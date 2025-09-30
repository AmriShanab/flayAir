<?php

namespace App\Http\Controllers;

use App\Mail\ShiftNotificationMail;
use App\Models\Flight;
use App\Models\Notification;
use App\Models\Shift;
use App\Models\User;
use App\Models\Worker;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    public function index()
    {
        // Recent items
        $recentShifts = Shift::with('worker')->latest()->take(5)->get();

        // Get all flights scheduled for today
        $recentFlights = Flight::whereDate('date', today())
            ->orderBy('date', 'asc')
            ->get();

        $recentWorkers = Worker::whereHas('shifts', function ($query) {
            $query->whereDate('start_time', today());
        })
            ->latest()
            ->get();

        // Statistics for the dashboard cards
        $totalWorkers = Worker::count();
        $totalShifts = Shift::whereBetween('start_time', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ])->count();
        $totalFlights = Flight::whereDate('date', today())->count();

        return view('admin.index', compact(
            'recentShifts',
            'recentFlights',
            'recentWorkers',
            'totalWorkers',
            'totalShifts',
            'totalFlights'
        ));
    }

    public function addShifts()
    {
        $workers = Worker::all(); // fetch all workers
        $flights = Flight::where('status', 'scheduled')
            ->whereDate('date', today())
            ->get();
        return view('admin.add_shifts', compact('workers', 'flights'));
    }

    public function addFlights()
    {
        return view('admin.add_flights');
    }

    public function storeFlights(Request $request)
    {
        //validate the details
        $validated = $request->validate([
            'flight_number' => 'required|string|max:255',
            'type' => 'required|in:arrival,departure',
            'scheduled_time' => 'required|date_format:H:i',
            'date' => 'required|date',
            'origin' => 'nullable|string|max:255',
            'destination' => 'nullable|string|max:255',
            'airline' => 'nullable|string|max:255',
            'status' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        Flight::create($validated);

        return redirect()->route('admin.add.flights')->with('success', 'Flight added successfully!');
    }

    public function storeShifts(Request $request)
    {

        // Validate the request
        $validated = $request->validate([
            'worker_id' => 'required|exists:workers,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'notes' => 'nullable|string',
            'flight_id' => 'required|exists:flights,id',
            'shift_type' => 'required|integer', // validate shift_type
        ]);


        // Optional: If you want to auto-set break times for shift_type = 3
        // if ($validated['shift_type'] == 3 && isset($validated['break_time_start'])) {
        //     $breakStart = new \DateTime($validated['break_time_start']);
        //     $breakEnd = (clone $breakStart)->modify('+1 hour');
        //     $validated['break_time_end'] = $breakEnd->format('Y-m-d H:i:s');
        // }

        // Create the shift
        $shift = Shift::create($validated);

        // Create notification
        $messageText = "Your shift has been assigned/updated.";
        Notification::create([
            'worker_id' => $shift->worker_id,
            'title' => 'Shift Updated',
            'message' => $messageText,
            'shift_start' => $shift->start_time,
            'shift_end' => $shift->end_time,
            'flight_id' => $shift->flight_id,
        ]);

        // Send email to worker
        Mail::to($shift->worker->email)->send(new ShiftNotificationMail($shift, $messageText));

        return redirect()->route('admin.add.shifts')->with('success', 'Shift added successfully!');
    }

    public function storeShiftsWeb(Request $request)
    {
        $validated = $request->validate([
            'worker_id'  => 'required|exists:workers,id',
            'start_time' => 'required|date',
            'end_time'   => 'required|date|after:start_time',
            'notes'      => 'nullable|string',
            'flight_id'  => 'nullable|exists:flights,id', // changed from required → nullable
            'shift_type' => 'required|integer',
        ]);

        // Normalize times (ensure proper DB datetime format)
        $validated['start_time'] = \Carbon\Carbon::parse($validated['start_time']);
        $validated['end_time']   = \Carbon\Carbon::parse($validated['end_time']);

        // Create the shift
        $shift = Shift::create($validated);

        // Create notification
        $messageText = "Your shift has been assigned/updated.";
        Notification::create([
            'worker_id'   => $shift->worker_id,
            'title'       => 'Shift Updated',
            'message'     => $messageText,
            'shift_start' => $shift->start_time,
            'shift_end'   => $shift->end_time,
            'flight_id'   => $shift->flight_id,
        ]);

        // Send email to worker
        Mail::to($shift->worker->email)->send(new ShiftNotificationMail($shift, $messageText));

        return redirect()->route('admin.add.shifts')->with('success', 'Shift added successfully!');
    }


    public function storeDragDropShift(Request $request)
    {
        $validated = $request->validate([
            'worker_id' => 'required|exists:workers,id',
            'flight_id' => 'required|exists:flights,id',
            'notes'     => 'nullable|string',
        ]);

        $flight = Flight::findOrFail($validated['flight_id']);

        // ✅ Combine flight->date with the time coming from JS (e.g. "15:00:00")
        $startTime = Carbon::parse($request->input('start_time'));
        $endTime   = Carbon::parse($request->input('end_time'));

        $shift = Shift::create([
            'worker_id'       => $validated['worker_id'],
            'flight_id'       => $validated['flight_id'],
            'start_time'      => $startTime,
            'end_time'        => $endTime,
            'break_time_start' => null,
            'break_time_end'  => null,
            'notes'           => $validated['notes'] ?? null,
            'shift_type'      => 1, // regular shift
        ]);

        // Optionally update flight status
        $flight->update(['status' => 'assigned']);

        return response()->json([
            'success' => true,
            'message' => 'Shift created successfully!',
            'shift'   => $shift,
        ]);
    }

    public function viewShifts()
    {
        $twoDaysAgo = Carbon::now()->subDays(2);

        $shifts = Shift::with(['worker', 'flight'])
            ->where('start_time', '>=', $twoDaysAgo)
            ->where('shift_type', '!=', 3) // Exclude shift_type = 3 (breaks)
            ->orderBy('start_time', 'desc')
            ->paginate(10);

        return view('admin.view_shifts', compact('shifts'));
    }


    public function unBlockUser($id)
    {
        $user = User::find($id);
        $user->update(['is_locked' => false, 'login_attempts' => 0]);
        return redirect()->back()->with('success', 'User unblocked successfully.');
    }

    public function listUsers()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    public function storeShiftsFromShiftsPage(Request $request)
    {
        // Validate the request
        $request->validate([
            'worker_id'        => 'required|exists:workers,id',
            'flight_id'        => 'nullable|exists:flights,id', // ✅ add this
            'start_time'       => 'required|date',
            'end_time'         => 'required|date|after:start_time',
            'break_start_time' => 'nullable|date|after_or_equal:start_time|before:end_time',
            'break_end_time'   => 'nullable|date|after:break_start_time|before_or_equal:end_time',
            'notes'            => 'nullable|string|max:500',
        ]);

        $start = \Carbon\Carbon::parse($request->start_time);
        $end   = \Carbon\Carbon::parse($request->end_time);

        // Case 1: same-day shift
        if ($start->isSameDay($end)) {
            $shift = Shift::create([
                'worker_id'        => $request->worker_id,
                'flight_id'        => $request->flight_id, // ✅ store flight
                'start_time'       => $request->start_time,
                'end_time'         => $request->end_time,
                'break_time_start' => $request->break_start_time,
                'break_time_end'   => $request->break_end_time,
                'status'           => 'scheduled',
                'notes'            => $request->notes,
            ]);

            $this->notifyWorker($shift, "A new shift has been assigned to you.");
        }
        // Case 2: overnight shift → split into 2
        else {
            // First part: until 23:59:59 of start day
            $shift1 = Shift::create([
                'worker_id'       => $request->worker_id,
                'flight_id'        => $request->flight_id,
                'start_time'      => $start,
                'end_time'        => $start->copy()->endOfDay(),
                'break_time_start' => ($request->break_start_time &&
                    \Carbon\Carbon::parse($request->break_start_time)->isSameDay($start))
                    ? $request->break_start_time : null,
                'break_time_end'   => ($request->break_end_time &&
                    \Carbon\Carbon::parse($request->break_end_time)->isSameDay($start))
                    ? $request->break_end_time : null,
                'status'          => 'scheduled',
                'notes'           => $request->notes,
            ]);

            // Second part: from 00:00 of next day until real end
            $shift2 = Shift::create([
                'worker_id'       => $request->worker_id,
                'flight_id'        => $request->flight_id,
                'start_time'      => $end->copy()->startOfDay(),
                'end_time'        => $end,
                'break_time_start' => ($request->break_start_time &&
                    \Carbon\Carbon::parse($request->break_start_time)->isSameDay($end))
                    ? $request->break_start_time : null,
                'break_time_end'   => ($request->break_end_time &&
                    \Carbon\Carbon::parse($request->break_end_time)->isSameDay($end))
                    ? $request->break_end_time : null,
                'status'          => 'scheduled',
                'notes'           => $request->notes,
            ]);

            // Notifications
            $this->notifyWorker($shift1, "A new shift has been assigned to you.");
            $this->notifyWorker($shift2, "A new shift has been assigned to you.");
        }

        return redirect()->back()->with('success', 'Shift added successfully.');
    }

    /**
     * Notify worker by DB + email
     */
    protected function notifyWorker($shift, $messageText)
    {
        Notification::create([
            'worker_id' => $shift->worker_id,
            'title'     => 'New Shift Assigned',
            'message'   => $messageText,
        ]);

        Mail::to($shift->worker->email)->send(new ShiftNotificationMail($shift, $messageText));
    }


    /**
     * Determine shift type based on start time
     */



    public function updateShift(Request $request, $id)
    {
        $shift = Shift::findOrFail($id);
        $shift->start_time = \Carbon\Carbon::parse($request->input('start_time'));
        $shift->end_time   = \Carbon\Carbon::parse($request->input('end_time'));
        $shift->notes      = $request->input('notes');
        $shift->save();

        // Create a notification for the worker
        $messageText = "Your shift has been updated.";
        Notification::create([
            'worker_id' => $shift->worker_id,
            'title' => 'Shift Updated',
            'message' => $messageText,
        ]);

        // Send email
        Mail::to($shift->worker->email)->send(new ShiftNotificationMail($shift, $messageText));

        return response()->json(['success' => true]);
    }


    public function destroyShift($id)
    {
        $shift = Shift::findOrFail($id);
        $shift->delete();
        return response()->json(['success' => true]);
    }

    public function viewAllNotifications()
    {
        // Fetch notifications with related worker
        $notifications = Notification::with('worker')->latest()->paginate(10);
        // dd($notifications);

        // Pass to view
        return view('admin.notifications.index', compact('notifications'));
    }

    // In your controller (e.g., ShiftController.php)

    public function addBreak()
    {
        $workers = Worker::all(); // fetch all workers
        return view('admin.add_break', compact('workers'));
    }

    public function storeBreak(Request $request)
    {
        $request->validate([
            'worker_id' => 'required|exists:workers,id',
            'break_time_start' => 'required|date',
            'break_time_end' => 'required|date|after:break_time_start',
            'notes' => 'nullable|string|max:500',
        ]);
        $message = "It's Time to have break";
        try {
            // Create the break record
            $break = new Shift();
            $break->worker_id = $request->worker_id;
            $break->shift_type = 3; // Break type
            $break->start_time = $request->break_time_start;
            $break->end_time = $request->break_time_end;
            $break->notes = $message;
            $break->save();

            return redirect()->route('admin.dashboard')->with('success', 'Break added successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error adding break: ' . $e->getMessage());
        }

        // Should needs to send email and Notification Here 
    }

    public function updateTodayStatus()
    {
        $today = Carbon::today();

        // Get all workers
        $workers = Worker::all();

        foreach ($workers as $worker) {
            // Check if worker has a shift today
            $hasShiftToday = Shift::where('worker_id', $worker->id)
                ->whereDate('start_time', '<=', $today)
                ->whereDate('end_time', '>=', $today)
                ->exists();

            // Update worker status
            $worker->status = $hasShiftToday ? 'active' : 'inactive';
            $worker->save();
        }

        return response()->json([
            'message' => 'Worker statuses updated successfully!'
        ]);
    }
}
