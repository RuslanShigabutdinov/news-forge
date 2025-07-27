<?php

namespace App\Jobs;

use App\Mail\NewsPublishedMail;
use App\Models\News;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\{
    ShouldQueue
};
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Facades\Mail;
use Carbon\CarbonInterface;
use Illuminate\Queue\Middleware\Skip;
use Illuminate\Support\Facades\Log;

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

        if (! $news?->published_at) {
            Log::channel('mail')->warning('Mail skipped ‑ no published_at', [
                'news_id' => $this->newsId,
            ]);
            return;
        }
        try {

            Mail::to($news->author->user->email)
                ->send(new NewsPublishedMail($news));

            Log::channel('mail')->info('Mail sent', [         // успех
                'news_id'   => $news->id,
                'author_id' => $news->author_id,
                'email'     => $news->author->user->email,
                'subject'   => 'News published',
            ]);
        } catch (\Throwable $e) {
            Log::channel('mail')->error('Mail failed', [
                'news_id' => $this->newsId,
                'error'   => $e->getMessage(),
            ]);

            throw $e;
        }
    }

}
