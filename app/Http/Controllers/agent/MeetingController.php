<?php

namespace App\Http\Controllers\agent;

use App\Http\Controllers\Controller;
use App\Http\Requests\MeetingRequest;
use Illuminate\Http\Request;
use App\Traits\MeetingValidationTrait;
use App\Traits\MeetingCreatorTrait;
use App\Traits\MeetingTrait;
use App\Services\Meeting\MeetingService;
use Illuminate\Support\Str;

class MeetingController extends Controller
{
  //
  use MeetingValidationTrait, MeetingCreatorTrait, MeetingTrait;
  protected $meetingService;

  public function __construct(MeetingService $meetingService)
  {
    $this->meetingService = $meetingService;
  }
  public function index()
  {
    // Get meetings data
    $scheduledMeetings = $this->getMeetingByStatus('scheduled');
    $canceledMeetings = $this->getMeetingByStatus('canceled');
    $upcomingMeetings = $this->getUpcomingMeetings();

    // Pass data to the view
    return view('agent.meeting.index', compact('scheduledMeetings', 'canceledMeetings', 'upcomingMeetings'));
  }


  public function create()
  {
    return view('agent.meeting.create');
  }

  public function createScheduleMeting(MeetingRequest $request): \Illuminate\Http\RedirectResponse
  {
    try {
      $validated = $request->validated();
      // Create the meeting using the MeetingCreatorTrait logic
      $meeting = $this->meetingService->createMeeting($validated);

      // Check if the meeting is instant
      if ($validated['meeting_type'] === 'instant') {
        // Redirect directly to the meeting room
        return redirect()->route('agent.meeting.room', [
          'room' => $meeting->meeting_room, // Meeting room name
        ]);
      }

      // If it's a scheduled meeting, redirect back to the index (or another desired view)
      return redirect()->route('agent.meetings');
    } catch (\Illuminate\Validation\ValidationException $e) {
      return back()->withErrors($e->validator)->withInput();
    }
  }

  public function createInstantMeeting(Request $request): \Illuminate\Http\RedirectResponse {}
}