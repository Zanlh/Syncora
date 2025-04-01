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
  // Controller or Service Method to fetch meetings by status
  public function getMeetingsByStatus($status)
  {
    // Get today's date in the default timezone
    $today = Carbon::today(date_default_timezone_get());

    // Get the meetings with the given status
    $meetings = Meeting::where('status', $status)
      ->where('agent_id', Auth::id())
      ->whereDate('start_date', $today)
      ->get();

    // Get the user's current time
    $userCurrentTime = Carbon::now();

    // Loop through each meeting to calculate the time difference and local time
    foreach ($meetings as $meeting) {
      $this->setMeetingLocalTimeAndOffset($meeting, $userCurrentTime);
    }

    return $meetings;
  }

  // Method to calculate and set meeting local time and time offset
  private function setMeetingLocalTimeAndOffset($meeting, $userCurrentTime)
  {
    // Assuming the meeting time zone is stored in 'time_zone' (e.g., 'Asia/Singapore')
    $meetingTimeZone = $meeting->time_zone;

    // Combine the start date and time and convert it to the meeting's time zone
    $startDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $meeting->start_date . ' ' . $meeting->start_time, $meetingTimeZone);

    // Convert the meeting time to the user's local time
    $localTime = $startDateTime->timezone($userCurrentTime->getTimezone());

    // Format the meeting time in the user's local time zone (e.g., "Apr 02, 2025, 11:00 AM")
    $formattedLocalTime = $this->formatLocalTime($localTime);

    // Calculate the time difference and set the formatted difference string
    $formattedDiff = $this->calculateTimeDifference($startDateTime, $userCurrentTime, $meetingTimeZone);

    // Add the time difference and formatted local time as attributes to the meeting object for display
    $meeting->time_diff = $formattedDiff;
    $meeting->local_time_display = $formattedLocalTime;
  }

  // Helper Method to format the meeting time in local time
  private function formatLocalTime($localTime)
  {
    return $localTime->format('M d, Y g:i A'); // Example: "Apr 02, 2025, 11:00 AM"
  }

  // Helper Method to calculate the time difference between two time zones
  private function calculateTimeDifference($startDateTime, $userCurrentTime, $meetingTimeZone)
  {
    // Get the time zone offset for the meeting and user in seconds
    $meetingOffset = $startDateTime->timezone($meetingTimeZone)->getOffset();
    $userOffset = $userCurrentTime->getOffset();

    // Calculate the difference in seconds between the meeting time zone and user's current time zone
    $offsetDifferenceInSeconds = $meetingOffset - $userOffset;

    // Convert the difference to hours and minutes
    $hours = intdiv(abs($offsetDifferenceInSeconds), 3600);
    $minutes = (abs($offsetDifferenceInSeconds) % 3600) / 60;

    // Format the difference as a string (e.g., "+03:30" or "-02:00")
    return ($offsetDifferenceInSeconds >= 0 ? '+' : '-') . sprintf("%02d:%02d", $hours, $minutes);
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