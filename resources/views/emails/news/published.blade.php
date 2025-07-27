@component('mail::message')
# {{ $news->title }}

Здравствуйте, {{ $news->author->full_name }}!

Ваша статья опубликована в {{ $news->published_at?->format('d.m.Y H:i') }}.

@component('mail::button', ['url' => url("/api/news/{$news->id}")])
Читать на сайте
@endcomponent

Спасибо за сотрудничество!
@endcomponent