<?php

namespace App\Services;

use Illuminate\Support\Str;

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
}