<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\MeetingService;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   */
  public function register(): void
  {
    $this->app->singleton(MeetingService::class, function ($app) {
      return new MeetingService();
    });
  }

  /**
   * Bootstrap any application services.
   */
  public function boot(): void
  {
    //
  }
}