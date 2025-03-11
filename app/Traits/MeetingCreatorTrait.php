<?php

namespace App\Traits;

use App\Models\Meeting;
use Illuminate\Support\Facades\Auth;
use App\Services\MeetingService;
use Carbon\Carbon;

trait MeetingCreatorTrait
{
  protected $meetingService;

  // Injecting the MeetingService in the constructor
  public function __construct(MeetingService $meetingService)
  {
    $this->meetingService = $meetingService;
  }

  public function createMeeting($validatedData)
  {
    // Generate a unique meeting room name
    $meetingRoom = $this->meetingService->generateMeetingRoomName();
    // Generate the meeting link
    $meetingLink = $this->meetingService->generateMeetingLink($meetingRoom);

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


    // Call the MeetingService to store the meeting and return the result
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