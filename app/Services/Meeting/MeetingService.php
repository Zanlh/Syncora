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

    $meetingStatus = 'scheduled';

    $startDate = $validatedData['start_date'];
    $endDate = $validatedData['end_date'];
    $startTime = Carbon::createFromFormat('h:i A', $validatedData['start_time'])->format('H:i:s');
    $endTime = Carbon::createFromFormat('h:i A', $validatedData['end_time'])->format('H:i:s');

    // Combine start date and time
    $startDateTime = Carbon::createFromFormat('Y-m-d H:i:s', "$startDate $startTime", $validatedData['time_zone'])->timestamp;
    // Combine end date and time
    $endDateTime = Carbon::createFromFormat('Y-m-d H:i:s', "$endDate $endTime", $validatedData['time_zone'])->timestamp;


    $jwtToken = $this->utilityService->generateJitsiToken(Auth::guard('agent')->user(), $meetingRoom, $startDateTime, $endDateTime);
    $meeting = new Meeting([
      'title' => $validatedData['meeting_title'],
      'agent_id' => Auth::guard('agent')->user()->id, // Use the custom agent guard
      'attendees' => json_encode($validatedData['attendees']),
      'start_date' => $startDate,
      'start_time' => $startTime,
      'end_date' => $endDate,
      'end_time' => $endTime,
      'time_zone' => $validatedData['time_zone'],
      'location' => $validatedData['location'],
      'meeting_room' => $meetingRoom,
      'meeting_link' => $meetingLink, // Store the meeting link
      'token' => $jwtToken, // Store the JWT token
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

    // Get current datetime with timezone
    $meetingDateTime = Carbon::now($timeZone);
    $startDateTime = $meetingDateTime; // Start time is now
    $endDateTime = $meetingDateTime->copy()->addHours(1); // End time is 1 hours from now

    // Generate JWT Token
    $jwtToken = $this->utilityService->generateJitsiToken(
      Auth::guard('agent')->user(),
      $meetingRoom,
      $startDateTime->timestamp,
      $endDateTime->timestamp
    );

    // Create the meeting with the provided title and auto-fill the rest
    $meeting = new Meeting([
      'title' => $validatedData['meeting_title'],
      'agent_id' => Auth::guard('agent')->user()->id, // Use the custom agent guard
      'attendees' => json_encode($validatedData['attendees'] ?? []),
      'optional_attendees' => json_encode($validatedData['optional_attendees'] ?? []),
      'start_date' => $startDateTime->format('Y-m-d'),
      'start_time' => $startDateTime->format('H:i:s'),
      'end_date' => $endDateTime->format('Y-m-d'),
      'end_time' => $endDateTime->format('H:i:s'),
      'time_zone' => $timeZone,
      'location' => $validatedData['location'] ?? 'Virtual', // Default to 'Virtual'
      'meeting_room' => $meetingRoom,
      'meeting_link' => $meetingLink, // Store the meeting link
      'token' => $jwtToken, // Store the JWT token
      'status' => $meetingStatus,
      'meeting_type' => $validatedData['meeting_type'] ?? 'instant',
    ]);

    $meeting->save();
    return $meeting;
  }
}