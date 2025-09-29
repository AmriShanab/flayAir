@extends('layouts.app')

@section('content')
<head>
    <link rel="stylesheet" href="{{ asset('css/shift-timeline.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>
<style>
.timeline-header {
    display: flex;
    width: 100%;
    background: white;
    border-bottom: 1px solid #e5e7eb;
    position: sticky;
    top: 0;
    z-index: 20;
}

.timeline-slot-container {
    display: flex;
    width: 3840px; /* 96 slots × 40px */
}

.timeline-slot {
    width: 40px;
    min-width: 40px;
    height: 48px;
    border-right: 1px solid #e5e7eb;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    box-sizing: border-box;
}

.timeline-slot.hour {
    background-color: #f9fafb;
    font-weight: 500;
    color: #374151;
}

.timeline-slot.quarter {
    background-color: white;
    color: #6b7280;
}

/* Worker row styling */
.worker-row {
    display: flex;
    height: 80px;
    border-bottom: 1px solid #e5e7eb;
    position: relative;
    width: 100%;
}

.worker-name-cell {
    position: sticky;
    left: 0;
    z-index: 15;
    background: white;
    border-right: 1px solid #e5e7eb;
    width: 200px;
    min-width: 200px;
    padding: 0 16px;
    display: flex;
    align-items: center;
    height: 80px;
    box-sizing: border-box;
}

.worker-timeline {
    position: relative;
    width: 3840px;
    height: 80px;
}

.time-slots-container {
    display: flex;
    width: 3840px;
    height: 100%;
}

.time-slot {
    width: 40px;
    min-width: 40px;
    height: 100%;
    border-right: 1px solid #e5e7eb;
    box-sizing: border-box;
    position: relative;
}

.time-slot.hour {
    background-color: #f9fafb;
}

.time-slot.quarter {
    background-color: white;
}

.time-slot:hover {
    background-color: #f3f4f6;
}

.time-label {
    position: absolute;
    top: 2px;
    left: 2px;
    font-size: 9px;
    color: #9ca3af;
    opacity: 0;
    transition: opacity 0.2s;
}

.time-slot:hover .time-label {
    opacity: 1;
}

/* Shift block styling */
.shift-block {
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    position: absolute;
    top: 2px;
    bottom: 2px;
    z-index: 10;
    border-radius: 4px;
    cursor: pointer;
    overflow: hidden;
}

.shift-block:hover {
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    transform: translateY(-1px);
    z-index: 20;
}

.shift-content {
    /* padding: 6px; */
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Container styling */
.timeline-container {
    overflow-x: auto;
}

.workers-container {
    width: 100%;
}

/* Custom scrollbar */
.timeline-container::-webkit-scrollbar {
    height: 12px;
}

.timeline-container::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 6px;
}

.timeline-container::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 6px;
    border: 2px solid #f1f5f9;
}

.timeline-container::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Modal styling */
.max-h-80vh {
    max-height: 80vh;
}

/* Flight timeline styling */
.flight-timeline-container {
    margin-top: 2rem;
    border-top: 2px solid #e5e7eb;
    padding-top: 1rem;
    width: 100%;
}

.flight-timeline-header {
    display: flex;
    width: 100%;
    background: white;
    border-bottom: 1px solid #e5e7eb;
    position: sticky;
    top: 0;
    z-index: 20;
}

.flight-timeline-label {
    position: sticky;
    left: 0;
    z-index: 15;
    background: white;
    border-right: 1px solid #e5e7eb;
    width: 200px;
    min-width: 200px;
    padding: 0 16px;
    display: flex;
    align-items: center;
    height: 48px;
    box-sizing: border-box;
    font-weight: 600;
    color: #374151;
}

.flight-timeline-slot-container {
    display: flex;
    width: 3840px; /* Must match the width of the main timeline */
}

.flight-timeline {
    position: relative;
    width: 3840px; /* Must match the width of the main timeline */
    min-height: 60px;
    background: #f9fafb;
    border-bottom: 1px solid #e5e7eb;
    margin-left: 190px;
}

.flight-item {
    position: absolute;
    top: 8px;
    height: 44px;
    min-width: 120px;
    background: white;
    border-radius: 6px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    padding: 8px;
    cursor: pointer;
    z-index: 10;
    overflow: hidden;
    border-left: 4px solid;
}

.flight-item:hover {
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    z-index: 20;
}

