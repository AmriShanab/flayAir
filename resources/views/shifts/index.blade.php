@extends('layouts.app')

@section('content')
<head>
    <link rel="stylesheet" href="{{ asset('css/shift-timeline.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        /* Floating Flights Panel Styles */
        .floating-flights-panel {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            border-top: 3px solid #3b82f6;
            box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.15);
            z-index: 1000;
            transition: all 0.3s ease;
            max-height: 200px;
            overflow: hidden;
        }

        .floating-flights-panel.minimized {
            max-height: 40px;
        }

        .floating-flights-panel.hidden {
            transform: translateY(100%);
        }

        .flights-panel-header {
            background: #3b82f6;
            color: white;
            padding: 8px 16px;
            cursor: pointer;
            user-select: none;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .flights-panel-header h3 {
            margin: 0;
            font-weight: 600;
            font-size: 14px;
        }

        .flights-panel-controls {
            display: flex;
            gap: 8px;
        }

        .flights-panel-controls button {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            padding: 4px;
            border-radius: 4px;
            transition: background-color 0.2s;
        }

        .flights-panel-controls button:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .flights-panel-content {
            padding: 12px 16px;
            max-height: 150px;
            overflow-y: auto;
        }

        .flights-container {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            align-items: center;
        }

        .flight-tile {
            padding: 6px 12px;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            cursor: move;
            font-size: 12px;
            font-weight: 500;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .flight-tile:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
            border-color: #3b82f6;
        }

        .flight-tile.dragging {
            opacity: 0.7;
            transform: scale(0.95);
        }

        .no-flights {
            text-align: center;
            color: #6b7280;
            font-style: italic;
            font-size: 14px;
            padding: 20px;
        }

        /* Adjust main content to account for floating panel */
        .timeline-container {
            padding-bottom: 60px; /* Space for minimized panel */
        }

        .floating-flights-panel:not(.minimized) ~ .timeline-container {
            padding-bottom: 220px; /* Space for expanded panel */
        }

        /* Reopen Flights Panel Button */
        #reopen-flights-panel {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #3b82f6;
            color: white;
            border: none;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
            cursor: pointer;
            z-index: 999;
            transition: all 0.3s ease;
        }

        #reopen-flights-panel:hover {
            background: #2563eb;
            transform: scale(1.05);
            box-shadow: 0 6px 16px rgba(59, 130, 246, 0.5);
        }

        #reopen-flights-panel.hidden {
            display: none;
        }

        .flight-icon {
            width: 24px;
            height: 24px;
        }

        .zoro-logo {
            max-width: 250px;
            height: 55px;
        }

        .flight-tile {
    color: #fff;
    padding: 8px 12px;
    border-radius: 8px;
    margin-bottom: 6px;
    font-weight: 600;
    text-align: center;
    cursor: grab;
    transition: transform 0.2s, box-shadow 0.2s;
}

.flight-tile:hover {
    transform: scale(1.03);
    box-shadow: 0 2px 6px rgba(0,0,0,0.15);
}

/* Status Colors */
.flight-assigned {
    background-color: #28a745; /* green */
}

.flight-scheduled {
    background-color: #007bff; /* blue */
}

.flight-cancelled {
    background-color: #dc3545; /* red */
}

.flight-default {
    background-color: #6c757d; /* gray fallback */
}

    </style>
</head>

<div class="flex h-screen bg-gray-50">

    <!-- ===================== RIGHT: Shift Dashboard ===================== -->
    <div class="flex-1 flex flex-col overflow-hidden">
        
        <!-- ===== Header ===== -->
     <!-- ===== Header ===== -->
<div class="p-6 pb-0">
    <div class="flex justify-between items-center mb-6">
           <div class="flex items-center">
                <img src="{{ asset('images/Zorovel Logo - 7 - Edited.png') }}" 
                     alt="Zoroval Logo" 
                     class="zoro-logo">
            </div>

        <!-- Page Title -->
        {{-- <div>
            <h1 class="text-2xl font-bold text-gray-800">Shift Schedule</h1>
            <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($date)->format('F j, Y') }}</p>
        </div> --}}

        <!-- Right Section: Logo, Live Clocks, and Actions -->
        <div class="flex items-center gap-6">
            
            <!-- Zoroval Logo -->
         
            <!-- Live Clocks -->
            <div class="flex flex-row items-end gap-2">
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

                <!-- Dashboard Button -->
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
                        Back to Dashboard
                    </a>
                @endauth
            </div>
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
        </div>
    </div>
