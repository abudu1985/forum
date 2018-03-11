<?php

namespace App\Http\Controllers;

use App\Filters\TreadFilters;
use App\Channel;
use App\Tread;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TreadsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        //$this->middleware('auth')->only(['store', 'create']);
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index(Channel $channel, TreadFilters $filters)
    {
        $treads = Tread::latest()->filter($filters);

        if($channel->exists){
            $treads->where('channel_id', $channel->id);
        }

        $treads = $treads->get();

        if(request()->wantsJson()){
            return $treads;
        }

        return view('treads.index', compact('treads'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('treads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
            'channel_id' => 'required|exists:channels,id'
        ]);

        $tread = Tread::create([
            'user_id' => auth()->id(),
            'title' => request('title'),
            'channel_id' => request('channel_id'),
            'body' => request('body')
        ]);
        //dd($tread);
        return redirect($tread->path())
            ->with('flash', 'Your thread has been published!');
    }

    /**
     * @param $channel
     * @param Tread $tread
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($channel, Tread $tread)
    {
        // Record that the user visited that page
        // Record a timestamp
        if (auth()->check()){
            auth()->user()->read($tread);
        }


//        $key = sprintf("users.%s.visits.%s", auth()->id(), $tread->id);
//
//        cache()->forever($key, Carbon::now());

        return view('treads.show', compact('tread'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Tread  $tread
     * @return \Illuminate\Http\Response
     */
    public function edit(Tread $tread)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tread  $tread
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tread $tread)
    {
        //
    }

    /**
     * @param $channel
     * @param Tread $tread
     */
    public function destroy($channel, Tread $tread)
    {
        $this->authorize('update', $tread);

        $tread->delete();
        if(request()->wantsJson()){
            return response([], 204);
        }
        return redirect('/treads');
    }

}
