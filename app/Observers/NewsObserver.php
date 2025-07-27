<?php

namespace App\Observers;

use App\Models\News;
use App\Jobs\SendNewsPublishedNotification;

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
        ->afterCommit();                   // безопасно при транзакциях
    }
}
