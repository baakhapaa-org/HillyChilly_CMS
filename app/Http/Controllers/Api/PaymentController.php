<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Package;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\ApiErrorException;
use Stripe\Exception\SignatureVerificationException;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Stripe\Webhook;

class PaymentController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    // ── Create PaymentIntent ───────────────────────────────────────────────

    /**
     * POST /api/v1/payments/intent
     * Body: { quest_id, quantity? }
     *
     * Returns: { client_secret, amount_npr, currency }
     */
    public function createIntent(Request $request): JsonResponse
    {
        $data = $request->validate([
            'quest_id' => 'required|exists:packages,id',
            'quantity' => 'sometimes|integer|min:1|max:20',
        ]);

        $quest    = Package::findOrFail($data['quest_id']);
        $quantity = $data['quantity'] ?? 1;
        $amountNpr = $quest->price_npr * $quantity;

        if ($amountNpr <= 0) {
            return response()->json([
                'error' => 'This quest is free — no payment required.',
            ], 422);
        }

        try {
            // Stripe requires amounts in the smallest currency unit.
            // NPR is a zero-decimal currency (no paise), so amount = NPR directly.
            $intent = PaymentIntent::create([
                'amount'   => $amountNpr,
                'currency' => 'npr',
                'metadata' => [
                    'quest_id'    => $quest->id,
                    'quest_title' => $quest->title,
                    'user_id'     => $request->user()->id,
                    'quantity'    => $quantity,
                ],
                'automatic_payment_methods' => ['enabled' => true],
            ]);

            return response()->json([
                'client_secret' => $intent->client_secret,
                'amount_npr'    => $amountNpr,
                'currency'      => 'npr',
            ]);
        } catch (ApiErrorException $e) {
            Log::error('Stripe createIntent error', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Payment service unavailable.'], 503);
        }
    }

    // ── Stripe Webhook ─────────────────────────────────────────────────────

    /**
     * POST /webhook/stripe
     * Marks booking as paid when Stripe confirms the PaymentIntent.
     */
    public function webhook(Request $request): JsonResponse
    {
        $payload   = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature', '');
        $secret    = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $secret);
        } catch (SignatureVerificationException $e) {
            Log::warning('Stripe webhook signature mismatch');
            return response()->json(['error' => 'Invalid signature.'], 400);
        }

        if ($event->type === 'payment_intent.succeeded') {
            $intent = $event->data->object;

            // Mark matching booking as paid if it exists.
            Booking::where('payment_intent_id', $intent->id)
                ->where('payment_status', 'pending')
                ->update(['payment_status' => 'paid']);

            Log::info('Stripe payment confirmed', ['intent_id' => $intent->id]);
        }

        return response()->json(['received' => true]);
    }
}
