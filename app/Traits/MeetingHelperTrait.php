<?php

namespace App\Traits;

use Illuminate\Http\RedirectResponse;

use App\Services\Meeting\MeetingUtilityService;

trait MeetingHelperTrait
{
  protected $utilityService;


  public function __construct(MeetingUtilityService $utilityService)
  {
    $this->utilityService = $utilityService;
  }

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
        'room' => $meeting->meeting_room,
        'token' => $meeting->token,
      ])->with('success', 'Meeting created successfully.');
    }

    // Default redirection for scheduled meetings
    return redirect()->route('agent.meetings')->with('success', 'Meeting scheduled successfully.');
  }
}