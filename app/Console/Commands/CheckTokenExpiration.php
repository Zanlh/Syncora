<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Meeting;
use Firebase\JWT\JWT;
use Carbon\Carbon;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Log;
use Firebase\JWT\ExpiredException;

class CheckTokenExpiration extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'check:token-expiry';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Check if meeting token has expired and update the status';

  /**
   * Secret key used to encode/decode JWT (use the same one that you use to generate the token)
   * Replace with your actual key or use an environment variable
   */

  /**
   * Execute the console command.
   */
  public function handle()
  {
    $secretKey = env('SYNCORA_SECRET_KEY');

    // Get all meetings where the token is set
    $meetings = Meeting::whereNotNull('token')->get();

    foreach ($meetings as $meeting) {
      try {
        // Check if the token is expired
        $decoded = JWT::decode($meeting->token, new Key($secretKey, 'HS256'));

        // Check if the token contains the 'exp' claim and it is a valid timestamp
        if (isset($decoded->exp) && is_numeric($decoded->exp)) {
          // Extract expiration time from the token (in Unix timestamp format)
          $expirationTime = $decoded->exp;

          // Compare the expiration time with the current time
          if ($expirationTime < Carbon::now()->timestamp) {
            // Token has expired, update the meeting status and clear the token
            $meeting->update([
              'status' => 'inactive',
              'token' => null,
            ]);

            Log::info("Meeting ID {$meeting->id} status updated to inactive and token cleared.");
          }
        } else {
          Log::warning("Meeting ID {$meeting->id} has no valid expiration claim.");
        }
      } catch (ExpiredException $e) {
        // Token is expired, handle the case where the token is expired
        $meeting->update([
          'status' => 'inactive',
          'token' => null,
        ]);
        Log::info("Meeting ID {$meeting->id} token expired and status updated to inactive.");
      } catch (\Exception $e) {
        // Other decoding errors
        Log::error("Failed to decode token for meeting ID {$meeting->id}: {$e->getMessage()}");
      }
    }
  }
}