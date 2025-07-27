<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\{
    User,
    Author
};
use App\Models\News;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Enums\Role;

class NewsTest extends TestCase
{
    use RefreshDatabase;

    public function test_author_can_delete_own_news() {
        $user = User::factory()->create(['role'=>Role::AUTHOR->value]);
        $author = Author::factory()->create(['user_id'=>$user->id]);
        Sanctum::actingAs($user, [], 'sanctum');
        $news = News::factory()->create(['author_id'=>$author->id]);

        $response = $this->deleteJson(route('news.destroy', $news));

        $response->assertNoContent();
        $this->assertDatabaseMissing('news', ['id'=>$news->id]);
    }

    public function test_author_cannot_delete_not_his_news()
    {
        $user1 = User::factory()->create(['role'=>Role::AUTHOR->value]);
        $author1 = Author::factory()->create(['user_id'=>$user1->id]);
        $user2 = User::factory()->create(['role'=>Role::AUTHOR->value]);
        $author2 = Author::factory()->create(['user_id'=>$user2->id]);
        $news = News::factory()->create(['author_id'=>$author2->id]);
        Sanctum::actingAs($user1, [], 'sanctum');

        $response = $this->deleteJson(route('news.destroy', $news));
        $response->assertForbidden();
    }
}
