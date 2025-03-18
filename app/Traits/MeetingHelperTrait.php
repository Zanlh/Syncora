<?php

namespace App\Traits;

use Illuminate\Http\RedirectResponse;

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
    if ($validated['meeting_type'] === 'instant') {
      return redirect()->route('agent.meeting.room', [
        'room' => $meeting->meeting_room, // Meeting room name
      ])->with('success', 'Meeting created successfully.');
    }

    // Default redirection for scheduled meetings
    return redirect()->route('agent.meetings')->with('success', 'Meeting scheduled successfully.');
  }
}