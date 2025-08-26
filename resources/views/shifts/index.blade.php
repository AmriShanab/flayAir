@extends('layouts.app')

@section('content')
<head>
    <link rel="stylesheet" href="{{ asset('css/shift-timeline.css') }}">
</head>

<div class="flex h-screen bg-gray-50">
    <!-- LEFT: Worker List -->
    <div class="w-64 bg-white border-r border-gray-200 shadow-sm p-4 overflow-y-auto">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-800">Staff Members</h2>
            <button class="p-2 rounded-full hover:bg-gray-100 text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
        
        <div class="relative mb-4">
            <input type="text" placeholder="Search workers..." class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute left-3 top-2.5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
            </svg>
        </div>
        
        <ul class="space-y-2" id="workers-list">
            @foreach($workers as $worker)
                @php
                    $workerColor = generateWorkerColor($worker->id);
                @endphp
                <li class="flex items-center p-3 rounded-lg hover:bg-gray-50 cursor-pointer transition worker-item" 
                    data-worker-id="{{ $worker->id }}"
                    style="border-left: 4px solid {{ $workerColor }};">
                    <div class="h-10 w-10 rounded-full flex items-center justify-center text-white font-semibold mr-3"
                         style="background-color: {{ $workerColor }};">
                        {{ substr($worker->first_name, 0, 1) }}
                    </div>
                    <div>
                        <p class="font-medium text-gray-800">{{ $worker->full_name }}</p>
                        <p class="text-xs text-gray-500">
                            {{ $worker->shifts->count() > 0 ? 'Scheduled' : 'Available' }}
                        </p>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>

    <!-- RIGHT: Shift Dashboard -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Header -->
        <div class="p-6 pb-0">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Shift Schedule</h1>
                    <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($date)->format('F j, Y') }}</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="flex items-center border rounded-lg overflow-hidden">
                        <button class="p-2 hover:bg-gray-100" id="prev-day">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <input type="date" id="shift-date" class="border-0 px-4 py-2 focus:ring-2 focus:ring-blue-500" value="{{ $date }}">
                        <button class="p-2 hover:bg-gray-100" id="next-day">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                    <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2" id="new-shift-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        New Shift
                    </button>
                    <form method="POST" action="#">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h6a1 1 0 110 2H5v10h5a1 1 0 110 2H4a1 1 0 01-1-1V4zm11.293 4.293a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 01-1.414-1.414L15.586 13H9a1 1 0 110-2h6.586l-1.293-1.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Schedule Container -->
        <div class="flex-1 overflow-auto px-6 pb-6 timeline-container">
            <!-- Timeline Header -->
            <div class="timeline-header">
                <div class="worker-name-cell font-semibold text-gray-700">
                    Worker / Time
                </div>
                <div class="flex">
                    @for($slot = 0; $slot < 96; $slot++)
                        @php
                            $hour = floor($slot / 4);
                            $minute = ($slot % 4) * 15;
                            $isHourMark = $minute === 0;
                        @endphp
                        <div class="timeline-slot {{ $isHourMark ? 'hour' : 'quarter' }}">
                            @if($isHourMark)
                                {{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}:00
                            @else
                                {{ $minute }}
                            @endif
                        </div>
                    @endfor
                </div>
            </div>

            <!-- Worker Rows with Shifts -->
            <div class="workers-container">
                @foreach($workers as $worker)
                    @php
                        $workerColor = generateWorkerColor($worker->id);
                    @endphp
                    <div class="worker-row" data-worker-id="{{ $worker->id }}">
                        <!-- Worker name cell (sticky) -->
                        <div class="worker-name-cell">
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-full flex items-center justify-center text-white font-semibold mr-3"
                                     style="background-color: {{ $workerColor }};">
                                    {{ substr($worker->first_name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">{{ $worker->first_name }}</p>
                                    <p class="text-xs text-gray-500">{{ $worker->position }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Timeline for this worker -->
                        <div class="worker-timeline">
                            <div class="time-slots-container">
                                @for($slot = 0; $slot < 96; $slot++)
                                    @php
                                        $hour = floor($slot / 4);
                                        $minute = ($slot % 4) * 15;
                                        $timeString = str_pad($hour, 2, '0', STR_PAD_LEFT) . ':' . str_pad($minute, 2, '0', STR_PAD_LEFT);
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
                            
                            <!-- Shifts will be rendered here by JavaScript -->
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Flight Timeline Section -->
            <div class="flight-timeline-container">
                <div class="flight-timeline-header">
                    <div class="flight-timeline-label">
                        Flight Schedule
                    </div>
                    <div class="flex">
                        @for($slot = 0; $slot < 96; $slot++)
                            @php
                                $hour = floor($slot / 4);
                                $minute = ($slot % 4) * 15;
                                $isHourMark = $minute === 0;
                            @endphp
                            <div class="timeline-slot {{ $isHourMark ? 'hour' : 'quarter' }}">
                                @if($isHourMark)
                                    {{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}:00
                                @else
                                    {{ $minute }}
                                @endif
                            </div>
                        @endfor
                    </div>
                </div>
                
                <div class="flight-timeline" id="flight-timeline">
                    <!-- Flight items will be rendered here by JavaScript -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Shift Details Modal -->
<div id="shift-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg p-6 w-96 max-h-80vh overflow-y-auto">
        <h3 class="text-lg font-semibold mb-4" id="modal-title">Shift Details</h3>
        <div id="modal-content">
            <!-- Content will be loaded dynamically -->
        </div>
        <div class="mt-6 flex justify-end gap-3">
            <button class="px-4 py-2 text-gray-600 hover:text-gray-800" onclick="closeModal()">Close</button>
            <button class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 hidden" id="delete-shift-btn">Delete</button>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
// Generate consistent colors for workers
function generateWorkerColor(workerId) {
    const colors = [
        '#3B82F6', '#EF4444', '#10B981', '#F59E0B', '#8B5CF6',
        '#EC4899', '#06B6D4', '#84CC16', '#F97316', '#6366F1',
        '#14B8A6', '#F43F5E', '#0EA5E9', '#A855F7', '#84CC16',
        '#F472B6', '#60A5FA', '#34D399', '#FBBF24', '#A78BFA'
    ];
    return colors[workerId % colors.length];
}

// Flight color mapping
function getFlightColor(flightType) {
    return flightType === 'arrival' ? '#10B981' : '#EF4444';
}

// Utility functions
function getContrastColor(hexColor) {
    const r = parseInt(hexColor.substr(1, 2), 16);
    const g = parseInt(hexColor.substr(3, 2), 16);
    const b = parseInt(hexColor.substr(5, 2), 16);
    const brightness = (r * 299 + g * 587 + b * 114) / 1000;
    return brightness > 128 ? '#000000' : '#FFFFFF';
}

function darkenColor(hexColor, percent) {
    let r = parseInt(hexColor.substr(1, 2), 16);
    let g = parseInt(hexColor.substr(3, 2), 16);
    let b = parseInt(hexColor.substr(5, 2), 16);
    
    r = Math.max(0, Math.min(255, r * (1 - percent / 100)));
    g = Math.max(0, Math.min(255, g * (1 - percent / 100)));
    b = Math.max(0, Math.min(255, b * (1 - percent / 100)));
    
    return `#${Math.round(r).toString(16).padStart(2, '0')}${Math.round(g).toString(16).padStart(2, '0')}${Math.round(b).toString(16).padStart(2, '0')}`;
}

function calculateDuration(start, end) {
    const startTime = new Date(`2000-01-01T${start}`);
    const endTime = new Date(`2000-01-01T${end}`);
    if (endTime < startTime) endTime.setDate(endTime.getDate() + 1);
    
    const diffMs = endTime - startTime;
    const hours = Math.floor(diffMs / (1000 * 60 * 60));
    const minutes = Math.floor((diffMs % (1000 * 60 * 60)) / (1000 * 60));
    
    return `${hours}h ${minutes}m`;
}

function closeModal() {
    document.getElementById('shift-modal').classList.add('hidden');
}

// Worker color mapping
const workerColors = {};

document.addEventListener('DOMContentLoaded', function () {
    // Initialize worker colors
    @foreach($workers as $worker)
        workerColors[{{ $worker->id }}] = generateWorkerColor({{ $worker->id }});
    @endforeach

    const dateInput = document.getElementById('shift-date');
    
    // Load shifts and flights for current date
    loadDataForDate(dateInput.value);
    
    // Date navigation
    document.getElementById('prev-day').addEventListener('click', () => {
        navigateDate(-1);
    });
    
    document.getElementById('next-day').addEventListener('click', () => {
        navigateDate(1);
    });
    
    dateInput.addEventListener('change', () => {
        loadDataForDate(dateInput.value);
    });
    
    // Close modal when clicking outside
    document.getElementById('shift-modal').addEventListener('click', (e) => {
        if (e.target.id === 'shift-modal') {
            closeModal();
        }
    });
    
    function navigateDate(days) {
        const date = new Date(dateInput.value);
        date.setDate(date.getDate() + days);
        dateInput.value = date.toISOString().split('T')[0];
        loadDataForDate(dateInput.value);
    }
    
    function loadDataForDate(date) {
        // Load shifts
        fetch(`/shifts/data?date=${date}`)
            .then(response => response.json())
            .then(shifts => {
                renderShifts(shifts);
                updateDateDisplay(date);
            })
            .catch(error => {
                console.error('Error loading shifts:', error);
            });
        
        // Load flights
        fetch(`/flights/data?date=${date}`)
            .then(response => response.json())
            .then(flights => {
                renderFlights(flights);
            })
            .catch(error => {
                console.error('Error loading flights:', error);
            });
    }
    
    function updateDateDisplay(date) {
        const dateDisplay = document.querySelector('.text-sm.text-gray-500');
        if (dateDisplay) {
            dateDisplay.textContent = new Date(date).toLocaleDateString('en-US', {
                month: 'long',
                day: 'numeric',
                year: 'numeric'
            });
        }
    }
    
    function renderShifts(shifts) {
        // Clear existing shifts
        document.querySelectorAll('.shift-block').forEach(el => el.remove());
        
        // Group shifts by worker
        const shiftsByWorker = {};
        shifts.forEach(shift => {
            if (!shiftsByWorker[shift.worker_id]) {
                shiftsByWorker[shift.worker_id] = [];
            }
            shiftsByWorker[shift.worker_id].push(shift);
        });
        
        // Render shifts for each worker
        Object.keys(shiftsByWorker).forEach(workerId => {
            const workerShifts = shiftsByWorker[workerId];
            const workerRow = document.querySelector(`.worker-row[data-worker-id="${workerId}"]`);
            
            if (!workerRow) return;
            
            const timelineContainer = workerRow.querySelector('.worker-timeline');
            
            workerShifts.forEach(shift => {
                const startTime = shift.start_time.split(':');
                const endTime = shift.end_time.split(':');
                
                const startHour = parseInt(startTime[0]);
                const startMinute = parseInt(startTime[1]);
                const endHour = parseInt(endTime[0]);
                const endMinute = parseInt(endTime[1]);
                
                // Calculate start and end slot indices
                const startSlot = (startHour * 4) + Math.floor(startMinute / 15);
                let endSlot = (endHour * 4) + Math.ceil(endMinute / 15);
                
                // Handle overnight shifts
                if (endSlot < startSlot) {
                    endSlot = 96; // 24 hours in 15-minute slots
                }
                
                const totalSlots = endSlot - startSlot;
                
                if (totalSlots > 0) {
                    // Create a single shift block that spans multiple slots
                    createMultiSlotShift(shift, startSlot, endSlot, timelineContainer);
                }
            });
        });
    }
    
    function renderFlights(flights) {
        // Clear existing flight items
        document.querySelectorAll('.flight-item').forEach(el => el.remove());
        
        // Group flights by time slot
        const flightsBySlot = {};
        flights.forEach(flight => {
            const scheduledTime = flight.scheduled_time;
            const timeParts = scheduledTime.split(':');
            const hour = parseInt(timeParts[0]);
            const minute = parseInt(timeParts[1]);
            
            // Calculate slot index
            const slot = (hour * 4) + Math.floor(minute / 15);
            
            if (!flightsBySlot[slot]) {
                flightsBySlot[slot] = [];
            }
            flightsBySlot[slot].push(flight);
        });
        
        // Render flights
        const flightTimeline = document.getElementById('flight-timeline');
        
        Object.keys(flightsBySlot).forEach(slot => {
            const slotFlights = flightsBySlot[slot];
            
            // Create rows for flights at the same time
            slotFlights.forEach((flight, index) => {
                createFlightItem(flight, parseInt(slot), index, flightTimeline);
            });
        });
    }
    
    function createFlightItem(flight, slot, rowIndex, container) {
        const flightColor = getFlightColor(flight.type);
        const textColor = getContrastColor(flightColor);
        
        // Calculate position
        const leftPosition = slot * 40;
        const topPosition = 8 + (rowIndex * 52); // 44px height + 8px margin
        
        // Create flight item
        const flightItem = document.createElement('div');
        flightItem.className = 'flight-item';
        flightItem.style.left = leftPosition + 'px';
        flightItem.style.top = topPosition + 'px';
        flightItem.style.borderLeftColor = flightColor;
        
        flightItem.innerHTML = `
            <div class="flight-item-content">
                <div class="flight-number">${flight.flight_number}</div>
                <div class="flight-time">${flight.scheduled_time}</div>
            </div>
            <div class="flight-tooltip">
                <div class="font-semibold">${flight.flight_number}</div>
                <div>${flight.type === 'arrival' ? 'From' : 'To'}: ${flight.type === 'arrival' ? flight.origin : flight.destination}</div>
                <div>Scheduled: ${flight.scheduled_time}</div>
                <div>Status: ${flight.status.charAt(0).toUpperCase() + flight.status.slice(1)}</div>
                ${flight.notes ? `<div class="mt-2 text-xs">${flight.notes}</div>` : ''}
            </div>
        `;
        
        // Add click handler
        flightItem.addEventListener('click', (e) => {
            e.stopPropagation();
            showFlightDetails(flight);
        });
        
        container.appendChild(flightItem);
    }
    
    function createMultiSlotShift(shift, startSlot, endSlot, container) {
        const timeSlots = container.querySelectorAll('.time-slot');
        const firstSlot = timeSlots[startSlot];
        
        if (!firstSlot) return;
        
        const workerColor = workerColors[shift.worker_id] || '#6B7280';
        const textColor = getContrastColor(workerColor);
        
        // Create the shift block
        const shiftBlock = document.createElement('div');
        shiftBlock.className = 'shift-block';
        shiftBlock.style.backgroundColor = workerColor;
        shiftBlock.style.color = textColor;
        shiftBlock.style.borderLeft = '4px solid ' + darkenColor(workerColor, 20);
        
        // Calculate width based on number of slots
        const slotWidth = 40; // px per slot
        const totalWidth = (endSlot - startSlot) * slotWidth;
        shiftBlock.style.width = totalWidth + 'px';
        
        // Add shift content
        shiftBlock.innerHTML = `
            <div class="shift-content">
                <div class="flex justify-between items-start">
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-semibold truncate">${shift.worker_name}</div>
                        <div class="text-xs opacity-90">${shift.start_time}-${shift.end_time}</div>
                    </div>
                    <div class="text-2xl opacity-0 group-hover:opacity-50 transition-opacity">→</div>
                </div>
                ${shift.notes ? `
                <div class="mt-2 text-xs italic line-clamp-2" title="${shift.notes}">
                    💬 ${shift.notes}
                </div>
                ` : ''}
            </div>
        `;
        
        // Add click handler
        shiftBlock.addEventListener('click', (e) => {
            e.stopPropagation();
            showShiftDetails(shift);
        });
        
        // Position the shift block
        shiftBlock.style.position = 'absolute';
        shiftBlock.style.top = '2px';
        shiftBlock.style.left = (startSlot * 40) + 'px';
        
        // Append to container
        container.appendChild(shiftBlock);
    }
    
    function showShiftDetails(shift) {
        const modal = document.getElementById('shift-modal');
        const title = document.getElementById('modal-title');
        const content = document.getElementById('modal-content');
        
        title.textContent = 'Shift Details';
        content.innerHTML = `
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-white" 
                         style="background-color: ${workerColors[shift.worker_id]}">
                        ${shift.worker_name.charAt(0)}
                    </div>
                    <div>
                        <h4 class="font-semibold">${shift.worker_name}</h4>
                        <p class="text-sm text-gray-600">${shift.start_time} - ${shift.end_time}</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Shift Type</label>
                        <p class="text-sm text-gray-800 capitalize bg-gray-50 px-3 py-2 rounded">${shift.shift_type}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Duration</label>
                        <p class="text-sm text-gray-800 bg-gray-50 px-3 py-2 rounded">${calculateDuration(shift.start_time, shift.end_time)}</p>
                    </div>
                </div>
                
                ${shift.notes ? `
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-800 leading-relaxed">${shift.notes}</p>
                    </div>
                </div>
                ` : ''}
            </div>
        `;
        
        modal.classList.remove('hidden');
    }
    
    function showFlightDetails(flight) {
        const modal = document.getElementById('shift-modal');
        const title = document.getElementById('modal-title');
        const content = document.getElementById('modal-content');
        
        const flightColor = getFlightColor(flight.type);
        const textColor = getContrastColor(flightColor);
        
        title.textContent = 'Flight Details';
        content.innerHTML = `
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-white font-semibold" 
                         style="background-color: ${flightColor}">
                        ${flight.type === 'arrival' ? 'A' : 'D'}
                    </div>
                    <div>
                        <h4 class="font-semibold">${flight.flight_number}</h4>
                        <p class="text-sm text-gray-600">${flight.airline} • ${flight.type}</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Scheduled Time</label>
                        <p class="text-sm text-gray-800 bg-gray-50 px-3 py-2 rounded">${flight.scheduled_time}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <p class="text-sm text-gray-800 bg-gray-50 px-3 py-2 rounded capitalize">${flight.status}</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">${flight.type === 'arrival' ? 'Origin' : 'Destination'}</label>
                        <p class="text-sm text-gray-800 bg-gray-50 px-3 py-2 rounded">${flight.type === 'arrival' ? flight.origin : flight.destination}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">${flight.type === 'arrival' ? 'Destination' : 'Origin'}</label>
                        <p class="text-sm text-gray-800 bg-gray-50 px-3 py-2 rounded">${flight.type === 'arrival' ? flight.destination : flight.origin}</p>
                    </div>
                </div>
                
                ${flight.notes ? `
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-800 leading-relaxed">${flight.notes}</p>
                    </div>
                </div>
                ` : ''}
            </div>
        `;
        
        modal.classList.remove('hidden');
    }
});

</script>

<?php
// PHP function to generate consistent colors for workers
function generateWorkerColor($workerId) {
    $colors = [
        '#3B82F6', '#EF4444', '#10B981', '#F59E0B', '#8B5CF6',
        '#EC4899', '#06B6D4', '#84CC16', '#F97316', '#6366F1',
        '#14B8A6', '#F43F5E', '#0EA5E9', '#A855F7', '#84CC16',
        '#F472B6', '#60A5FA', '#34D399', '#FBBF24', '#A78BFA'
    ];
    return $colors[$workerId % count($colors)];
}
?>
@endsection