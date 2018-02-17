<?php

namespace App;

use App\Notifications\TreadWasUpdated;
use Illuminate\Database\Eloquent\Model;

class TreadSubscription extends Model
{
    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return$this->belongsTo(User::class);
    }

    public function tread()
    {
        return$this->belongsTo(Tread::class);
    }

    /**
     * @param $reply
     */
    public function notify($reply)
    {
        $this->user->notify(new TreadWasUpdated($this->tread, $reply));
    }
}
