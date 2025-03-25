<?php

namespace App\Services\Meeting;

use Illuminate\Support\Str;

class MeetingUtilityService
{
  /**
   * Generate a unique Jitsi meeting room name.
   */
  public function generateMeetingRoomName($prefix = '')
  {
    $words = explode("\n", file_get_contents('https://raw.githubusercontent.com/dwyl/english-words/master/words.txt'));

    // Pick two random words
    $randomPair = ucfirst(trim($words[array_rand($words)])) . ucfirst(trim($words[array_rand($words)]));

    return $prefix . $randomPair;
  }

  /**
   * Generate the meeting link.
   */
  public function generateMeetingLink($meetingRoom)
  {
    return 'https://syncora.duckdns.org/' . $meetingRoom;
  }
}