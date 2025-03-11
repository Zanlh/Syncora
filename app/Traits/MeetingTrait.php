<?php

namespace App\Traits;

use App\Models\Meeting;

trait MeetingTrait
{
  // Get meetings by status
  public function getMeetingByStatus($status)
  {
    return Meeting::where('status', $status)->get();
  }

  // Get meetings within a specific date range
  public function getMeetingsByDateRange($startDate, $endDate)
  {
    return Meeting::whereBetween('start_date', [$startDate, $endDate])
      ->orWhereBetween('end_date', [$startDate, $endDate])
      ->get();
  }

  // Get meetings for a specific moderator
  public function getMeetingsByModerator($moderatorId)
  {
    return Meeting::where('moderator_id', $moderatorId)->get();
  }

  // Get upcoming meetings
  public function getUpcomingMeetings()
  {
    return Meeting::where('start_date', '>=', now())
      ->orderBy('start_date')
      ->orderBy('start_time')
      ->get();
  }

  // Update the status of a meeting
  public function updateMeetingStatus($meetingId, $status)
  {
    $meeting = Meeting::findOrFail($meetingId);
    $meeting->status = $status;
    $meeting->save();

    return $meeting;
  }

  // Get meeting by its unique meeting link
  public function getMeetingByLink($link)
  {
    return Meeting::where('meeting_link', $link)->first();
  }

  // Cancel a meeting
  public function cancelMeeting($meetingId)
  {
    $meeting = Meeting::findOrFail($meetingId);
    $meeting->status = 'canceled';
    $meeting->save();

    return $meeting;
  }

  // Get meetings by agent (moderator)
  public function getMeetingsByAgent($agentId)
  {
    return Meeting::where('agent_id', $agentId)->get();
  }
}