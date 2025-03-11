<?php

namespace App\Services\Meeting;

use Illuminate\Support\Str;

use App\Models\Meeting;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MeetingService
{
  /**
   * Generate a unique Jitsi meeting room name.
   *
   * @param string $prefix
   * @return string
   */
  public function generateMeetingRoomName($prefix = 'syncora_')
  {
    return $prefix . Str::random(10); // Generate a random 10-character meeting room name
  }

  /**
   * Generate the meeting link based on the room name.
   *
   * @param string $meetingRoom
   * @return string
   */
  public function generateMeetingLink($meetingRoom)
  {
    return 'https://localhost:8443/' . $meetingRoom; // Replace with your Jitsi server URL
  }

  public function createMeeting($validatedData)
  {
    $meetingRoom = $this->generateMeetingRoomName();
    $meetingLink = $this->generateMeetingLink($meetingRoom);

    // Combine the start date and time to create a Carbon object
    //$meetingDate = Carbon::createFromFormat('Y-m-d h:i A', $validatedData['start_date'] . ' ' . $validatedData['start_time'], $validatedData['time_zone']);
    $meetingStatus = 'scheduled';

    // If the meeting is instant, set it as active and use the current date/time
    if (isset($validatedData['meeting_type']) && $validatedData['meeting_type'] === 'instant') {
      $meetingDate = Carbon::now($validatedData['time_zone']); // Instant meetings use the current time
      $meetingStatus = 'active';
    }
    $startTime = Carbon::createFromFormat('h:i A', $validatedData['start_time'])->format('H:i:s');
    $endTime = Carbon::createFromFormat('h:i A', $validatedData['end_time'])->format('H:i:s');

    $meeting = new Meeting([
      'title' => $validatedData['meeting_title'],
      'agent_id' => Auth::guard('agent')->user()->id, // Use the custom agent guard
      'attendees' => json_encode($validatedData['attendees']),
      'optional_attendees' => json_encode($validatedData['optional_attendees'] ?? []),
      'start_date' => $validatedData['start_date'],
      'start_time' => $startTime,
      'end_date' => $validatedData['end_date'],
      'end_time' => $endTime,
      'time_zone' => $validatedData['time_zone'],
      'location' => $validatedData['location'],
      'meeting_room' => $meetingRoom,
      'meeting_link' => $meetingLink, // Store the meeting link
      'status' => $meetingStatus,
      'meeting_type' => $validatedData['meeting_type'] ?? 'scheduled',
    ]);

    $meeting->save();
    return $meeting;
  }
}