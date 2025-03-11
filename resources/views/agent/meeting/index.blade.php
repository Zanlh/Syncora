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
                        <i class="bx bx-plus me-1"></i> Schedule a Meeting
                    </a>
                    <!-- Start Meeting Button -->
                    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#attendeesModal">
                        <i class="bx bx-plus me-1"></i> Start a Meeting
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal for Attendees -->
    <div class="modal fade" id="attendeesModal" tabindex="-1" aria-labelledby="attendeesModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="attendeesModalLabel">Enter Meeting Title and Attendees</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="attendeesForm">
                        <!-- Input for Meeting Title -->
                        <div class="mb-3">
                            <label for="meeting_title" class="form-label">Meeting Title</label>
                            <input type="text" class="form-control" id="meeting_title" name="meeting_title"
                                placeholder="Enter the meeting title" required>
                        </div>

                        <!-- Input for Attendees' Emails -->
                        <div class="mb-3">
                            <label for="attendees" class="form-label">Attendees Emails</label>
                            <input type="text" class="form-control" id="attendees" name="attendees"
                                placeholder="Enter emails, separated by commas" required>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary">Start Meeting</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Date Clicked Details -->
    <div class="modal fade" id="dateDetailsModal" tabindex="-1" aria-labelledby="dateDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="dateDetailsModalLabel">Meeting Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Dynamic content will be injected here -->
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
                    var selectedDate = info.dateStr;
                    var meetingDetails = '';

                    // Filter events for the selected date
                    var eventsOnDate = calendar.getEvents().filter(function(event) {
                        // Compare only the date part of event's start date with the selected date
                        return event.start.toLocaleDateString() === new Date(selectedDate)
                            .toLocaleDateString();
                    });
                    console.log(eventsOnDate);
                    if (eventsOnDate.length > 0) {
                        eventsOnDate.forEach(function(event) {
                            meetingDetails += `
                        <strong>Title:</strong> ${event.title}<br>
                        <strong>Start Time:</strong> ${event.start.toLocaleString()}<br>
                        <strong>End Time:</strong> ${event.end ? event.end.toLocaleString() : 'N/A'}<br>
                        <strong>Description:</strong> ${event.extendedProps.description || 'No description'}<br><br>
                    `;
                        });
                    } else {
                        meetingDetails = 'No meetings scheduled for this date.';
                    }

                    // Open the modal and display the meeting details
                    $('#dateDetailsModal .modal-body').html(meetingDetails);
                    $('#dateDetailsModal').modal('show'); // Use Bootstrap modal
                },
                // Populate events from PHP passed data
                events: {!! json_encode(
                    $scheduledMeetings->map(function ($meeting) {
                        return [
                            'title' => $meeting->title,
                            'start' => $meeting->start_date . 'T' . $meeting->start_time,
                            'end' => $meeting->end_date . 'T' . $meeting->end_time,
                            'description' => $meeting->description,
                            'backgroundColor' => '#4caf50', // Green for scheduled meetings
                            'textColor' => '#fff',
                        ];
                    }),
                ) !!},
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
