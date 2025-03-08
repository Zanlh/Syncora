<?php

namespace App\Helpers;

use Carbon\Carbon;

class TimeHelper
{
  public static function generateTimeSlots()
  {
    $start = Carbon::createFromFormat('h:i A', '12:00 AM');
    $end = Carbon::createFromFormat('h:i A', '11:30 PM');
    $interval = 30; // 30 minutes
    $times = [];

    while ($start <= $end) {
      $times[] = $start->format('h:i A'); // 12-hour format with AM/PM
      $start->addMinutes($interval);
    }

    return $times;
  }
}
