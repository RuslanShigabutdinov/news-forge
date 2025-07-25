<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\{
    Author,
    User
};

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::all()->each(fn ($user) =>
            Author::factory()->create([
                'user_id'   => $user->id,
                'full_name' => $user->name,
            ])
        );

    }
}
