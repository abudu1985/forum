<?php

namespace App\Http\Controllers;

use App\Tread;
use Illuminate\Http\Request;

class RepliesController extends Controller
{
    public function __construct()
    {
       $this->middleware('auth');
    }

    public function store($channelId, Tread $tread)
    {
        $this->validate(request(), [
            'body' => 'required'
        ]);

        $tread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id()
        ]);
        return back();
    }
}
