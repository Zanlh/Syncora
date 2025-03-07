<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class AgentSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    DB::table('agents')->insert([
      'name' => 'Agent One',
      'email' => 'agent@example.com',
      'password' => bcrypt('MangoSlice'), // Encrypting the password
      'created_at' => now(),
      'updated_at' => now(),
    ]);
  }
}
