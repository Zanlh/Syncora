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
            <div class="mb-3">
                <label for="meeting_title" class="form-label">
                    <i class="bx bx-calendar"></i> Meeting Title
                </label>
                <input type="text" name="meeting_title" class="form-control rounded-3" required>
            </div>

            <div class="mb-3">
                <label for="attendees" class="form-label">
                    <i class="bx bx-envelope"></i> Attendees (Emails)
                </label>
                <select id="attendees" name="attendees[]" class="form-control rounded-3" multiple="multiple" required>
                </select>
            </div>

            <div class="mb-3">
                <label for="optional_attendees" class="form-label">
                    <i class="bx bx-envelope-open"></i> Optional Attendees (Emails)
                </label>
                <select id="optional_attendees" name="optional_attendees[]" class="form-control rounded-3"
                    multiple="multiple">
                </select>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="start_date" class="form-label">
                        <i class="bx bx-calendar-event"></i> Start Date
                    </label>
                    <input type="date" name="start_date" class="form-control rounded-3" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="start_time" class="form-label">
                        <i class="bx bx-time-five"></i> Start Time
                    </label>
                    <select name="start_time" class="form-control rounded-3" required>
                        <option value="">Select Start Time</option>
                        @foreach (\App\Helpers\TimeHelper::generateTimeSlots() as $time)
                            <option value="{{ $time }}">{{ $time }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="end_date" class="form-label">
                        <i class="bx bx-calendar-event"></i> End Date
                    </label>
                    <input type="date" name="end_date" class="form-control rounded-3" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="end_time" class="form-label">
                        <i class="bx bx-time-five"></i> End Time
                    </label>
                    <select name="end_time" class="form-control rounded-3" required>
                        <option value="">Select End Time</option>
                        @foreach (\App\Helpers\TimeHelper::generateTimeSlots() as $time)
                            <option value="{{ $time }}">{{ $time }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label for="time_zone" class="form-label">
                    <i class="bx bx-time"></i> Time Zone
                </label>
                <input type="text" name="time_zone" class="form-control rounded-3" required>
            </div>

            <div class="mb-3">
                <label for="location" class="form-label">
                    <i class="bx bx-map"></i> Location
                </label>
                <input type="text" name="location" class="form-control rounded-3" required>
            </div>

            <button type="submit" class="btn btn-primary px-4 py-2">
                <i class="bx bx-calendar-plus"></i> Create Meeting
            </button>
        </form>
    </div>
@endsection

@push('script-page')
    <script>
        $(document).ready(function() {
            $.noConflict();

            $('#attendees').select2({
                placeholder: 'Enter email addresses',
                tags: true,
                tokenSeparators: [',', ' '],
                maximumSelectionLength: 10,
            });

            $('#optional_attendees').select2({
                placeholder: 'Enter optional email addresses',
                tags: true,
                tokenSeparators: [',', ' '],
                maximumSelectionLength: 10,
            });
        });
    </script>
@endpush