</div>

<!-- ===================== Floating Flights Panel ===================== -->
@auth
    @if(Auth::user()->role === 'admin' || Auth::user()->role === 'super_admin')
        <div class="floating-flights-panel" id="floating-flights-panel">
            <div class="flights-panel-header" id="flights-panel-header">
                <h3>Available Flights ({{ count($flights) }})</h3>
                <div class="flights-panel-controls">
                    <button id="flights-panel-minimize" title="Minimize">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <button id="flights-panel-close" title="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="flights-panel-content" id="flights-panel-content">
                <div class="flights-container" id="available-flights">
                    @if(count($flights) > 0)
                        @foreach($flights as $flight)
                            @php
                                $statusClass = match($flight->status) {
                                    'assigned' => 'flight-assigned',
                                    'scheduled' => 'flight-scheduled',
                                    'cancelled' => 'flight-cancelled',
                                    default => 'flight-default',
                                };
                            @endphp

                            <div class="flight-tile {{ $statusClass }}"
                                draggable="true"
                                data-flight-id="{{ $flight->id }}"
                                data-departure-time="{{ \Carbon\Carbon::parse($flight->scheduled_time)->toIso8601String() }}">
                                {{ $flight->flight_number }} ({{ \Carbon\Carbon::parse($flight->scheduled_time)->format('H:i') }})
                            </div>
                        @endforeach
                    @else
                        <div class="no-flights">No flights available for today</div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Floating Reopen Button -->
        <button id="reopen-flights-panel" class="hidden">
            <svg class="flight-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 2L11 13"></path>
                <path d="M22 2L15 22L11 13L2 9L22 2Z"></path>
            </svg>
        </button>
    @endif
@endauth


<!-- ===================== Shift Details Modal ===================== -->
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
</div>

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
<script>
    window.todayFlights = @json($flights);
</script>
<script src="{{ asset('js/shift-timeline.js') }}"></script>

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

// Floating Flights Panel Functionality
document.addEventListener('DOMContentLoaded', function() {
    const flightsPanel = document.getElementById('floating-flights-panel');
    const panelHeader = document.getElementById('flights-panel-header');
    const panelContent = document.getElementById('flights-panel-content');
    const minimizeBtn = document.getElementById('flights-panel-minimize');
    const closeBtn = document.getElementById('flights-panel-close');
    const reopenBtn = document.getElementById('reopen-flights-panel');

    if (flightsPanel) {
        // Minimize/Expand functionality
        minimizeBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            flightsPanel.classList.toggle('minimized');
            
            if (flightsPanel.classList.contains('minimized')) {
                minimizeBtn.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                    </svg>
                `;
                minimizeBtn.title = 'Expand';
            } else {
                minimizeBtn.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                `;
                minimizeBtn.title = 'Minimize';
            }
        });

        // Close functionality
        closeBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            flightsPanel.classList.add('hidden');
            reopenBtn.classList.remove('hidden');
        });

        // Reopen functionality
        reopenBtn.addEventListener('click', function() {
            flightsPanel.classList.remove('hidden');
            flightsPanel.classList.remove('minimized');
            reopenBtn.classList.add('hidden');
        });

        // Click header to toggle (only if not clicking buttons)
        panelHeader.addEventListener('click', function(e) {
            if (!e.target.closest('button')) {
                flightsPanel.classList.toggle('minimized');
                
                if (flightsPanel.classList.contains('minimized')) {
                    minimizeBtn.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                        </svg>
                    `;
                } else {
                    minimizeBtn.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    `;
                }
            }
        });

        // Add drag functionality to flight tiles
        const flightTiles = document.querySelectorAll('.flight-tile');
        flightTiles.forEach(tile => {
            tile.addEventListener('dragstart', function(e) {
                this.classList.add('dragging');
                e.dataTransfer.setData('text/plain', this.dataset.flightId);
            });
            
            tile.addEventListener('dragend', function() {
                this.classList.remove('dragging');
            });
        });
    }
});
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