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
                                    <h4 class="card-text">
                                        {{ $canceledMeetings && $canceledMeetings->count() > 0 ? $canceledMeetings->count() : 0 }}
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Meeting Creation Card -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title">Meeting Actions</h5>
                </div>
                <div class="card-body">
                    <!-- Create Meeting Button -->
                    <a href="{{ route('agent.meetings.create') }}" class="btn btn-primary me-2">
                        <i class="bx bxs-calendar"></i> Schedule a Meeting
                    </a>

                    <!-- Start Meeting Button -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#instantMeetingModal">
                        <i class="bx bxs-video me-1"></i> Start a Meeting
                    </button>
                </div>
            </div>
        </div>

        <!-- Second Column: Create a Meeting (With Date Picker and Upcoming Meetings) -->
        <div class="col-lg-6 col-md-12">
            <div class="card">
                <!-- Fixed Card Header with Gradient -->
                <div class="card-header"
                    style="background: linear-gradient(135deg, #696CFF, #FF6F61);
                       text-align: center;
                       padding: 2rem 1.5rem;
                       border-radius: 0.75rem;">
                    <div id="currentTime"
                        style="font-family: 'Roboto', sans-serif;
                           font-size: 4rem;
                           font-weight: bold;
                           text-shadow: 2px 2px rgba(0, 0, 0, 0.2);
                           margin-bottom: 0.5rem;
                           color: white;">
                        <!-- Ensure time is visible on gradient -->
                    </div>
                    <div id="currentDay"
                        style="font-size: 1.25rem;
                           font-weight: 400;
                           color: rgba(255, 255, 255, 0.7);">
                    </div>
                </div>

                <!-- Scrollable Card Body with Styled Content -->
                <div class="card-body" style="padding: 1.5rem;">
                    <!-- Fixed Header: Today's Meetings and Calendar Button -->
                    <div class="d-flex justify-content-between align-items-center mb-3 mt-3"
                        style="position: sticky; top: 0; background-color: white; z-index: 10;">
                        <h4 class="card-title" style="font-size: 1.5rem; font-weight: bold; color: #333;">
                            Today's Meetings
                        </h4>

                        <!-- Calendar Button -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#calendarModal">
                            <i class="bx bxs-calendar"></i> Calendar
                        </button>
                    </div>

                    <!-- List of Meetings (Scrollable Section) -->
                    <div class="list-group" style="max-height: 400px; overflow-y: auto;">
                        @if ($scheduledMeetings->isEmpty())
                            <div class="text-center mt-4">
                                <i class="bx bx-calendar-x text-primary" style="font-size: 3rem;"></i>
                                <p class="text-muted mt-2" style="font-size: 1.2rem;">No scheduled meetings found.</p>
                            </div>
                        @else
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
                                                    <a class="dropdown-item text-primary" href="#">
                                                        <i class="bx bx-link me-2"></i> Copy Invite Link
                                                    </a>
                                                </li>
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
                                            <br>
                                            <!-- Time Zone Display -->
                                            <span class="timezone-diff" data-timezone="{{ $meeting->time_zone }}"
                                                data-datetime="{{ \Carbon\Carbon::parse($meeting->start_date . ' ' . $meeting->start_time)->toIso8601String() }}"></span>
                                            <span style="font-size: 0.875rem;">
                                                Time Zone: {{ $meeting->time_zone }} (
                                                {{ $meeting->time_diff }})
                                            </span>
                                            <br>
                                            <span style="font-size: 0.875rem;">
                                                Meeting is in {{ $meeting->local_time_display }} local time
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
                                                    class="btn btn-primary btn-sm">
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

                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Instant Meeting -->
    <div class="modal fade" id="instantMeetingModal" tabindex="-1" aria-labelledby="instantMeetingModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="instantMeetingModalLabel">Enter Meeting Title and Attendees</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="attendeesForm" action="{{ route('agent.instant.meetings.create') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="meetingTitle" class="form-label">Meeting Title</label>
                            <input type="text" class="form-control" id="meeting_title" name="meeting_title" required>
                        </div>
                        {{-- <div class="mb-3">
                            <label for="attendees" class="form-label">Attendees</label>
                            <input type="email" class="form-control" id="attendees" name="attendees"
                                placeholder="Add attendees" multiple required>
                        </div> --}}
                        <input type="hidden" class="form-control" id="meeting_type" name="meeting_type"
                            value="instant">
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-play-circle me-1"></i> Start Meeting
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('script-page')
    <script>
        const {
            DateTime
        } = luxon;

        // Function to show current time in user's time zone
        function updateTime() {
            const now = DateTime.local();
            document.getElementById("currentTime").textContent = now.toFormat('hh:mm a');
            document.getElementById("currentDay").textContent = now.toFormat('cccc, MMMM dd, yyyy');
        }


        setInterval(() => {
            updateTime();
        }, 1000);
    </script>
@endpush
