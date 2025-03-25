<?php

namespace App\Traits;

use Illuminate\Http\RedirectResponse;
use Firebase\JWT\JWT;

trait MeetingHelperTrait
{
  /**
   * Redirect to meeting room if it's an instant meeting.
   *
   * @param array $validated
   * @param $meeting
   * @return RedirectResponse
   */
  public function redirectToMeetingRoom(array $validated, $meeting): RedirectResponse
  {
    // Generate JWT token for the agent
    $jwt = $this->generateJitsiToken(auth('agent')->user(), $meeting->meeting_room);

    if ($validated['meeting_type'] === 'instant') {
      return redirect()->route('agent.meeting.room', [
        'room' => $meeting->meeting_room,
        'token' => $jwt, // Pass JWT token in the URL
      ])->with('success', 'Meeting created successfully.');
    }

    // Default redirection for scheduled meetings
    return redirect()->route('agent.meetings')->with('success', 'Meeting scheduled successfully.');
  }

  /**
   * Generate JWT token for Jitsi authentication.
   */
  private function generateJitsiToken($user, $room)
  {
    $payload = [
      'aud' => env('SYNCORA_APP'), // app_id
      'iss' => 'syncora.duckdns.org', // Issuer
      'sub' => '*',
      'room' => $room, // Room name
      'exp' => time() + 3600,          // Token expires in 1 hour
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