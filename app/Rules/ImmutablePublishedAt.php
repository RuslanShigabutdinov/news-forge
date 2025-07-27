<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Carbon;

use App\Models\News;

class ImmutablePublishedAt implements ValidationRule
{
    public function __construct(private readonly News $news) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $current = $this->news->published_at;
        $incoming = Carbon::parse($value);

        // Новость уже опубликована но новое значение отличается
        if ($current && $current->isPast() && !$current->equalTo($incoming)) {
            $fail('validation.immutable_published_at')->translate();
        }
    }
}
