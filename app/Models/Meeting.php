<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
  protected $fillable = [
    'title',
    'agent_id',
    'attendees',
    'start_date',
    'start_time',
    'end_date',
    'end_time',
    'time_zone',
    'location',
    'meeting_room',
    'meeting_link',
    'token',
    'status',
    'meeting_type',
  ];


  protected $casts = [
    'attendees' => 'array',
  ];

  public function agent()
  {
    return $this->belongsTo(Agent::class);
  }
}