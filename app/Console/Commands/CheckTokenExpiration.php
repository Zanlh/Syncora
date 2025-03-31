<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Meeting;
use Firebase\JWT\JWT;
use Carbon\Carbon;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Log;

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


  protected $secretKey = '83c8b5853e349bab7a4ba41c5e60d6764a60eac1133186e282969135bc5905d7';


  /**
   * Execute the console command.
   */
  public function handle()
  {
    // Your logic for checking token expiration
    Log::info('Checking token expiration...');
    // Get all meetings where the token is set
    $meetings = Meeting::whereNotNull('token')->get();

    foreach ($meetings as $meeting) {
      try {
        // Check if the token is expired
        $decoded = JWT::decode($meeting->token, new Key($this->secretKey, 'HS256'));

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

            $this->info("Meeting ID {$meeting->id} status updated to inactive and token cleared.");
          }
        } else {
          $this->error("Meeting ID {$meeting->id} has no valid expiration claim.");
        }
      } catch (\Exception $e) {
        // Handle decoding errors (e.g., invalid token format)
        if (strpos($e->getMessage(), 'Expired token') !== false) {
          // Token is expired, handle the case where the token is expired
          $meeting->update([
            'status' => 'inactive',
            'token' => null,
          ]);
          $this->info("Meeting ID {$meeting->id} token expired and status updated to inactive.");
        } else {
          // Other decoding errors
          $this->error("Failed to decode token for meeting ID {$meeting->id}: {$e->getMessage()}");
        }
      }
    }
  }
}