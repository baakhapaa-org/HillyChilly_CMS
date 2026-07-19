<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Package;
use App\Models\RewardTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $bookings = $request->user()
            ->bookings()
            ->with('package')
            ->latest()
            ->get()
            ->map(fn($b) => $this->bookingResource($b));

        return response()->json(['data' => $bookings]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'package_id'   => 'required|exists:packages,id',
            'start_date'   => 'required|date|after:today',
            'participants' => 'required|integer|min:1|max:20',
        ]);

        $package = Package::findOrFail($data['package_id']);
        $total = $package->price_npr * $data['participants'];

        $booking = DB::transaction(function () use ($request, $data, $package, $total) {
            $booking = Booking::create([
                'user_id'          => $request->user()->id,
                'package_id'       => $package->id,
                'start_date'       => $data['start_date'],
                'participants'     => $data['participants'],
                'total_amount_npr' => $total,
                'points_reward'    => $package->points_reward,
                'status'           => 'upcoming',
            ]);

            // Award points immediately on booking
            $user = $request->user();
            $user->increment('points_balance', $package->points_reward);

            RewardTransaction::create([
                'user_id'     => $user->id,
                'booking_id'  => $booking->id,
                'points_delta'=> $package->points_reward,
                'reason'      => "Booking: {$package->title}",
            ]);

            return $booking;
        });

        return response()->json($this->bookingResource($booking->load('package')), 201);
    }

    public function show(Request $request, Booking $booking)
    {
        $this->authorizeBooking($request, $booking);
        return response()->json($this->bookingResource($booking->load('package')));
    }

    public function cancel(Request $request, Booking $booking)
    {
        $this->authorizeBooking($request, $booking);

        if ($booking->status === 'cancelled') {
            return response()->json(['message' => 'Booking already cancelled.'], 422);
        }

        $booking->update(['status' => 'cancelled']);
        return response()->json(['message' => 'Booking cancelled.']);
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $this->authorizeBooking($request, $booking);

        $data = $request->validate([
            'status' => 'required|in:upcoming,active,completed,cancelled',
        ]);

        $booking->update(['status' => $data['status']]);
        return response()->json($this->bookingResource($booking->load('package')));
    }

    private function authorizeBooking(Request $request, Booking $booking): void
    {
        if ($booking->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized.');
        }
    }

    private function bookingResource(Booking $b): array
    {
        return [
            'id'               => $b->id,
            'package_id'       => $b->package_id,
            'quest_title'      => $b->package?->title,
            'quest_image_url'  => $b->package?->image_url,
            'quest_location'   => $b->package?->location_label,
            'start_date'       => $b->start_date->toDateString(),
            'participants'     => $b->participants,
            'total_amount_npr' => $b->total_amount_npr,
            'points_reward'    => $b->points_reward,
            'duration_days'    => $b->package?->duration_days,
            'status'           => $b->status,
            'created_at'       => $b->created_at,
        ];
    }
}
