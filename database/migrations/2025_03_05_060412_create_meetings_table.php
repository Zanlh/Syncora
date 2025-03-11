<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('meetings', function (Blueprint $table) {
      $table->id();
      $table->string('title');
      $table->foreignId('agent_id')->constrained('agents')->onDelete('cascade');
      $table->json('attendees');  // List of attendees' emails (JSON array)
      $table->json('optional_attendees');  // Optional attendees (JSON array)
      $table->date('start_date'); // Separate date column
      $table->time('start_time'); // Separate time column
      $table->date('end_date'); // Separate date column
      $table->time('end_time'); // Separate time column
      $table->string('time_zone');
      $table->string('location');
      $table->string('meeting_room')->nullable()->default('default_room'); // Make nullable or set a default value
      $table->string('meeting_link')->nullable(); // Store Jitsi meeting link
      $table->enum('status', ['scheduled', 'active', 'inactive', 'canceled'])->default('scheduled');  // Meeting status
      $table->foreignId('moderator_id')->nullable()->constrained('agents')->onDelete('set null'); // Track the moderator (admin)
      $table->enum('meeting_type', ['scheduled', 'instant'])->default('scheduled');  // Type of meeting (Scheduled or Instant)
      $table->timestamp('cancelled_at')->nullable();  // To track when the meeting was canceled
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('meetings');
  }
};