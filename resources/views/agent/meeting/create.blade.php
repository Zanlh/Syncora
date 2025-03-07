@extends('layouts/contentNavbarLayout')

@section('title', 'Create Meeting')

@section('content')
    <h1 class="mb-4">Create Meeting</h1>
    <div class="card shadow-lg p-4 rounded">
        <form action="#" method="POST">
            @csrf
            <div class="mb-3">
                <label for="meeting_title" class="form-label">Meeting Title</label>
                <input type="text" name="meeting_title" class="form-control rounded-3" required>
            </div>

            <div class="mb-3">
                <label for="attendee" class="form-label">Attendees (Emails)</label>
                <select id="attendee" name="attendee[]" class="form-control rounded-3" multiple="multiple" required>
                    <!-- Emails will be selected here -->
                </select>
            </div>

            <div class="mb-3">
                <label for="optional_attendee" class="form-label">Optional Attendees (Emails)</label>
                <select id="optional_attendee" name="optional_attendee[]" class="form-control rounded-3"
                    multiple="multiple">
                    <!-- Optional emails will be selected here -->
                </select>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" name="start_date" class="form-control rounded-3" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="start_time" class="form-label">Start Time</label>
                    <input type="time" name="start_time" class="form-control rounded-3" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" name="end_date" class="form-control rounded-3" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="end_time" class="form-label">End Time</label>
                    <input type="time" name="end_time" class="form-control rounded-3" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="time_zone" class="form-label">Time Zone</label>
                <input type="text" name="time_zone" class="form-control rounded-3" required>
            </div>

            <div class="mb-3">
                <label for="location" class="form-label">Location</label>
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
            $.noConflict(); // Remove jquery conflict
            console.log('jQuery is loaded:', $); // Check if jQuery is loaded
            console.log('Select2 is loaded:', $.fn.select2); // Check if Select2 is available

            // Initialize Select2 on the attendee select field
            $('#attendee').select2({
                placeholder: 'Enter email addresses',
                tags: true, // Allows inputting custom emails
                tokenSeparators: [',', ' '],
                maximumSelectionLength: 10, // Optional: limit the number of emails
            });
            console.log('Select2 initialized for attendees');

            // Initialize Select2 on the optional attendees select field
            $('#optional_attendee').select2({
                placeholder: 'Enter optional email addresses',
                tags: true, // Allows inputting custom emails
                tokenSeparators: [',', ' '],
                maximumSelectionLength: 10, // Optional: limit the number of emails
            });
            console.log('Select2 initialized for optional attendees');
        });
    </script>
@endpush
