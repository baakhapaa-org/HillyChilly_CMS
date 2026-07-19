<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingTaskCompletion;
use App\Models\PackageTask;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TaskCompletionController extends Controller
{
    /**
     * GET /bookings/{booking}/tasks
     * Return completed task IDs so Flutter can restore progress after
     * reinstall or on a new device.
     */
    public function index(Request $request, Booking $booking): JsonResponse
    {
        $this->authorizeBooking($request, $booking);

        $completedIds = $booking->taskCompletions()
            ->pluck('task_id')
            ->map(fn ($id) => (string) $id);

        return response()->json(['completed_task_ids' => $completedIds]);
    }

    /**
     * POST /bookings/{booking}/tasks/{task}/complete
     * Mark a single task as completed.
     */
    public function complete(Request $request, Booking $booking, PackageTask $task): JsonResponse
    {
        $this->authorizeBooking($request, $booking);

        if ($task->package_id !== $booking->package_id) {
            return response()->json(['message' => 'Task does not belong to this quest.'], 422);
        }

        BookingTaskCompletion::firstOrCreate(
            ['booking_id' => $booking->id, 'task_id' => $task->id],
            ['completed_at' => now()]
        );

        return response()->json(['message' => 'Task marked as completed.']);
    }

    private function authorizeBooking(Request $request, Booking $booking): void
    {
        if ($booking->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized.');
        }
    }
}
