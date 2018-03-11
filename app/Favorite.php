<?php
/**
 * Created by PhpStorm.
 * User: Anna
 * Date: 21.01.2018
 * Time: 20:23
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use RecordsActivity;

    protected $guarded = [];

    protected $fillable = [
        'user_id', 'favorited_id', 'favorited_type'
    ];

    public function favorited()
    {
        return $this->morphTo();
    }
}