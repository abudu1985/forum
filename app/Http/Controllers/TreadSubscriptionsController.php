<?php

namespace App\Http\Controllers;

use App\Tread;
use Illuminate\Http\Request;

class TreadSubscriptionsController extends Controller
{
    public function store($channelId, Tread $tread)
    {
       $tread->subscribe();
    }

    public function destroy($channelId, Tread $tread)
    {
        $tread->unsubscribe();
    }
}
