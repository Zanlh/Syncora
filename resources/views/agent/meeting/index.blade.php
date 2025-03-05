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
                                    <h4 class="card-text">15</h4>
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
                                    <h4 class="card-text">5</h4>
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
                                    <h4 class="card-text">2</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Today's Meetings Card -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title">Today's Meetings</h5>
                </div>
                <div class="card-body">
                    <!-- Meeting Detail Card 1 -->
                    <div class="card mb-3">
                        <div class="card-body">
                            <h6 class="card-title">Meeting 1</h6>
                            <p class="text-muted">10:00 AM - 11:00 AM</p>
                            <p class="text-muted">Team discussion about new feature development.</p>
                        </div>
                    </div>
                    <!-- Meeting Detail Card 2 -->
                    <div class="card mb-3">
                        <div class="card-body">
                            <h6 class="card-title">Meeting 2</h6>
                            <p class="text-muted">1:00 PM - 2:00 PM</p>
                            <p class="text-muted">Client review and feedback session.</p>
                        </div>
                    </div>
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
                        <i class="bx bx-plus
                        me-1"></i> Create Meeting
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
