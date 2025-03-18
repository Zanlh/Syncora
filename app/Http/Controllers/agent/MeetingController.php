<?php

namespace App\Http\Controllers\agent;

use App\Http\Controllers\Controller;
use App\Http\Requests\MeetingRequest;
use Illuminate\Http\Request;
use App\Services\Meeting\MeetingService;
use App\Services\Meeting\MeetingQueryService;
use App\Traits\MeetingHelperTrait;

class MeetingController extends Controller
{
  use MeetingHelperTrait;
  protected $meetingService;
  protected $meetingQueryService;

  public function __construct(MeetingService $meetingService, MeetingQueryService $meetingQueryService)
  {
    $this->meetingService = $meetingService;
    $this->meetingQueryService = $meetingQueryService;
  }
  public function index()
  {
    // Get meetings data
    $scheduledMeetings = $this->meetingQueryService->getMeetingsByStatus('scheduled');
    $canceledMeetings = $this->meetingQueryService->getMeetingsByStatus('canceled');
    $upcomingMeetings = $this->meetingQueryService->getUpcomingMeetings();

    // Pass data to the view
    return view('agent.meeting.index', compact('scheduledMeetings', 'canceledMeetings', 'upcomingMeetings'));
  }


  public function create()
  {
    return view('agent.meeting.create');
  }

  public function createScheduleMeeting(MeetingRequest $request): \Illuminate\Http\RedirectResponse
  {
    try {
      $validated = $request->validated();
      $meeting = $this->meetingService->createMeeting($validated);

      return $this->redirectToMeetingRoom($validated, $meeting);
    } catch (\Illuminate\Validation\ValidationException $e) {

      return back()->with('error', $e->getMessage())->withInput();
    }
  }
  public function createInstantMeeting(Request $request): \Illuminate\Http\RedirectResponse
  {
    $validated = $request->validate([
      'meeting_title' => 'required|string|max:255',
      'attendees' => 'required|array|min:1',
      'attendees.*' => 'required|email',
      'meeting_type' => 'nullable|in:scheduled,instant', // If you have meeting type field
    ]);

    $meeting = $this->meetingService->createInstantMeeting($validated);

    return $this->redirectToMeetingRoom($validated, $meeting);
  }
}