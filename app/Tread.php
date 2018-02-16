<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tread extends Model
{
    use RecordsActivity;

    protected $fillable = [
        'user_id', 'title', 'body', 'channel_id'
    ];

    protected $guarded = [];
    protected $with = ['creator', 'channel'];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($tread){
           $tread->replies->each->delete();
        });
    }

    public function path()
    {
        return url('/treads/') . '/' . "{$this->channel->slug}/{$this->id}";
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
//            ->withCount('favorites')
//            ->with('owner');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    /**
     * @param $reply
     * @return Model
     */
    public function addReply($reply)
    {
        return $this->replies()->create($reply);
    }

    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

    public function getReplyCountAttribute()
    {
        return $this->replies()->count();
    }

}
