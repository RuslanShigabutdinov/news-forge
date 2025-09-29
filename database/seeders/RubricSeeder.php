<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Rubric;

class RubricSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Rubric::factory()
            ->count(5)
            ->create()
            ->each(function ($root) {
                $children = Rubric::factory()
                    ->count(rand(2, 4))
                    ->make();

                $root->children()->saveMany($children);

                $children->each(function ($child) {
                    if (rand(0, 1)) {
                        $grandChildren = Rubric::factory()
                            ->count(rand(1, 2))
                            ->make();

                        $child->children()->saveMany($grandChildren);
                    }
                });
            });

    }
}
