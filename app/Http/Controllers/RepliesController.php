<?php

namespace App\Http\Controllers;

use App\Reply;
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
        return back()->with('flash', 'Your reply has been left!');
    }

    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);

        $reply->update(request(['body']));
    }

    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);

        $reply->delete();

        if(request()->acceptsJson()){
            return response(['status' => 'Reply deleted']);
        }

        return back();
    }
}
