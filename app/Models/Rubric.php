<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{
    BelongsTo,
    BelongsToMany,
    HasMany
};

class Rubric extends Model
{
    /** @use HasFactory<\Database\Factories\RubricFactory> */
    use HasFactory;

    protected $fillable = ['name', 'parent_id'];

    public function parent(): BelongsTo   { 
        return $this->belongsTo(Rubric::class, 'parent_id');
    }
    public function children(): HasMany { 
        return $this->hasMany  (Rubric::class, 'parent_id');
    }
    public function news(): BelongsToMany { 
        return $this->belongsToMany(News::class);
    }

    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }

}
