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

            <!-- Meeting Creation Card -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title">Meeting Creation</h5>
                </div>
                <div class="card-body">
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

        <!-- Second Column: Create a Meeting (With Date Picker and Upcoming Meetings) -->
        <div class="col-lg-6 col-md-12">
            <div class="card">
                <!-- Fixed Card Header with Gradient -->
                <div class="card-header"
                    style="background: linear-gradient(135deg, #6f42c1, #ff6f61); text-align: center; padding: 2rem 1.5rem; border-radius: 0.75rem;">
                    <div id="currentTime"
                        style="font-family: 'Roboto', sans-serif; font-size: 4rem; font-weight: bold; text-shadow: 2px 2px rgba(0, 0, 0, 0.2); margin-bottom: 0.5rem;">
                    </div>
                    <div id="currentDay" style="font-size: 1.25rem; font-weight: 400; color: rgba(255, 255, 255, 0.7);">
                    </div>
                </div>

                <!-- Scrollable Card Body with Styled Content -->
                <div class="card-body" style="height: calc(100vh - 250px); overflow-y: auto; padding: 1.5rem;">
                    <!-- Upcoming Meetings Section -->
                    <h4 class="card-title mb-3 mt-3" style="font-size: 1.5rem; font-weight: bold; color: #333;">
                        Today's Meetings
                    </h4>

                    <!-- List of Meetings -->
                    <div class="list-group">
                        @foreach ($scheduledMeetings as $meeting)
                            <div class="card mb-3 shadow-sm"
                                style="border-radius: 10px; border: 1px solid #f1f1f1; position: relative;">
                                <div class="card-body" style="padding: 1.25rem; position: relative;">
                                    <!-- Three-dot Dropdown -->
                                    <div class="dropdown" style="position: absolute; top: 10px; right: 15px;">
                                        <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown"
                                            aria-expanded="false" style="border: none; background: transparent;">
                                            <i class="bx bx-dots-vertical-rounded"></i> <!-- Boxicons Three-dot icon -->
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item text-warning" href="#">
                                                    <i class="bx bx-calendar-edit me-2"></i> Reschedule
                                                </a>
                                            </li>
                                            <li>
                                                <form action="#" method="POST"
                                                    onsubmit="return confirm('Are you sure you want to cancel this meeting?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="dropdown-item text-danger" type="submit">
                                                        <i class="bx bx-x-circle me-2"></i> Cancel
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>

                                    <!-- Meeting Title -->
                                    <h4 class="card-title" style="font-size: 1.25rem; font-weight: 600; color: #333;">
                                        {{ $meeting->title }}
                                    </h4>

                                    <!-- Meeting Date and Time -->
                                    <p class="text-muted" style="font-size: 1rem;">
                                        {{ \Carbon\Carbon::parse($meeting->start_date)->format('M d, Y') }}
                                        <span style="font-size: 0.875rem; display: inline-block; margin-left: 5px;">
                                            {{ \Carbon\Carbon::parse($meeting->start_time)->format('g:i A') }} -
                                            {{ \Carbon\Carbon::parse($meeting->end_time)->format('g:i A') }}
                                        </span>
                                    </p>

                                    <!-- Meeting Description -->
                                    <p class="text-muted" style="font-size: 0.95rem; line-height: 1.5; color: #555;">
                                        {{ $meeting->description }}
                                    </p>

                                    <!-- Action Buttons -->
                                    <div class="mt-3">
                                        @if ($meeting->meeting_link)
                                            <a href="{{ route('agent.meeting.room', ['room' => $meeting->meeting_room, 'token' => $meeting->token]) }}"
                                                target="_blank" class="btn btn-primary btn-sm">
                                                Join Meeting
                                            </a>
                                        @else
                                            <button class="btn btn-secondary btn-sm" disabled>No meeting link
                                                available</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach


                    </div>
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
                        <input type="hidden" class="form-control" id="meeting_type" name="meeting_type"
                            value="instant">
                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary">Start Meeting</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- End Content -->

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
