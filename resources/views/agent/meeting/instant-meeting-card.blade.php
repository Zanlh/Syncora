<!-- Modal for Attendees -->
<div class="modal fade" id="attendeesModal" tabindex="-1" aria-labelledby="attendeesModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="attendeesModalLabel">Enter Meeting Title and Attendees</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="attendeesForm" action="{{ route('agent.meetings.create.submit') }}" method="POST">
                    @csrf
                    <!-- Input for Meeting Title -->
                    <div class="mb-3">
                        <label for="meeting_title" class="form-label">Meeting Title</label>
                        <input type="text" class="form-control" id="meeting_title" name="meeting_title"
                            placeholder="Enter the meeting title" required>
                    </div>

                    <!-- Input for Attendees' Emails (using Select2) -->
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

                    <!-- Hidden Fields for Other Meeting Details -->
                    <input type="hidden" name="meeting_type" id="meeting_type" value="instant">
                    <input type="hidden" name="start_date" id="start_date"
                        value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                    <input type="hidden" name="end_date" id="end_date"
                        value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                    <input type="hidden" name="start_time" id="start_time"
                        value="{{ \Carbon\Carbon::now()->format('h:i A') }}">
                    <input type="hidden" name="end_time" id="end_time"
                        value="{{ \Carbon\Carbon::now()->format('h:i A') }}">
                    <input type="hidden" name="time_zone" id="time_zone" value="{{ old('time_zone') }}">
                    <input type="hidden" name="location" id="location" value="Virtual">

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary">Start Meeting</button>
                </form>
            </div>
        </div>
    </div>
</div>
