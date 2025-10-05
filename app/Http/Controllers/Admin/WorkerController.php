<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Worker;

class WorkerController extends Controller
{
    // List all workers
    public function index(Request $request)
{
    $search = $request->input('search');

    $workers = \App\Models\Worker::query()
        ->when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        })
        ->orderBy('first_name', 'asc') // Alphabetical order
        ->get(); // Removed pagination

    return view('admin.workers.index', compact('workers', 'search'));
}

    // Show create form
    public function create()
    {
        return view('admin.workers.create');
    }

    // Store worker
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:workers,email',
            'phone' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:50',
            'status' => 'required|in:active,inactive',
        ]);

        Worker::create($request->all());
        return redirect()->route('workers.index')->with('success', 'Worker added successfully.');
    }

    // Show edit form
    public function edit(Worker $worker)
    {
        return view('admin.workers.edit', compact('worker'));
    }

    // Update worker
    public function update(Request $request, Worker $worker)
    {
        $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:workers,email,' . $worker->id,
            'phone' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:50',
            'status' => 'required|in:active,inactive',
        ]);

        $worker->update($request->all());
        return redirect()->route('workers.index')->with('success', 'Worker updated successfully.');
    }

    // Delete worker
    public function destroy(Worker $worker)
    {
        $worker->delete();
        return redirect()->route('workers.index')->with('success', 'Worker deleted successfully.');
    }
}
