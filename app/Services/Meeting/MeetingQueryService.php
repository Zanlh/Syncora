<?php

namespace App\Services\Meeting;

use App\Models\Meeting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class MeetingQueryService
{
  /**
   * Get meetings by status.
   */
  public function getMeetingsByStatus($status)
  {
    $today = Carbon::today(date_default_timezone_get());
    return Meeting::where('status', $status)
      ->where('agent_id', Auth::id())
      ->whereDate('start_date', $today) // Filter meetings with today's date
      ->get();
  }

  /**
   * Get meetings within a date range.
   */
  public function getMeetingsByDateRange($startDate, $endDate)
  {
    return Meeting::whereBetween('start_date', [$startDate, $endDate])
      ->orWhereBetween('end_date', [$startDate, $endDate])
      ->get();
  }

  /**
   * Get meetings for a specific moderator.
   */
  public function getMeetingsByModerator($moderatorId)
  {
    return Meeting::where('moderator_id', $moderatorId)->get();
  }

  /**
   * Get upcoming meetings.
   */
  public function getUpcomingMeetings()
  {
    return Meeting::where('start_date', '>=', now())
      ->orderBy('start_date')
      ->orderBy('start_time')
      ->get();
  }

  /**
   * Get meeting by unique link.
   */
  public function getMeetingByLink($link)
  {
    return Meeting::where('meeting_link', $link)->first();
  }
}