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
      'email' => 'agent1@example.com',
      'password' => bcrypt('syncoraagent'), // Encrypting the password
      'created_at' => now(),
      'updated_at' => now(),
    ]);
    DB::table('agents')->insert([
      'name' => 'Agent Two',
      'email' => 'agent2@example.com',
      'password' => bcrypt('syncoraagent'), // Encrypting the password
      'created_at' => now(),
      'updated_at' => now(),
    ]);
    DB::table('agents')->insert([
      'name' => 'Agent Three',
      'email' => 'agen3t@example.com',
      'password' => bcrypt('syncoraagent'), // Encrypting the password
      'created_at' => now(),
      'updated_at' => now(),
    ]);
    DB::table('agents')->insert([
      'name' => 'Agent Four',
      'email' => 'agent4@example.com',
      'password' => bcrypt('syncoraagent'), // Encrypting the password
      'created_at' => now(),
      'updated_at' => now(),
    ]);
  }
}