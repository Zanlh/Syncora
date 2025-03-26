<?php

namespace App\Services\Meeting;

use Firebase\JWT\JWT;
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

  /**
   * Generate JWT token for Jitsi authentication.
   */
  public function generateJitsiToken($user, $room, $startDateTime, $endDateTime): string
  {
    $issuedAt = time(); // Token issued now

    $notBefore = $startDateTime - 900; // 15 minutes before meeting starts
    $expiresAt = $endDateTime + 1800; // 30 minutes after meeting ends
    $payload = [
      'aud' => env('SYNCORA_APP'), // app_id
      'iss' => 'syncora.duckdns.org', // Issuer
      'sub' => '*',
      'room' => $room, // Meeting room
      'iat' => $issuedAt, // Issued at (now)
      'nbf' => $notBefore, // Not valid before 15 mins before start
      'exp' => $expiresAt, // Expires 30 mins after meeting ends
      'context' => [
        'user' => [
          'name' => $user->name,
          'email' => $user->email,
          'avatar' => $user->avatar ?? null,
          'moderator' => true, // User is a moderator
          'affiliation' => 'owner'   // User is the owner of the room
        ]
      ]
    ];

    return JWT::encode($payload, env('SYNCORA_SECRET_KEY'), 'HS256'); // Correct key from .env
  }
}