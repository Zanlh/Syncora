@extends('layouts/contentNavbarLayout')

@section('title', 'Meeting')

@section('content')
    <!-- Content -->
    <div class="row">
        <!-- First Column: Meeting Overview & Today's Meetings -->
        <div class="col-lg-6 col-md-12">
            <!-- Meeting Overview Card -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title">Meetings Overview</h5>
                </div>
                <div class="card-body">
                    <div class="row d-flex justify-content-between">
                        <!-- Scheduled Meetings -->
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-header d-flex align-items-center">
                                    <i class="bx bx-calendar-check bx-lg text-primary me-2"></i>
                                    <h6 class="card-title m-0">Scheduled Meetings</h6>
                                </div>
                                <div class="card-body text-center">
                                    <h4 class="card-text">{{ $scheduledMeetings->count() }}</h4>
                                </div>
                            </div>
                        </div>

                        <!-- Reschedule Meetings -->
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-header d-flex align-items-center">
                                    <i class="bx bx-refresh bx-lg text-warning me-2"></i>
                                    <h6 class="card-title m-0">Reschedule Meetings</h6>
                                </div>
                                <div class="card-body text-center">
                                    <h4 class="card-text">0</h4>
                                </div>
                            </div>
                        </div>

                        <!-- Canceled Meetings -->
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-header d-flex align-items-center">
                                    <i class="bx bx-x-circle bx-lg text-danger me-2"></i>
                                    <h6 class="card-title m-0">Canceled Meetings</h6>
                                </div>
                                <div class="card-body text-center">
                                    <h4 class="card-text">{{ $canceledMeetings->count() }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Today's Meetings Card -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title">Upcoming Meetings</h5>
                </div>
                <div class="card-body">
                    @foreach ($upcomingMeetings as $meeting)
                        <div class="card mb-3">
                            <div class="card-body">
                                <h6 class="card-title">{{ $meeting->title }}</h6>

                                <p class="text-muted">
                                    {{ \Carbon\Carbon::parse($meeting->start_date . ' ' . $meeting->start_time)->format('M d, Y g:i A') }}
                                    -
                                    {{ \Carbon\Carbon::parse($meeting->end_date . ' ' . $meeting->end_time)->format('M d, Y g:i A') }}
                                </p>

                                <p class="text-muted">{{ $meeting->description }}</p>

                                @if ($meeting->meeting_link)
                                    <a href="{{ $meeting->meeting_link }}" target="_blank" class="btn btn-primary">
                                        Join Meeting
                                    </a>
                                @else
                                    <button class="btn btn-secondary" disabled>No meeting link available</button>
                                @endif

                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

        <!-- Second Column: Create a Meeting (With Small Calendar) -->
        <div class="col-lg-6 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Create a Meeting</h5>
                </div>
                <div class="card-body">
                    <!-- Small Calendar Display -->
                    <div id="calendar" class="mb-3"></div>

                    <!-- Create Meeting Button -->
                    <a href="{{ route('agent.meetings.create') }}" class="btn btn-primary me-2">
                        <i class="bx bx-plus me-1"></i> Schedulee Meeting
                    </a>
                    <a href="#" class="btn btn-info me-2">
                        <i class="bx bx-plus me-1"></i> Start Meeting
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- End Content -->
@endsection

@push('script-page')
    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">

    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>

    <!-- Your custom script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {

                initialView: 'dayGridMonth', // Initial view on load
                selectable: true, // Allows selecting a date
                dateClick: function(info) {
                    alert('Selected date: ' + info.dateStr); // Simple alert for clicked date
                },

                // Event handling: adding events dynamically or from server
                events: [{
                        title: 'Meeting 1',
                        start: '2025-03-10T10:00:00',
                        end: '2025-03-10T12:00:00',
                        description: 'Important business meeting',
                        backgroundColor: '#ff7f7f', // Event color
                        textColor: '#fff', // Text color within the event box
                    },
                    {
                        title: 'Conference',
                        start: '2025-03-12T09:00:00',
                        end: '2025-03-12T17:00:00',
                        backgroundColor: '#4caf50',
                        textColor: '#fff',
                    }
                ],

                // Design and customization options
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                buttonText: {
                    today: 'Today',
                    month: 'Month',
                    week: 'Week',
                    day: 'Day',
                },

                // Customization of events' appearance
                eventColor: '#378006', // Color of events
                eventTextColor: '#ffffff', // Color of text within events
            });
            calendar.render();
        });
    </script>
@endpush
