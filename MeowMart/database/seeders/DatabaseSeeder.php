<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Lor Frederick Aquino',
            'email' => 'laquino@filamer.edu.ph',
            'password' => Hash::make('Password_123'),
            'role' => 'admin',
        ]);
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'mvaflor@filamer.edu.ph',
            'password' => Hash::make('Password_123'),
            'role' => 'manager',
        ]);
        User::factory()->create([
            'name' => 'Mark Louie Antioquia',
            'email' => 'mantioquia@filamer.edu.ph',
            'password' => Hash::make('Password_123'),
            'role' => 'cashier',
        ]);

        $this->call(CategorySeeder::class);
    }
}
