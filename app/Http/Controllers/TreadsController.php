<?php

namespace App\Http\Controllers;

use App\Filters\TreadFilters;
use App\Channel;
use App\Tread;
use Illuminate\Http\Request;

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
       // dd($request->all());
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
        return redirect($tread->path());
    }

    /**
     * @param $channel
     * @param Tread $tread
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($channel, Tread $tread)
    {
        return view('treads.show', [
            'tread' => $tread,
            'replies' => $tread->replies()->paginate(20)
        ]);
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
//        if($tread->user_id != auth()->id()){
//            abort(403, 'You do not have permission to do this action.');
//        }
        $tread->delete();
        if(request()->wantsJson()){
            return response([], 204);
        }
        return redirect('/treads');
    }

}
