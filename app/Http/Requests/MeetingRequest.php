<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MeetingRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      'meeting_title' => 'required|string|max:255',
      'start_date' => 'required|date_format:Y-m-d', // Ensures date format
      'start_time' => 'required|date_format:h:i A', // Accepts AM/PM format
      'end_date' => 'required|date_format:Y-m-d|after_or_equal:start_date',
      'end_time' => 'required|date_format:h:i A|after:start_time', // Ensures end time is after start time
      'time_zone' => 'required|string',
      'location' => 'required|string',
      'attendees' => 'required|array|min:1',
      'attendees.*' => 'required|email', // Validate each attendee email
      'meeting_room' => 'nullable|string|max:255', // Validate meeting room if needed
      'status' => 'nullable|in:scheduled,active,inactive,canceled', // If you have status field
      'meeting_type' => 'nullable|string',
      'moderator_id' => 'nullable|exists:agents,id' // If moderator ID is provided, make sure it's an existing agent
    ];
  }
}