.flight-item-content {
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.flight-number {
    font-weight: 600;
    font-size: 12px;
    line-height: 1.2;
}

.flight-time {
    font-size: 10px;
    color: #6b7280;
}

.flight-tooltip {
    position: absolute;
    left: 8px;
    top: 50%;
    transform: translateY(-50%);
    background: white;
    padding: 12px;
    border-radius: 6px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    min-width: 200px;
    opacity: 0;
    visibility: hidden;
    transition: all 0.2s ease;
    z-index: 30;
    pointer-events: none;
}

.flight-item:hover .flight-tooltip {
    opacity: 1;
    visibility: visible;
}

.flight-tooltip::before {
    content: '';
    position: absolute;
    left: -6px;
    top: 50%;
    transform: translateY(-50%);
    width: 0;
    height: 0;
    border-top: 6px solid transparent;
    border-bottom: 6px solid transparent;
    border-right: 6px solid white;
}

/* Ensure all timeline components have the same width */
.timeline-slot-container,
.worker-timeline,
.flight-timeline-slot-container,
.flight-timeline {
    width: 3840px; /* 96 slots × 40px */
}


[x-cloak] {
        display: none !important;
    }


    /* Scrollable container */
.timeline-container {
    overflow-x: auto;
    width: 100%;
}

/* Worker row: flex with sticky name column */
.worker-row {
    display: flex;
    min-width: max-content; /* important: prevent wrapping */
}

/* Worker name: sticky so it doesn’t scroll */
.worker-name-cell {
    position: sticky;
    left: 0;
    z-index: 15;
    background: white;
    border-right: 1px solid #e5e7eb;
    width: 200px;
    min-width: 200px;
    display: flex;
    align-items: center;
    padding: 0 12px;
    box-sizing: border-box;
}

/* Timeline scrolls horizontally */
.worker-timeline {
    flex: 1 1 auto;
    min-width: 3840px; /* 96 slots × 40px */
}

.time-slots-container {
    display: flex;
    width: 100%;
    min-width: 3840px; /* match timeline width */
}

/* Individual slot width */
.time-slot {
    width: 40px;
    min-width: 40px;
    box-sizing: border-box;
}


/* Timeline styling */
.timeline-header {
    display: flex;
    width: 100%;
    background: white;
    border-bottom: 1px solid #e5e7eb;
    position: sticky;
    top: 0;
    z-index: 20;
}

.timeline-slot-container {
    display: flex;
    width: 3840px; /* 96 slots × 40px */
}

.timeline-slot {
    width: 40px;
    min-width: 40px;
    height: 35px; /* updated height */
    border-right: 1px solid #e5e7eb;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    box-sizing: border-box;
}

.timeline-slot.hour {
    background-color: #f9fafb;
    font-weight: 500;
    color: #374151;
}

.timeline-slot.quarter {
    background-color: white;
    color: #6b7280;
}

/* Worker row styling */
.worker-row {
    display: flex;
    height: 45px; /* updated height */
    /* border-bottom: 1px solid #e5e7eb; */
    position: relative;
    width: 100%;
    min-width: max-content; /* prevent collapsing */
}

.worker-name-cell {
    position: sticky;
    left: 0;
    z-index: 15;
    background: white;
    /* border-right: 1px solid #e5e7eb; */
    width: 200px;
    min-width: 200px;
    padding: 0 12px;
    display: flex;
    align-items: center;
    height: 45px; /* updated height */
    box-sizing: border-box;
    font-size: 12px;
}

/* Timeline scrolls horizontally */
.worker-timeline {
    flex: 1 1 auto;
    min-width: 3840px; /* match timeline width */
    height: 45px; /* updated height */
}

.time-slots-container {
    display: flex;
    width: 100%;
    min-width: 3840px;
    height: 100%;
}

.time-slot {
    width: 40px;
    min-width: 40px;
    height: 100%;
    border-right: 1px solid #e5e7eb;
    box-sizing: border-box;
    position: relative;
}

.time-slot.hour {
    background-color: #f9fafb;
}

.time-slot.quarter {
    background-color: white;
}

.time-slot:hover {
    background-color: #f3f4f6;
}

.time-label {
    position: absolute;
    top: 1px; /* slightly adjusted for smaller height */
    left: 2px;
    font-size: 8px; /* smaller font for smaller height */
    color: #9ca3af;
    opacity: 0;
    transition: opacity 0.2s;
}

.time-slot:hover .time-label {
    opacity: 1;
}

/* Shift block styling */
.shift-block {
    transition: all 0.3s ease;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); /* smaller shadow for smaller height */
    position: absolute;
    z-index: 10;
    border-radius: 3px;
    cursor: pointer;
    overflow: hidden;
    margin-top: -5px;
}

.shift-block:hover {
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
    transform: translateY(-0.5px);
    z-index: 20;
}

