<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\Relations\{
    BelongsTo,
    HasMany,
};

class Author extends Model
{
    /** @use HasFactory<\Database\Factories\AuthorFactory> */
    use HasFactory;

    use HasFactory;

    protected $fillable = ['full_name', 'avatar_path', 'user_id'];

    
    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
    public function news(): HasMany {
        return $this->hasMany(News::class);
    }

    /* ---------- accessor / mutator ---------- */
    public function setAvatarPathAttribute($v)
    {
        $this->attributes['avatar_path'] =
            $v instanceof UploadedFile ? $v->store('avatars', 'public') : $v;
    }
    public function getAvatarUrlAttribute()
    {
        return $this->avatar_path ? asset('storage/'.$this->avatar_path)
                                  : asset('images/default_avatar.png');
    }

}
