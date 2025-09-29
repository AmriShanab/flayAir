<div class="mt-8">
    <h2 class="text-xl font-bold text-gray-800 mb-4">Available Flights</h2>
    
    <!-- Container: use flex-wrap to avoid horizontal scroll -->
    <div class="flex flex-wrap gap-4 max-w-full overflow-x-hidden" id="available-flights">
        @foreach($flights as $flight)
            <div class="flight-tile bg-white rounded-lg shadow p-4 cursor-move border border-gray-200 flex-shrink-0 w-full sm:w-[48%] lg:w-[31%]"
                 draggable="true"
                 data-flight-id="{{ $flight->id }}"
                 data-flight-number="{{ $flight->flight_number }}"
                 data-departure-time="{{ $flight->departure_time }}"
                 data-arrival-time="{{ $flight->arrival_time }}"
                 data-origin="{{ $flight->origin }}"
                 data-destination="{{ $flight->destination }}">
                 
                <div class="flex justify-between items-start mb-2">
                    <h3 class="font-bold text-lg">{{ $flight->flight_number }}</h3>
                    <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                        {{ $flight->status }}
                    </span>
                </div>
                
                <div class="flex justify-between mb-2">
                    <div>
                        <p class="text-sm text-gray-500">Scheduled Time</p>
                        <p class="font-semibold">{{ \Carbon\Carbon::parse($flight->scheduled_time)->format('H:i') }}</p>
                        <p class="text-xs">{{ $flight->origin }}</p>
                    </div>
                </div>
                
                <div class="text-xs text-gray-500 mt-2">
                    Drag to assign to staff member
                </div>
            </div>
        @endforeach
    </div>
</div>
