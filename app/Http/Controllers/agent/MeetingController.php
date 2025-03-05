<?php

namespace App\Http\Controllers\agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MeetingController extends Controller
{
  //
  public function index()
  {
    return view('agent.meeting.index');
  }

  public function create()
  {
    return view('agent.meeting.create');
  }
}
