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
                // 2‑4 дочерние
                $children = Rubric::factory()
                    ->count(rand(2, 4))
                    ->make();                    // make() — без сохранения

                // сохраняем дочерние и запоминаем
                $root->children()->saveMany($children);

                // для каждой дочерней — 0‑2 внучатые
                $children->each(function ($child) {
                    if (rand(0, 1)) {            // 50 % шанс
                        $grandChildren = Rubric::factory()
                            ->count(rand(1, 2))
                            ->make();

                        $child->children()->saveMany($grandChildren);
                    }
                });
            });

    }
}
