<?php

namespace App\Http\Controllers\agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\MeetingValidationTrait;

class MeetingController extends Controller
{
  //
  use MeetingValidationTrait;
  public function index()
  {
    return view('agent.meeting.index');
  }

  public function create()
  {
    return view('agent.meeting.create');
  }

  public function createMeting(Request $request): \Illuminate\Http\RedirectResponse
  {
    try {
      $validated = $this->validateMeeting($request);
      dd($validated);
    } catch (\Illuminate\Validation\ValidationException $e) {
      return back()->withErrors($e->validator)->withInput();
    }
  }
}
