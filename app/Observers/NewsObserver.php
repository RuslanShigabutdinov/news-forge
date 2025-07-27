<?php

namespace App\Observers;

use App\Models\News;
use App\Jobs\SendNewsPublishedNotification;
use Illuminate\Support\Facades\Log;

class NewsObserver
{
    public function saved(News $news): void
    {
        if (!$news->wasRecentlyCreated && !$news->wasChanged('published_at') || !$news->published_at) {
            return;
        }

        $delay = $news->published_at->isFuture()
            ? $news->published_at
            : now();

        SendNewsPublishedNotification::dispatch(
            $news->id,
            $news->published_at->copy()
        )
        ->delay($delay)
        ->afterCommit();

        Log::channel('mail')->info('Mail queued', [
            'news_id'     => $news->id,
            'author_id'   => $news->author_id,
            'email'       => $news->author->user->email ?? null,
            'run_at'      => $delay->toDateTimeString(),
        ]);

    }
}
