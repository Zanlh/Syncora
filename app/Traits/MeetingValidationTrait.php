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
    ]);
  }
}
