<?php

namespace App\Http\Controllers;

use App\Models\Flight;
use App\Models\Shift;
use App\Models\User;
use App\Models\Worker;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // Recent items
        $recentShifts = Shift::with('worker')->latest()->take(5)->get();
        $recentFlights = Flight::latest()->take(5)->get();
        $recentWorkers = Worker::latest()->take(5)->get();

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
        $flights = Flight::where('status', 'scheduled')->get();
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
            'flight_id'   => 'required|exists:flights,id',
        ]);

        // Create the shift
        Shift::create($validated);

        return redirect()->route('admin.add.shifts')->with('success', 'Shift added successfully!');
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
            'worker_id'  => 'required|exists:workers,id',
            'start_time' => 'required|date',
            'end_time'   => 'required|date|after:start_time',
            'notes'      => 'nullable|string|max:500',
        ]);

        // Create the shift
        $shift = Shift::create([
            'worker_id'  => $request->worker_id,
            'start_time' => $request->start_time,
            'end_time'   => $request->end_time,
            'notes'      => $request->notes,
        ]);

        // Optional: redirect back with a success message
        return redirect()->back()->with('success', 'Shift added successfully.');
    }
}
