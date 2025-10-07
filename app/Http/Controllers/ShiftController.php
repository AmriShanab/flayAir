<?php
// app/Http/Controllers/ShiftController.php
namespace App\Http\Controllers;

use App\Models\Flight;
use App\Models\Notification;
use App\Models\Shift;
use App\Models\Worker;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ShiftController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->get('date', now()->format('Y-m-d'));

        $workers = Worker::active()
            ->with(['shifts' => function ($query) use ($date) {
                $query->forDate($date);
            }])
            ->orderBy('first_name')
            ->get();

        $shifts = Shift::with(['worker', 'flight'])
            ->forDate($date)
            ->orderBy('start_time')
            ->get();

        $flights = Flight::whereDate('date', $date)
            ->get();
        // dd($flights);
        return view('shifts.index', [
            'workers' => $workers,
            'shifts'  => $shifts,
            'date'    => $date,
            'flights' => $flights,
            // 👇 Pass JSON version of shifts for JS
            'shiftsJson' => $shifts->toJson(),
        ]);
    }


    public function assignFlight(Request $request)
    {
        $validated = $request->validate([
            'flight_id' => 'required|exists:flights,id',
            'worker_id' => 'required|exists:workers,id',
            'start_time' => 'required'
        ]);

        try {
            // Get the flight to calculate end time
            $flight = Flight::find($validated['flight_id']);

            // Parse the start time (format: HH:MM:SS)
            $startTime = Carbon::createFromFormat('H:i:s', $validated['start_time']);

            // Use today's date with the time
            $startDateTime = Carbon::today()
                ->setHour($startTime->hour)
                ->setMinute($startTime->minute)
                ->setSecond($startTime->second);

            // Calculate end time based on flight duration
            $endDateTime = $startDateTime->copy()->addMinutes($flight->duration + 30);


            // Create a new shift for the worker with this flight
            $shift = new Shift();
            $shift->worker_id = $validated['worker_id'];
            $shift->flight_id = $validated['flight_id'];
            $shift->start_time = $startDateTime;
            $shift->end_time = $endDateTime;
            $shift->save();

            // Update flight status to 'assigned'
            $flight->status = 'assigned';
            $flight->save();

            return response()->json(['success' => true, 'message' => 'Flight assigned successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    // public function flightIndex()
    // {
    //     $date = Carbon::now();
    //     $flights = Flight::whereDate('date', $date)->get();

    //     return view('admin.shifts.flight_shift', compact('flights'));
    // }

    public function welcome()
    {
        return view('welcome');
    }

    public function getShiftsForDate(Request $request): JsonResponse
    {
        $request->validate([
            'date' => 'required|date'
        ]);

        $date = Carbon::parse($request->date);

        $shifts = Shift::with(['worker', 'flight'])
            ->whereDate('start_time', $date)
            ->orderBy('start_time')
            ->get()
            ->map(function ($shift) {
                return [
                    'id' => $shift->id,
                    'worker_id' => $shift->worker_id,
                    'worker_name' => $shift->worker->full_name,
                    'start_time' => $shift->start_time->format('H:i'),
                    'end_time' => $shift->end_time->format('H:i'),
                    'shift_type' => $shift->shift_type,
                    'notes' => $shift->notes,
                    'color' => $this->getShiftColor($shift->shift_type),
                    'flight_id' => $shift->flight_id,
                    'break_time_start' => $shift->break_time_start?->format('H:i'), // ✅ add this
                    'break_time_end' => $shift->break_time_end?->format('H:i'),     // ✅ add this
                    'flight' => $shift->flight ? [
                        'id' => $shift->flight->id,
                        'flight_number' => $shift->flight->flight_number,
                        'status' => $shift->flight->status,
                        'type' => $shift->flight->type,
                        'origin' => $shift->flight->origin ?? null,
                        'destination' => $shift->flight->destination ?? null,
                    ] : null,
                ];
            });


        return response()->json($shifts);
    }


    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'worker_id' => 'required|exists:workers,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'shift_type' => 'required|in:morning,afternoon,evening,night',
            'notes' => 'nullable|string'
        ]);

        // Check for overlapping shifts
        $overlapping = Shift::where('worker_id', $validated['worker_id'])
            ->where(function ($query) use ($validated) {
                $query->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                    ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']])
                    ->orWhere(function ($q) use ($validated) {
                        $q->where('start_time', '<', $validated['start_time'])
                            ->where('end_time', '>', $validated['end_time']);
                    });
            })
            ->exists();

        if ($overlapping) {
            return response()->json([
                'error' => 'Worker already has a shift during this time'
            ], 422);
        }

        $shift = Shift::create($validated);

        return response()->json([
            'message' => 'Shift created successfully',
            'shift' => $shift->load('worker')
        ]);
    }

    public function update(Request $request, Shift $shift): JsonResponse
    {
        $validated = $request->validate([
            'worker_id' => 'sometimes|exists:workers,id',
            'start_time' => 'sometimes|date',
            'end_time' => 'sometimes|date|after:start_time',
            'shift_type' => 'sometimes|in:morning,afternoon,evening,night',
            'notes' => 'nullable|string'
        ]);

        $shift->update($validated);

        return response()->json([
            'message' => 'Shift updated successfully',
            'shift' => $shift->load('worker')
        ]);
    }

    public function destroy(Shift $shift): JsonResponse
    {
        $shift->delete();

        return response()->json([
            'message' => 'Shift deleted successfully'
        ]);
    }

    private function getShiftColor(string $type): string
    {
        return match ($type) {
            'morning' => 'bg-green-100 border-green-500 text-green-800',
            'afternoon' => 'bg-blue-100 border-blue-500 text-blue-800',
            'evening' => 'bg-purple-100 border-purple-500 text-purple-800',
            'night' => 'bg-yellow-100 border-yellow-500 text-yellow-800',
            default => 'bg-gray-100 border-gray-500 text-gray-800'
        };
    }

    public function getFlightsForDate(Request $request): JsonResponse
    {
        $request->validate([
            'date' => 'required|date'
        ]);

        $date = \Carbon\Carbon::parse($request->date);

        $flights = \App\Models\Flight::whereDate('date', $date)
            ->orderBy('scheduled_time')
            ->get()
            ->map(function ($flight) {
                return [
                    'id' => $flight->id,
                    'flight_number' => $flight->flight_number,
                    'type' => $flight->type, // arrival / departure
                    'scheduled_time' => $flight->scheduled_time->format('H:i'),
                    'status' => $flight->status,
                    'origin' => $flight->origin,
                    'destination' => $flight->destination,
                    'notes' => $flight->notes,
                    'airline' => $flight->airline,
                ];
            });

        return response()->json($flights);
    }

    public function viewSettings()
    {
        return view('shifts.settings');
    }


    public function notifications()
    {
        $worker = Auth::user()->worker; // uses the relationship

        if (!$worker) {
            abort(403, 'No worker found for this user.');
        }

        $notifications = Notification::where('worker_id', $worker->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // dd($notifications);

        return view('shifts.notifications', compact('notifications'));
    }

    public function acknowledge($id)
    {
        $notification = Notification::find($id);

        if ($notification) {
            $notification->is_read = 2;
            $notification->acknowledged_at = now();
            $notification->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }

    public function dismiss($id)
    {
        $notification = Notification::find($id);
        if ($notification) {
            $notification->is_read = 3; // 3 = dismissed
            $notification->save();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 404);
    }



    public function markAsRead($id)
    {
        $notification = Notification::find($id);

        if ($notification) {
            $notification->is_read = 1;          // mark as read
            $notification->acknowledged_at = now(); // optional timestamp
            $notification->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }

    public function decline($id, Request $request)
    {
        // dd($request->all());
        try {
            $notification = Notification::findOrFail($id);

            // Update notification status and store decline reason
            $notification->update([
                'is_read' => 3, // You can use 3 for declined status
                'decline_reason' => $request->reason,
                'acknowledged' => 0, // Not acknowledged since it's declined
                'acknowledged_at' => null,
            ]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}
