<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Console\Command;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;





class Kernel extends ConsoleKernel
{
  /**
   * The artisan commands provided by your application.
   *
   * @var array
   */
  protected $commands = [
    \App\Console\Commands\CheckTokenExpiration::class, // Example for the command you created
  ];
  /**
   * Define the application's command schedule.
   */
  protected function schedule(Schedule $schedule): void
  {
    // Example: Run a command daily
    $schedule->command('inspire')->daily();
    // Run the token expiration check every hour
    $schedule->command('check:token-expiry')->hourly()->output(storage_path('logs/token-expiry.log'));
  }

  /**
   * Register the commands for the application.
   */
  protected function commands(): void
  {
    $this->load(__DIR__ . '/Commands');

    require base_path('routes/console.php');
  }
}