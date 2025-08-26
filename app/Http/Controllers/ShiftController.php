<?php
// app/Http/Controllers/ShiftController.php
namespace App\Http\Controllers;

use App\Models\Flight;
use App\Models\Shift;
use App\Models\Worker;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

        $shifts = Shift::with('worker')
            ->forDate($date)
            ->orderBy('start_time')
            ->get();

        $flights = Flight::whereDate('date', $date)->get();

        return view('shifts.index', compact('workers', 'shifts', 'date', 'flights'));
    }

    public function flightIndex()
    {
        $date = Carbon::now();
        $flights = Flight::whereDate('date', $date)->get();

        return view('admin.shifts.flight_shift', compact('flights'));
    }

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

        $shifts = Shift::with('worker')
            ->whereDate('start_time', $date)
            ->orderBy('start_time')
            ->get()
            ->map(function ($shift) {
                return [
                    'id' => $shift->id,
                    'worker_id' => $shift->worker_id, // Make sure this is included
                    'worker_name' => $shift->worker->full_name,
                    'start_time' => $shift->start_time->format('H:i'),
                    'end_time' => $shift->end_time->format('H:i'),
                    'shift_type' => $shift->shift_type,
                    'notes' => $shift->notes, // Include notes if needed
                    'color' => $this->getShiftColor($shift->shift_type)
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
}
