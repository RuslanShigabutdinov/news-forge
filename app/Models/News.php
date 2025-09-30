<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\{
    BelongsTo,
    BelongsToMany,
};
use Illuminate\Support\Facades\DB;


class News extends Model
{
    /** @use HasFactory<\Database\Factories\NewsFactory> */
    use HasFactory;

    protected $fillable = [
        'title', 'announcement', 'body', 'published_at', 'author_id'
    ];
    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function author(): BelongsTo  { 
        return $this->belongsTo(Author::class);
    }
    public function rubrics(): BelongsToMany { 
        return $this->belongsToMany(Rubric::class); 
    }

    public function scopePublished(Builder $q) {
        return $q->whereNotNull('published_at')
                 ->where('published_at', '<=', now());
    }

    public function scopeSearch(Builder $query, string $term): Builder {
        $pdo = DB::getPdo();
        $quoted = $pdo->quote($term);

        return $query->select('*')
            ->addSelect(DB::raw("
                ts_rank(
                    to_tsvector('english', coalesce(title,'') || ' ' || coalesce(body,'')),
                    plainto_tsquery('english', {$quoted})
                ) as rank
            "))
            ->whereRaw(
                "to_tsvector('english', coalesce(title,'') || ' ' || coalesce(body,'')) @@ plainto_tsquery('english', ?)",
                [$term]
            )
            ->orderByDesc('rank');
    }


}
