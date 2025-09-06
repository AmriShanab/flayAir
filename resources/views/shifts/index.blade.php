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
                    <form method="POST" action="{{ route('logout') }}">
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

<script>
    window.appWorkers = @json($workers);
</script>
<script src="{{ asset('js/shift-timeline.js') }}"></script>



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