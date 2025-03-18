<?php

namespace App\Services\Meeting;

use Illuminate\Support\Str;

class MeetingUtilityService
{
  /**
   * Generate a unique Jitsi meeting room name.
   */
  public function generateMeetingRoomName($prefix = 'syncora_')
  {
    return $prefix . Str::random(10);
  }

  /**
   * Generate the meeting link.
   */
  public function generateMeetingLink($meetingRoom)
  {
    return 'https://syncora.duckdns.org/' . $meetingRoom;
  }
}
