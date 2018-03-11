<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use Favoritable, RecordsActivity;

    protected $guarded = [];
    protected $with = ['owner', 'favorites'];
    protected $appends = ['favoritesCount', 'isFavorited'];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($reply) {
            $reply->tread->increment('replies_count');
        });

        static::deleted(function ($reply) {
            $reply->tread->decrement('replies_count');
        });
    }

    protected $fillable = [
        'tread_id', 'user_id', 'body'
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tread()
    {
        return $this->belongsTo(Tread::class);
    }

    public function path()
    {
        return $this->tread->path() . "#reply-{$this->id}";
    }

}
