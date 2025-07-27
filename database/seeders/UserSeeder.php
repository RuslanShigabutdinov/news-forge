<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Enums\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->count(10)->create();

        User::factory()->create([
            'name'     => 'admin',
            'email'    => 'admin@admin.com',
            'password' => bcrypt('root'),
            'role'     => Role::ADMIN->value
        ]);
        User::factory()->create([
            'name'     => 'author',
            'email'    => 'author@author.com',
            'password' => bcrypt('root'),
            'role'     => Role::AUTHOR->value
        ]);

    }
}
