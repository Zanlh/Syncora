@extends('layouts/contentNavbarLayout')

@section('title', 'Create Meeting')

@section('content')
    <h1 class="mb-4">Create Meeting</h1>
    <div class="card shadow-lg p-4 rounded">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('agent.meetings.create.submit') }}" method="POST">
            @csrf
            <input type="hidden" name="meeting_type" value="scheduled">

            <div class="mb-3">
                <label for="meeting_title" class="form-label">
                    <i class="bx bx-calendar"></i> Meeting Title
                </label>
                <input type="text" name="meeting_title" class="form-control rounded-3" value="{{ old('meeting_title') }}"
                    required>
            </div>

            <div class="mb-3">
                <label for="attendees" class="form-label">
                    <i class="bx bx-envelope"></i> Attendees (Emails)
                </label>
                <select id="attendees" name="attendees[]" class="form-control rounded-3" multiple="multiple" required>
                    @foreach (old('attendees', []) as $email)
                        <option value="{{ $email }}" selected>{{ $email }}</option>
                    @endforeach
                </select>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="start_date" class="form-label">
                        <i class="bx bx-calendar-event"></i> Start Date
                    </label>
                    <input type="date" name="start_date" class="form-control rounded-3" value="{{ old('start_date') }}"
                        required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="start_time" class="form-label">
                        <i class="bx bx-time-five"></i> Start Time
                    </label>
                    <select name="start_time" class="form-control rounded-3" required>
                        <option value="">Select Start Time</option>
                        @foreach (\App\Helpers\TimeHelper::generateTimeSlots() as $time)
                            <option value="{{ $time }}" {{ old('start_time') == $time ? 'selected' : '' }}>
                                {{ $time }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="end_date" class="form-label">
                        <i class="bx bx-calendar-event"></i> End Date
                    </label>
                    <input type="date" name="end_date" class="form-control rounded-3" value="{{ old('end_date') }}"
                        required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="end_time" class="form-label">
                        <i class="bx bx-time-five"></i> End Time
                    </label>
                    <select name="end_time" class="form-control rounded-3" required>
                        <option value="">Select End Time</option>
                        @foreach (\App\Helpers\TimeHelper::generateTimeSlots() as $time)
                            <option value="{{ $time }}" {{ old('end_time') == $time ? 'selected' : '' }}>
                                {{ $time }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label for="time_zone" class="form-label">Time Zone</label>
                <select name="time_zone" id="time_zone" class="form-control rounded-3" required>
                    @foreach (timezone_identifiers_list() as $timezone)
                        <option value="{{ $timezone }}" {{ old('time_zone') == $timezone ? 'selected' : '' }}>
                            {{ $timezone }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="location" class="form-label">
                    <i class="bx bx-map"></i> Location
                </label>
                <input type="text" name="location" class="form-control rounded-3" value="{{ old('location') }}"
                    required>
            </div>

            <button type="submit" class="btn btn-primary px-4 py-2">
                <i class="bx bx-calendar-plus"></i> Create Meeting
            </button>
        </form>
    </div>
@endsection

@push('script-page')
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>

    <script>
        $(document).ready(function() {
            $.noConflict();

            $('#attendees').select2({
                placeholder: 'Enter email addresses',
                tags: true,
                tokenSeparators: [',', ' '],
                maximumSelectionLength: 10,
            });

            // Auto select timezone
            $.get("https://ipinfo.io/json", function(response) {
                let detectedTimezone = response.timezone;
                if ($("#time_zone option[value='" + detectedTimezone + "']").length > 0) {
                    $("#time_zone").val(detectedTimezone).trigger('change');
                }
            }).fail(function() {
                console.error("Failed to retrieve timezone.");
            });

            // Pre-fill the date and time fields with current values
            function getNearestTime() {
                let now = moment(); // Current time
                let startTime = now.startOf('minute').add(30 - (now.minute() % 30),
                    'minutes'); // Nearest 30 minutes
                let endTime = moment(startTime).add(1, 'hour'); // End time 1 hour later

                // Set the start and end time values
                $('select[name="start_time"]').val(startTime.format('hh:mm A')).trigger('change');
                $('select[name="end_time"]').val(endTime.format('hh:mm A')).trigger('change');

                // Set the start and end date as today
                $('input[name="start_date"]').val(now.format('YYYY-MM-DD'));
                $('input[name="end_date"]').val(now.format('YYYY-MM-DD'));
            }

            // Call the function to set the date and time when the page loads
            getNearestTime();
        });
    </script>
@endpush
