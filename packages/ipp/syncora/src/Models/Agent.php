<?php

namespace Ipp\Syncora\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class Agent extends Authenticatable
{
  use Notifiable;

  protected $fillable = [
    'name',
    'email',
    'password',
  ];

  protected $hidden = [
    'password',
    'remember_token',
  ];

  protected $casts = [
    'email_verified_at' => 'datetime',
  ];

  public function meetings()
  {
    return $this->hasMany(Meeting::class);
  }
}