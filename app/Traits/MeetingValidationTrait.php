<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait MeetingValidationTrait
{
  public function validateMeeting(Request $request)
  {
    return $request->validate([
      'meeting_title' => 'required|string|max:255',
      'start_date' => 'required|date_format:Y-m-d', // Ensures date format
      'start_time' => 'required|date_format:h:i A', // Accepts AM/PM format
      'end_date' => 'required|date_format:Y-m-d|after_or_equal:start_date',
      'end_time' => 'required|date_format:h:i A|after:start_time', // Ensures end time is after start time
      'time_zone' => 'required|string',
      'location' => 'required|string',
      'attendees' => 'required|array|min:1',
      'attendees.*' => 'required|email', // Validate each attendee email
      'optional_attendees' => 'nullable|array',
      'optional_attendees.*' => 'nullable|email', // Validate optional attendee emails
      'meeting_room' => 'nullable|string|max:255', // Validate meeting room if needed
      'status' => 'nullable|in:scheduled,active,inactive,canceled', // If you have status field
      'meeting_type' => 'nullable|in:scheduled,instant', // If you have meeting type field
      'moderator_id' => 'nullable|exists:agents,id' // If moderator ID is provided, make sure it's an existing agent
    ]);
  }
}