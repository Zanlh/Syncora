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
                                    <!-- Use route helper to generate the meeting room link dynamically -->
                                    <a href="{{ route('agent.meeting.room', ['room' => $meeting->meeting_room]) }}"
                                        target="_blank" class="btn btn-primary">
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

        <!-- Second Column: Create a Meeting (With Date Picker and Upcoming Meetings) -->
        <div class="col-lg-6 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Create a Meeting</h5>
                </div>
                <div class="card-body">
                    <!-- Current Time Display -->
                    <div class="mb-3 text-center">
                        <div id="currentTime" class="display-1 mb-2" style="font-weight: bold;"></div>
                        <div id="currentDay" class="h5 text-muted"></div>
                    </div>

                    <!-- Upcoming Meetings Section -->
                    <h6>Upcoming Meetings</h6>
                    <ul class="list-group mb-3">
                        @foreach ($upcomingMeetings as $meeting)
                            <li class="list-group-item">
                                <strong>{{ $meeting->title }}</strong>
                                <br>
                                <small>{{ \Carbon\Carbon::parse($meeting->start_date . ' ' . $meeting->start_time)->format('M d, Y g:i A') }}</small>
                            </li>
                        @endforeach
                    </ul>

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

    <!-- Modal for Instant Meeting -->
    <div class="modal fade" id="attendeesModal" tabindex="-1" aria-labelledby="attendeesModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="attendeesModalLabel">Enter Meeting Title and Attendees</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="attendeesForm" action="{{ route('agent.instant.meetings.create') }}" method="POST">
                        @csrf
                        <!-- Input for Meeting Title -->
                        <div class="mb-3">
                            <label for="meeting_title" class="form-label">Meeting Title</label>
                            <input type="text" class="form-control" id="meeting_title" name="meeting_title"
                                placeholder="Enter the meeting title" required>
                        </div>

                        <!-- Input for Attendees' Emails -->
                        <div class="mb-3">
                            <label for="attendees" class="form-label">
                                <i class="bx bx-envelope"></i> Attendees (Emails)
                            </label>
                            <select id="attendees" name="attendees[]" class="form-control rounded-3" multiple="multiple"
                                required>
                                @foreach (old('attendees', []) as $email)
                                    <option value="{{ $email }}" selected>{{ $email }}</option>
                                @endforeach
                            </select>
                        </div>
                        <input type="hidden" class="form-control" id="meeting_type" name="meeting_type" value="instant">
                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary">Start Meeting</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script-page')
    <script>
        $(document).ready(function() {
            // Prevent conflicts with other libraries
            $.noConflict();

            // Display the current time dynamically
            function updateCurrentTime() {
                const now = new Date();
                const currentTime = now.toLocaleTimeString([], {
                    hour: '2-digit',
                    minute: '2-digit'
                });
                const currentDay = now.toLocaleDateString([], {
                    weekday: 'long',
                    month: 'long',
                    day: 'numeric',
                    year: 'numeric'
                });

                // Update the displayed time and day
                document.getElementById('currentTime').textContent = currentTime;
                document.getElementById('currentDay').textContent = currentDay;
            }

            setInterval(updateCurrentTime, 1000); // Update every second
            updateCurrentTime(); // Initial call to display time immediately

            // Initialize the attendees input field (Select2)
            $('#attendees').select2({
                placeholder: 'Enter email addresses',
                tags: true,
                tokenSeparators: [',', ' '],
                maximumSelectionLength: 10,
                width: '100%'
            });
        });
    </script>
@endpush
