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
    // Initialize worker colors using global appWorkers from Blade
    if (window.appWorkers) {
        window.appWorkers.forEach(worker => {
            workerColors[worker.id] = generateWorkerColor(worker.id);
        });
    }

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
                        <div class="text-sm font-semibold">${shift.flight ? shift.flight.flight_number : ''}</div>
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
