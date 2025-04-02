<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\CheckTokenExpiration;

Artisan::command('inspire', function () {
  $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();
Artisan::command('check:token-expiry', function () {
  (new CheckTokenExpiration())->handle();
})->hourly();