.shift-content {
    /* padding: 4px; */
    height: 45px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Container styling */
.timeline-container {
    overflow-x: auto;
    width: 100%;
}

/* Worker row: flex with sticky name column */
.worker-row {
    display: flex;
    min-width: max-content; /* important: prevent wrapping */
}

/* Worker name: sticky so it doesn’t scroll */
.worker-name-cell {
    position: sticky;
    left: 0;
    z-index: 15;
    background: white;
    border-right: 1px solid #e5e7eb;
    width: 200px;
    min-width: 200px;
    display: flex;
    align-items: center;
    padding: 0 12px;
    box-sizing: border-box;
}

.shift-block {
    position: relative; /* needed for absolute children */
}

.shift-actions {
    opacity: 0;
    transition: opacity 0.2s ease-in-out;
    position: absolute;
    top: 2px;
    right: 2px;
    display: flex;
    gap: 4px;
    pointer-events: none; /* disable interaction when hidden */
}

.shift-block:hover .shift-actions {
    opacity: 1;
    pointer-events: auto; /* enable interaction on hover */
}

</style>

<div class="flex h-screen bg-gray-50">

    <!-- ===================== LEFT: Worker List ===================== -->
    {{-- <div class="w-64 bg-white border-r border-gray-200 shadow-sm p-4 overflow-y-auto">
        <!-- Staff Members Header -->
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-800">Staff Members</h2>
        </div>
        
        <!-- Worker Search -->
        <div class="relative mb-4">
            <input 
                type="text" 
                placeholder="Search staffs..." 
                class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                       outline-none transition">
            <svg xmlns="http://www.w3.org/2000/svg" 
                 class="h-5 w-5 absolute left-3 top-2.5 text-gray-400" 
                 viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" 
                      d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 
                         4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" 
                      clip-rule="evenodd" />
            </svg>
        </div>
        
        <!-- Worker List -->
        <ul class="space-y-2" id="workers-list">
            @foreach($workers as $worker)
                @php
                    $workerColor = generateWorkerColor($worker->position);
                @endphp
                <li class="flex items-center p-3 rounded-lg hover:bg-gray-50 cursor-pointer transition worker-item" 
                    data-worker-id="{{ $worker->id }}"
                    style="border-left: 4px solid {{ $workerColor }};">
                    
                    <!-- Worker Avatar -->
                    <div class="h-10 w-10 rounded-full flex items-center justify-center 
                                text-white font-semibold mr-3"
                         style="background-color: {{ $workerColor }};">
                        {{ substr($worker->first_name, 0, 1) }}
                    </div>
                    
                    <!-- Worker Info -->
                    <div>
                        <p class="font-medium text-gray-800">{{ $worker->full_name }}</p>
                        <p class="text-xs text-gray-500">
                            {{ $worker->shifts->count() > 0 ? 'Scheduled' : 'Available' }}
                        </p>
                    </div>
                </li>
            @endforeach
        </ul>
    </div> --}}


    <!-- ===================== RIGHT: Shift Dashboard ===================== -->
    <div class="flex-1 flex flex-col overflow-hidden">
        
        <!-- ===== Header ===== -->
        <div class="p-6 pb-0">
            <div class="flex justify-between items-center mb-6">
                
                <!-- Page Title -->
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Shift Schedule</h1>
                    <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($date)->format('F j, Y') }}</p>
                </div>
                <!-- Live Clocks -->
                <div class="flex flex-row items-end mr-6 ml-6 gap-2">
                    <div class="flex items-center gap-2 text-xs px-3 py-1 rounded-lg shadow bg-gradient-to-r from-blue-100 to-blue-50 border border-blue-300 text-blue-900 font-semibold">
                        <span class="font-semibold text-blue-700">Toronto:</span>
                        <span id="toronto-clock" class="font-mono tracking-wider text-base"></span>
                        <span class="ml-1 text-[10px] bg-blue-200 text-blue-800 px-1.5 py-0.5 rounded">EST</span>
                    </div>
                    <div class="flex items-center gap-2 text-xs px-3 py-1 rounded-lg shadow bg-gradient-to-r from-gray-100 to-gray-50 border border-gray-300 text-gray-900 font-semibold">
                        <span class="font-semibold text-gray-700">UTC:</span>
                        <span id="utc-clock" class="font-mono tracking-wider text-base"></span>
                        <span class="ml-1 text-[10px] bg-gray-200 text-gray-800 px-1.5 py-0.5 rounded">UTC</span>
                    </div>
                </div>
                <!-- Actions (Date nav, New Shift, Logout) -->
                <div class="flex items-center gap-4">

                    <!-- Date Navigation -->
                    <div class="flex items-center border rounded-lg overflow-hidden">
                        <button class="p-2 hover:bg-gray-100" id="prev-day">
                            <svg xmlns="http://www.w3.org/2000/svg" 
                                 class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" 
                                      d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 
                                         3.293a1 1 0 01-1.414 1.414l-4-4a1 1 
                                         0 010-1.414l4-4a1 1 0 011.414 0z" 
                                      clip-rule="evenodd" />
                            </svg>
                        </button>
                        <input type="date" id="shift-date" 
                               class="border-0 px-4 py-2 focus:ring-2 focus:ring-blue-500" 
                               value="{{ $date }}">
                        <button class="p-2 hover:bg-gray-100" id="next-day">
                            <svg xmlns="http://www.w3.org/2000/svg" 
                                 class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" 
                                      d="M7.293 14.707a1 1 0 010-1.414L10.586 
                                         10 7.293 6.707a1 1 0 011.414-1.414l4 
                                         4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" 
                                      clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>

                    <!-- Add Shift Button (Admin only) -->
                    <div x-data="{ openShiftForm: false }">
                        @auth
                            @if(Auth::user()->role === 'admin' || Auth::user()->role === 'super_admin')
                                <button 
                                    @click="openShiftForm = true"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg 
                                           hover:bg-blue-700 flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" 
                                         class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" 
                                              d="M10 3a1 1 0 011 1v5h5a1 1 
                                                 0 110 2h-5v5a1 1 
                                                 0 11-2 0v-5H4a1 1 
                                                 0 110-2h5V4a1 1 
                                                 0 011-1z" 
                                              clip-rule="evenodd" />
                                    </svg>
                                    New Shift
                                </button>
                            @endif
                        @endauth

                        <!-- Add Shift Modal -->
                        <div x-show="openShiftForm" x-cloak
                             class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                            <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 relative">
                                
                                <!-- Close Button -->
                                <button @click="openShiftForm = false"
                                        class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">
                                    ✕
                                </button>

                                <h2 class="text-xl font-bold text-gray-800 mb-4">Add New Shift</h2>

                             <form method="POST" action="{{ route('admin.store.shifts.web') }}">
                                    @csrf
                                    <!-- Worker Select -->
                                <input type="hidden" name="shift_type" value="1">
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Staffs</label>
                                        <select name="worker_id" class="w-full border-gray-300 rounded-lg mt-1">
                                            @foreach($workers as $worker)
                                                <option value="{{ $worker->id }}">{{ $worker->full_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Start Time -->
                                  <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Start Time</label>
                                        <input type="datetime-local" id="start_time" name="start_time" 
                                            class="w-full border-gray-300 rounded-lg mt-1">
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">End Time</label>
                                        <input type="datetime-local" id="end_time" name="end_time" 
                                            class="w-full border-gray-300 rounded-lg mt-1">
                                    </div>


                                    {{-- <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Break Start Time</label>
                                        <input type="text" id="break_start_time" name="break_start_time" 
                                            class="w-full border-gray-300 rounded-lg mt-1">
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Break End Time</label>
                                        <input type="text" id="break_end_time" name="break_end_time" 
                                            class="w-full border-gray-300 rounded-lg mt-1">
                                    </div> --}}
                                    
                                    <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Flight Number</label>
                                    <select name="flight_id" class="w-full border-gray-300 rounded-lg mt-1">
                                        <option value="">-- Select Flight --</option>
                                        @foreach($flights as $flight)
                                            <option value="{{ $flight->id }}">{{ $flight->flight_number }}</option>
                                        @endforeach
                                    </select>
                                </div>


                                    <!-- Notes -->
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Notes</label>
                                        <textarea name="notes" rows="3" 
                                                class="w-full border-gray-300 rounded-lg mt-1"
                                                placeholder="Add any notes for this shift..."></textarea>
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex justify-end gap-3">
                                        <button type="button" 
                                                @click="openShiftForm = false"
                                                class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">
                                            Cancel
                                        </button>
                                        <button type="submit"
                                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                            Save Shift
                                        </button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>

                    <!-- Logout Button -->
                     @auth
                        @php
                            $dashboardRoute = 'dashboard'; // default
                            if(Auth::user()->role === 'admin' || Auth::user()->role === 'super_admin') {
                                $dashboardRoute = 'admin.dashboard';
                            }
                        @endphp

                        <a href="{{ route($dashboardRoute) }}" 
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg 
                                hover:bg-gray-300 flex items-center gap-2">
                            {{-- <svg xmlns="http://www.w3.org/2000/svg" 
                                class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" 
                                    d="M10 3a1 1 0 011 1v4h4a1 1 0 110 2h-4v4a1 1 0 11-2 0v-4H5a1 1 0 110-2h4V4a1 1 0 011-1z" 
                                    clip-rule="evenodd" />
                            </svg> --}}
                            Back to Dashboard
                        </a>
                    @endauth
                </div>
            </div>
        </div>


        <!-- ===== Schedule Container ===== -->
        <div class="flex-1 overflow-auto px-6 pb-6 timeline-container">

            <!-- Timeline Header -->
            <div class="timeline-header">
                <div class="worker-name-cell font-semibold text-gray-700">
                    Staffs / Time
                </div>
                <div class="flex">
                    @for($slot = 0; $slot < 96; $slot++)
                        @php
                            $hour = floor($slot / 4);
                            $minute = ($slot % 4) * 15;
                            $isHourMark = $minute === 0;
                        @endphp
                        <div class="timeline-slot {{ $isHourMark ? 'hour' : 'quarter' }}">
                            {{ $isHourMark ? str_pad($hour, 2, '0', STR_PAD_LEFT).':00' : $minute }}
                        </div>
                    @endfor
                </div>
            </div>

            <!-- Worker Rows -->
            <div class="workers-container">
                @foreach($workers as $worker)
                    @php
                        $workerColor = generateWorkerColor($worker->position);
                    @endphp
                    <div class="worker-row" data-worker-id="{{ $worker->id }}">
                        
                        <!-- Worker Name Cell -->
                        <div class="worker-name-cell" style="background-color: {{ $workerColor }}90;">
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-full flex items-center justify-center 
                                            text-white font-semibold mr-3"
                                     style="background-color: {{ $workerColor }};">
                                    {{ substr($worker->first_name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-medium text-white-800">{{ $worker->first_name }}</p>
                                    <p class="text-xs text-white-500">{{ $worker->position }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Timeline Slots -->
                        <div class="worker-timeline droppable" data-worker-id="{{ $worker->id }}">
                            <div class="time-slots-container">
                                @for($slot = 0; $slot < 96; $slot++)
                                    @php
                                        $hour = floor($slot / 4);
                                        $minute = ($slot % 4) * 15;
                                        $timeString = str_pad($hour, 2, '0', STR_PAD_LEFT).':'.
                                                      str_pad($minute, 2, '0', STR_PAD_LEFT);
                                        $isHourMark = $minute === 0;
                                    @endphp

                                    <div class="time-slot {{ $isHourMark ? 'hour' : 'quarter' }}" 
                                         data-time="{{ $timeString }}"
                                         data-slot-index="{{ $slot }}"
                                         data-worker-id="{{ $worker->id }}">
                                        <div class="time-label">
                                            {{ $timeString }}
                                        </div>
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @auth
                @if(Auth::user()->role === 'admin' || Auth::user()->role === 'super_admin')
                    <!-- ===== Available Flights Section ===== -->
                    <div class="mt-8">
                        <h2 class="text-xl font-bold text-gray-800 mb-4">Available Flights</h2>

                        <!-- Container: use flex-wrap to avoid horizontal scroll -->
                        <div class="flex flex-wrap gap-4 max-w-full overflow-x-hidden" id="available-flights">
                            @foreach($flights as $flight)
                                <div class="flight-tile p-2 bg-white border rounded shadow cursor-move"
                                    draggable="true"
                                    data-flight-id="{{ $flight->id }}"
                                    data-departure-time="{{ \Carbon\Carbon::parse($flight->scheduled_time)->toIso8601String() }}">
                                    {{ $flight->flight_number }} ({{ \Carbon\Carbon::parse($flight->scheduled_time)->format('H:i') }})
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endauth


            {{-- <div class="flight-timeline" id="flight-timeline">
                
            </div> --}}
        </div>
    </div>
</div>


<!-- ===================== Shift Details Modal ===================== -->
<!-- Shift Details Modal -->
<div id="shift-details-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
    <h2 id="shift-details-title" class="text-lg font-bold mb-2">Shift Details</h2>
    <div id="shift-details-body" class="text-sm text-gray-700"></div>
    <div class="flex justify-end mt-4">
      <button id="shift-details-close" class="px-4 py-2 bg-blue-600 text-white rounded">Close</button>
    </div>
  </div>
</div>

<div id="shift-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
  <div class="bg-white p-6 rounded-lg w-96 max-w-full">
    <h2 id="modal-title" class="text-lg font-semibold mb-4"></h2>
    <div id="modal-content"></div>
  </div>
</div



<!-- ===================== Scripts ===================== -->
<script>
    window.appWorkers = @json($workers);
    window.positionColors = {
        'Ramp Agent': '#85A4BA',
        'Ramp Lead': '#495784',
        'Duty Manager': '#2F2557',
        'Supervisor': '#494268',
        'Staff': '#B4D9F3'
    };
</script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    window.currentUserRole = @json(Auth::check() ? Auth::user()->role : null);
</script>

<script src="{{ asset('js/shift-timeline.js') }}"></script>
<script>
    // --- UTILITY FUNCTIONS ---
function generateWorkerColor(workerId) {
    const defaultColors = [
        "#3B82F6","#EF4444","#10B981","#F59E0B","#8B5CF6",
        "#EC4899","#06B6D4","#84CC16","#F97316","#6366F1",
        "#14B8A6","#F43F5E","#0EA5E9","#A855F7","#84CC16",
        "#F472B6","#60A5FA","#34D399","#FBBF24","#A78BFA"
    ];

    // Find worker from global list
    const worker = window.appWorkers?.find(w => w.id === workerId);

    // If worker has a position and we have a color for it, use that
    if (worker && worker.position && window.positionColors[worker.position]) {
        return window.positionColors[worker.position];
    }

    // Otherwise fallback to rotating palette
    return defaultColors[workerId % defaultColors.length];
}

function getFlightColor(flightType) {
    return flightType === "arrival" ? "#10B981" : "#EF4444";
}

function getContrastColor(hexColor) {
    const r = parseInt(hexColor.substr(1,2),16);
    const g = parseInt(hexColor.substr(3,2),16);
    const b = parseInt(hexColor.substr(5,2),16);
    const brightness = (r*299 + g*587 + b*114)/1000;
    return brightness > 128 ? "#000000" : "#FFFFFF";
}

function darkenColor(hexColor, percent) {
    let r = parseInt(hexColor.substr(1,2),16);
    let g = parseInt(hexColor.substr(3,2),16);
    let b = parseInt(hexColor.substr(5,2),16);
    r = Math.max(0, Math.min(255, r*(1-percent/100)));
    g = Math.max(0, Math.min(255, g*(1-percent/100)));
    b = Math.max(0, Math.min(255, b*(1-percent/100)));
    return `#${Math.round(r).toString(16).padStart(2,"0")}${Math.round(g).toString(16).padStart(2,"0")}${Math.round(b).toString(16).padStart(2,"0")}`;
}

function toDateTimeLocal(value, isTimeOnly=false) {
    if(!value) return "";
    if(isTimeOnly) return value;
    if(value.includes("T")) return value.slice(0,16);
    if(value.includes(" ")) return value.replace(" ","T").slice(0,16);
    if(/^\d{1,2}:\d{2}$/.test(value)){
        const today = new Date().toISOString().split("T")[0];
        return `${today}T${value}`;
    }
    return "";
}

function parseTime(str) {
    if(str.includes("T")) str = str.split("T")[1];
    return str.split(":").map(Number);
}

// --- GLOBALS ---
let currentShifts = [];

// --- MAIN SCRIPT ---
document.addEventListener("DOMContentLoaded", function(){

    const workerColors = {};
    const dateInput = document.getElementById("shift-date");

    if(window.appWorkers){
        window.appWorkers.forEach(w => workerColors[w.id] = generateWorkerColor(w.id));
    }

    loadDataForDate(dateInput.value);

    // --- DATE NAVIGATION ---
    document.getElementById("prev-day").addEventListener("click", ()=>navigateDate(-1));
    document.getElementById("next-day").addEventListener("click", ()=>navigateDate(1));
    dateInput.addEventListener("change", ()=>loadDataForDate(dateInput.value));

    // --- MODAL HANDLING ---
    const modal = document.getElementById("shift-modal");
    function closeModal(){ if(modal) modal.classList.add("hidden"); }

    modal.addEventListener("click", e=>{
        if(e.target.id === "shift-modal") closeModal();
    });

    function navigateDate(days){
        const date = new Date(dateInput.value);
        date.setDate(date.getDate()+days);
        dateInput.value = date.toISOString().split("T")[0];
        loadDataForDate(dateInput.value);
    }

    function updateDateDisplay(date){
        const dateDisplay = document.querySelector(".text-sm.text-gray-500");
        if(dateDisplay){
            dateDisplay.textContent = new Date(date).toLocaleDateString("en-US",{
                month:"long", day:"numeric", year:"numeric"
            });
        }
    }

    function generateTimeSlots(container){
        container.innerHTML="";
        const slotWidth = 40;
        container.style.position = "relative";
        container.style.height = "50px";
        container.style.width = (96*slotWidth) + "px";
        for(let i=0;i<96;i++){
            const slot = document.createElement("div");
            slot.className="time-slot";
            slot.style.width=slotWidth+"px";
            slot.style.display="inline-block";
            slot.style.position="relative";
            container.appendChild(slot);
        }
    }

    // --- LOAD DATA ---
    function loadDataForDate(date){
        fetch(`./shifts/data?date=${date}`)
            .then(r=>r.json())
            .then(shifts=>{
                currentShifts = shifts;
                renderShifts(shifts);
                updateDateDisplay(date);
            }).catch(err=>console.error("Error loading shifts:",err));

        fetch(`./flights/data?date=${date}`)
            .then(r=>r.json())
            .then(flights=>renderFlights(flights))
            .catch(err=>console.error("Error loading flights:",err));
    }

    // --- RENDER SHIFTS ---
    function renderShifts(shifts){
        document.querySelectorAll(".shift-block").forEach(el=>el.remove());
        const shiftsByWorker={};
        shifts.forEach(s=>{ 
            if(!shiftsByWorker[s.worker_id]) shiftsByWorker[s.worker_id]=[]; 
            shiftsByWorker[s.worker_id].push(s); 
        });

        Object.keys(shiftsByWorker).forEach(workerId=>{
            const workerRow = document.querySelector(`.worker-row[data-worker-id="${workerId}"]`);
            if(!workerRow) return;
            const timeline = workerRow.querySelector(".worker-timeline");
            generateTimeSlots(timeline);

            shiftsByWorker[workerId].forEach(shift=>{
                const [sh,sm] = parseTime(shift.start_time);
                const [eh,em] = parseTime(shift.end_time);
                const startSlot = sh*4 + Math.floor(sm/15);
                let endSlot = eh*4 + Math.ceil(em/15);
                if(endSlot <= startSlot) endSlot = 96;
                createShiftBlock(shift,startSlot,endSlot,timeline,workerColors[workerId]);
            });
        });
    }

    // --- CREATE SHIFT BLOCK ---
   function createShiftBlock(shift, startSlot, endSlot, container, workerColor) {
    const slotWidth = 40;
    const totalWidth = (endSlot - startSlot) * slotWidth;
    const shiftBlock = document.createElement("div");

    const isBreak = Number(shift.shift_type) === 3;
    const backgroundColor = isBreak ? "#ff0707ff" : (workerColor || "#6B7280");
    const borderColor = isBreak ? darkenColor("#ff0707ff", 20) : darkenColor(workerColor || "#6B7280", 20);

    shiftBlock.className = "shift-block";
    shiftBlock.style.backgroundColor = backgroundColor;
    shiftBlock.style.color = getContrastColor(backgroundColor);
    shiftBlock.style.borderLeft = "4px solid " + borderColor;
    shiftBlock.style.width = totalWidth + "px";
    shiftBlock.style.position = "absolute";
    shiftBlock.style.left = startSlot * slotWidth + "px";
    shiftBlock.style.top = "2px";
    shiftBlock.style.cursor = "pointer";
    shiftBlock.textContent = `It's Time to Have your meal: ${shift.break_time_start}-${shift.break_time_end}`;

    // ✅ Role-based admin controls
    let adminControls = "";
    if (window.currentUserRole === "admin" || window.currentUserRole === "super_admin") {
        adminControls = `
            <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                <button class="edit-btn text-xs bg-blue-500 text-white px-2 py-1 rounded" data-id="${shift.id}">Edit</button>
                <button class="delete-btn text-xs bg-red-500 text-white px-2 py-1 rounded" data-id="${shift.id}">Delete</button>
            </div>
        `;
    }

    shiftBlock.innerHTML = `
        <div class="shift-content group">
            <div class="flex justify-between items-start">
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-semibold truncate">${isBreak ? "It's Time to Have your Meal" : (shift.flight?.flight_number || "")}</div>
                    <div class="text-xs opacity-90">${shift.start_time}-${shift.end_time}</div>
                </div>
                ${adminControls}
            </div>
            ${shift.notes ? `<div class="mt-2 text-xs italic line-clamp-2" title="${shift.notes}">💬 ${shift.notes}</div>` : ""}
        </div>
    `;

    // ✅ Break inside regular shift
    if (!isBreak && shift.break_time_start && shift.break_time_end) {
        const [shH, shM] = parseTime(shift.start_time);
        const shiftStartMin = shH * 60 + shM;
        const [bSH, bSM] = parseTime(shift.break_time_start);
        const [bEH, bEM] = parseTime(shift.break_time_end);
        const breakStartMin = bSH * 60 + bSM;
        const breakEndMin = bEH * 60 + bEM;
        const pxPerMin = slotWidth / 15;

        const breakBlock = document.createElement("div");
        breakBlock.className = "break-block";
        breakBlock.style.position = "absolute";
        breakBlock.style.left = ((breakStartMin - shiftStartMin) * pxPerMin) + "px";
        breakBlock.style.top = "0";
        breakBlock.style.width = ((breakEndMin - breakStartMin) * pxPerMin) + "px";
        breakBlock.style.height = "100%";
        breakBlock.style.backgroundColor = "#ff0707ff";
        breakBlock.style.borderRadius = "3px";
        breakBlock.style.display = "flex";
        breakBlock.style.alignItems = "center";
        breakBlock.style.justifyContent = "center";
        breakBlock.style.color = "#000";    
        breakBlock.style.fontSize = "14px";
        breakBlock.style.fontWeight = "600";
        breakBlock.style.opacity = "0.8";
        breakBlock.textContent = `It's Time to Have your meal: ${shift.break_time_start}-${shift.break_time_end}`;

        shiftBlock.appendChild(breakBlock);
    }

    container.appendChild(shiftBlock);
}


    // --- EVENT DELEGATION FOR EDIT/DELETE ---
    document.body.addEventListener("click", function(e){
        const editBtn = e.target.closest(".edit-btn");
        const deleteBtn = e.target.closest(".delete-btn");
        const shiftBlockEl = e.target.closest(".shift-block");

        if(editBtn){
            e.stopPropagation();
            const shiftId = editBtn.dataset.id;
            const shift = currentShifts.find(s=>s.id==shiftId);
            if(shift) openEditModal(shift);
        }

        if(deleteBtn){
            e.stopPropagation();
            const shiftId = deleteBtn.dataset.id;
            deleteShift(shiftId);
        }

        if(shiftBlockEl && !editBtn && !deleteBtn){
            const shiftId = shiftBlockEl.querySelector(".edit-btn")?.dataset.id;
            const shift = currentShifts.find(s=>s.id==shiftId);
            if(shift) showShiftDetails(shift);
        }
    });

    // --- EDIT MODAL ---
    function openEditModal(shift){
        const title = document.getElementById("modal-title");
        const content = document.getElementById("modal-content");
        if(!modal || !title || !content) return console.error("Modal elements missing");

        title.textContent = "Edit Shift";

        const startVal = toDateTimeLocal(shift.start_time);
        const endVal = toDateTimeLocal(shift.end_time);

        content.innerHTML = `
            <form id="edit-shift-form" class="space-y-4" novalidate>
                <input type="hidden" name="worker_id" value="${shift.worker_id ?? ''}">
                <input type="hidden" name="flight_id" value="${shift.flight_id ?? ''}">
                <input type="hidden" name="shift_type" value="${shift.shift_type ?? ''}">

                <div>
                    <label>Start</label>
                    <input type="datetime-local" name="start_time" value="${startVal}" class="w-full p-2 border rounded" required>
                </div>
                <div>
                    <label>End</label>
                    <input type="datetime-local" name="end_time" value="${endVal}" class="w-full p-2 border rounded" required>
                </div>
                <div>
                    <label>Notes</label>
                    <textarea name="notes" class="w-full p-2 border rounded">${shift.notes || ""}</textarea>
                </div>

                <div id="edit-form-errors" style="color: #b91c1c; font-size: .9rem;"></div>

                <div class="flex justify-end gap-2">
                    <button type="button" id="edit-cancel-btn" class="px-4 py-2 bg-gray-500 text-white rounded">Cancel</button>
                    <button type="submit" id="edit-submit-btn" class="px-4 py-2 bg-blue-600 text-white rounded">Update</button>
                </div>
            </form>
        `;

        modal.classList.remove("hidden");

        document.getElementById("edit-cancel-btn").onclick = closeModal;

        const form = document.getElementById("edit-shift-form");
        const submitBtn = document.getElementById("edit-submit-btn");
        const errorsDiv = document.getElementById("edit-form-errors");

        form.addEventListener("submit", async function handler(e){
            e.preventDefault();
            errorsDiv.textContent = "";

            const originalBtnHtml = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span style="opacity:.9">Updating…</span>';

            const formData = new FormData(form);
            formData.append("_method","PUT");
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            if(csrfMeta) formData.append("_token",csrfMeta.content);

            const url = `./admin/shifts/${shift.id}`;

            try{
                const resp = await fetch(url,{
                    method:"POST",
                    body:formData,
                    credentials:"same-origin",
                    headers: {"X-CSRF-TOKEN": csrfMeta ? csrfMeta.content : ""}
                });

                if(resp.status===422){
                    const data = await resp.json().catch(()=>null);
                    errorsDiv.textContent = data?.errors ? Object.values(data.errors)[0][0] : "Validation failed";
                    return;
                }

                if(!resp.ok){
                    const text = await resp.text().catch(()=>null);
                    errorsDiv.textContent = text || `Update failed (status ${resp.status})`;
                    return;
                }

                await resp.json().catch(()=>null);
                closeModal();
                loadDataForDate(dateInput.value);

            }catch(err){
                console.error("Network/update error:", err);
                errorsDiv.textContent = "Network error — please try again.";
            }finally{
                submitBtn.disabled=false;
                submitBtn.innerHTML=originalBtnHtml;
            }
        }, {once:true});
    }

    function deleteShift(id){
        if(!confirm("Are you sure you want to delete this shift?")) return;
        const csrfMeta=document.querySelector('meta[name="csrf-token"]');
        const formData = new FormData();
        formData.append("_method","DELETE");
        if(csrfMeta) formData.append("_token",csrfMeta.content);
        fetch(`./admin/shifts/${id}`,{method:"POST",body:formData,headers:{"X-CSRF-TOKEN":csrfMeta?csrfMeta.content:""}})
            .then(()=>loadDataForDate(dateInput.value)).catch(err=>console.error(err));
    }

    function showShiftDetails(shift) {
    const modal = document.getElementById("shift-details-modal");
    const title = document.getElementById("shift-details-title");
    const body = document.getElementById("shift-details-body");
    const closeBtn = document.getElementById("shift-details-close");

    if (!modal || !title || !body || !closeBtn) return console.error("Shift details modal elements missing");

    // Set modal content
    title.textContent = `Shift Details - ${shift.worker_name}`;
    body.innerHTML = `
        <p><strong>Worker:</strong> ${shift.worker_name}</p>
        <p><strong>Shift:</strong> ${shift.start_time} - ${shift.end_time}</p>
        <p><strong>Notes:</strong> ${shift.notes || "None"}</p>
    `;

    // Show modal
    modal.classList.remove("hidden");

    // Close modal on button click
    closeBtn.onclick = () => modal.classList.add("hidden");

    // Close modal when clicking outside the content
    modal.onclick = (e) => {
        if (e.target === modal) modal.classList.add("hidden");
    };
}

    // --- FLIGHTS ---
    function renderFlights(flights){
        document.querySelectorAll(".flight-item").forEach(el=>el.remove());
        const flightsBySlot={};
        flights.forEach(f=>{
            const [h,m]=f.scheduled_time.split(":").map(Number);
            const slot=h*4 + Math.floor(m/15);
            if(!flightsBySlot[slot]) flightsBySlot[slot]=[];
            flightsBySlot[slot].push(f);
        });
        const flightTimeline=document.getElementById("flight-timeline");
        Object.keys(flightsBySlot).forEach(slot=>{
            flightsBySlot[slot].forEach((f,idx)=>createFlightItem(f,parseInt(slot),idx,flightTimeline));
        });
    }

    function createFlightItem(flight,slot,rowIndex,container){
        const flightColor=getFlightColor(flight.type);
        const textColor=getContrastColor(flightColor);
        const left=slot*40;
        const top=8+rowIndex*52;
        const flightItem=document.createElement("div");
        flightItem.className="flight-item";
        flightItem.style.position="absolute";
        flightItem.style.left=left+"px";
        flightItem.style.top=top+"px";
        flightItem.draggable=true;
        flightItem.innerHTML=`
            <div class="px-2 py-1 rounded flex items-center gap-2 bg-white shadow" style="border-left:4px solid ${flightColor}; color:${textColor}">
                <span>${flight.flight_number}</span>
                <span class="text-xs opacity-70">${flight.scheduled_time}</span>
            </div>
        `;
        container.appendChild(flightItem);
        flightItem.addEventListener("click", e=>{ e.stopPropagation(); showFlightDetails(flight); });
    }

    function showFlightDetails(flight){
        alert(`Flight: ${flight.flight_number}\nType: ${flight.type}\nScheduled: ${flight.scheduled_time}`);
    }
});

</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
    const flightTiles = document.querySelectorAll(".flight-tile");
    const workerTimelines = document.querySelectorAll(".droppable");

    const storeDragUrl = window.storeDragUrl || "/admin/shifts/store-drag";
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    flightTiles.forEach((tile) => {
        tile.addEventListener("dragstart", (e) => {
            e.dataTransfer.setData("flight_id", tile.dataset.flightId);
            e.dataTransfer.setData("flight_number", tile.dataset.flightNumber);
            e.dataTransfer.setData("departure_time", tile.dataset.departureTime);
        });
    });

    workerTimelines.forEach((timeline) => {
        timeline.addEventListener("dragover", (e) => {
            e.preventDefault();
            timeline.classList.add("bg-green-50");
        });

        timeline.addEventListener("dragleave", () => {
            timeline.classList.remove("bg-green-50");
        });

        timeline.addEventListener("drop", async (e) => {
            e.preventDefault();
            timeline.classList.remove("bg-green-50");

            const workerId = timeline.dataset.workerId;
            const flightId = e.dataTransfer.getData("flight_id");
            const departureTimeStr = e.dataTransfer.getData("departure_time");

            const startTime = new Date(departureTimeStr);
            if (isNaN(startTime)) {
                alert("Invalid departure time: " + departureTimeStr);
                return;
            }

            const endTime = new Date(startTime.getTime() + 60 * 60 * 1000); // +1 hour

            const formData = new FormData();
            formData.append("worker_id", parseInt(workerId));
            formData.append("flight_id", parseInt(flightId));
            formData.append("shift_type", 1);
            formData.append("notes", "Auto-assigned via drag & drop");
            formData.append("_token", csrfToken);
            formData.append("start_time", startTime.toISOString());
            formData.append("end_time", endTime.toISOString());

            try {
                const response = await fetch(storeDragUrl, {
                    method: "POST",
                    body: formData,
                    headers: { "Accept": "application/json" }
                });

                const text = await response.text();
                try {
                    const result = JSON.parse(text);
                    if (result.success) {
                        alert(result.message || "Shift assigned successfully!");
                        location.reload();
                    } else {
                        alert("Failed: " + (result.message || "Unknown error"));
                    }
                } catch (err) {
                    console.error("Response not JSON:", text);
                    alert("Server returned non-JSON response.");
                }

            } catch (err) {
                console.error("Fetch error:", err);
                alert("Something went wrong while saving shift!");
            }
        });
    });
});

</script>
<script>
    window.storeDragUrl = "{{ route('admin.shifts.store.drag') }}"; // /admin/shifts/store-drag
</script>
<script src="{{ asset('js/drag-drop.js') }}"></script>
<script src="//unpkg.com/alpinejs" defer></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
    const options = {
        enableTime: true,
        time_24hr: true,
        dateFormat: "Y-m-d H:i", // Matches typical DB datetime format
    };

    flatpickr("#start_time", options);
    flatpickr("#end_time", options);
    flatpickr("#break_start_time", options);
    flatpickr("#break_end_time", options);
});
</script>

<script>
function updateClocks() {
    const now = new Date();

    // Toronto time
    const torontoTime = now.toLocaleString("en-US", {
        timeZone: "America/Toronto",
        hour12: false,
        hour: "2-digit",
        minute: "2-digit",
        second: "2-digit",
        day: "2-digit",
        month: "2-digit",
        year: "numeric"
    });

    // UTC time
    const utcTime = now.toLocaleString("en-GB", {
        timeZone: "UTC",
        hour12: false,
        hour: "2-digit",
        minute: "2-digit",
        second: "2-digit",
        day: "2-digit",
        month: "2-digit",
        year: "numeric"
    });

    document.getElementById("toronto-clock").textContent = torontoTime;
    document.getElementById("utc-clock").textContent = utcTime;
}

// Update every second
setInterval(updateClocks, 1000);
updateClocks();
</script>




<?php
// ===================== Helper: Generate Worker Color =====================
function generateWorkerColor($position) {
    $positionColors = [
    'ramp agent' => '#85A4BA',
    'ramp lead' => '#495784',
    'duty manager' => '#2F2557',
    'supervisor' => '#494268',
];

$positionKey = strtolower(trim($position));

if (isset($positionColors[$positionKey])) return $positionColors[$positionKey];

    
    if (isset($positionColors[$positionKey])) return $positionColors[$positionKey];
    
    foreach ($positionColors as $key => $color) {
        if ($key !== 'default' && strpos($positionKey, $key) !== false) {
            return $color;
        }
    }
    
    $hash = crc32($positionKey);
    $colors = [
        '#3B82F6', '#EF4444', '#10B981', '#F59E0B', '#8B5CF6',
        '#EC4899', '#06B6D4', '#84CC16', '#F97316', '#6366F1',
        '#14B8A6', '#F43F5E', '#0EA5E9', '#A855F7'
    ];
    return $colors[abs($hash) % count($colors)];
}
?>
@endsection
