<?php

namespace App\Jobs;

use App\Mail\NewsPublishedMail;
use App\Models\News;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\{
    ShouldBeUnique, 
    ShouldQueue
};
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Facades\Mail;
use Carbon\CarbonInterface;
use Illuminate\Queue\Middleware\Skip;

class SendNewsPublishedNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $newsId,
        public CarbonInterface $snapshotAt
    ) {}

    public function middleware(): array
    {
        return [
            // пропустить job, если опубликованная дата изменилась
            Skip::when(function () {
                $news = News::find($this->newsId);

                // нет новости, стала черновиком или дата уже другая?
                return ! $news?->published_at
                    || ! $news->published_at->equalTo($this->snapshotAt);
            }),
        ];
    }

    public function handle(): void {
        $news = News::with('author.user')->find($this->newsId);

        // двойная защита — вдруг модель удалена между Skip и handle
        if (! $news?->published_at) {
            return;
        }

        Mail::to($news->author->user->email)
            ->send(new NewsPublishedMail($news));
    }

}
