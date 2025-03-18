<?php

namespace App\Services\Meeting;

use Illuminate\Support\Str;

use App\Models\Meeting;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MeetingService
{
  protected $utilityService;


  public function __construct(MeetingUtilityService $utilityService)
  {
    $this->utilityService = $utilityService;
  }

  public function createMeeting($validatedData)
  {
    $meetingRoom = $this->utilityService->generateMeetingRoomName();
    $meetingLink = $this->utilityService->generateMeetingLink($meetingRoom);

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

  public function createInstantMeeting($validatedData)
  {
    // Default time zone if not provided in the request
    $timeZone = $validatedData['time_zone'] ?? 'Australia/Sydney';

    // Generate a unique meeting room name and meeting link
    $meetingRoom = $this->utilityService->generateMeetingRoomName();
    $meetingLink = $this->utilityService->generateMeetingLink($meetingRoom);

    // Set the meeting status to 'active' for instant meetings
    $meetingStatus = 'active';

    // Set the current time as the start date/time for instant meetings
    $meetingDate = Carbon::now($timeZone);
    $startTime = $meetingDate->format('H:i:s');
    $endTime = $meetingDate->addMinutes(30)->format('H:i:s'); // Set a 30-minute duration for the instant meeting

    // Create the meeting with the provided title and attendees, and auto-fill the rest
    $meeting = new Meeting([
      'title' => $validatedData['meeting_title'],
      'agent_id' => Auth::guard('agent')->user()->id, // Use the custom agent guard
      'attendees' => json_encode($validatedData['attendees']),
      'optional_attendees' => json_encode($validatedData['optional_attendees'] ?? []),
      'start_date' => $meetingDate->format('Y-m-d'),
      'start_time' => $startTime,
      'end_date' => $meetingDate->format('Y-m-d'),
      'end_time' => $endTime,
      'time_zone' => $timeZone,
      'location' => $validatedData['location'] ?? 'Virtual', // Default to 'Virtual'
      'meeting_room' => $meetingRoom,
      'meeting_link' => $meetingLink, // Store the meeting link
      'status' => $meetingStatus,
      'meeting_type' => $validatedData['meeting_type'] ?? 'instant',
    ]);

    $meeting->save();
    return $meeting;
  }
}