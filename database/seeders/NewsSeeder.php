<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\{
    Author,
    News,
    Rubric,
};

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rubricIds = Rubric::pluck('id');

        Author::all()->each(function ($author) use ($rubricIds) {
            News::factory()
                ->count(15)
                ->for($author)
                ->create()
                ->each(fn ($news) =>
                    $news->rubrics()->sync(
                        $rubricIds->random(rand(1, 3))->all()
                    )
                );
        });
    }
}
