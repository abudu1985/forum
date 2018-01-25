@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Forum Threads</div>

                    <div class="panel-body">
                     @foreach($treads as $tread)
                         <article>
                             <div class="level">
                             <h4 class="flex">
                                 <a href="{{ $tread->path() }}">{{ $tread->title }}</a>
                             </h4>
                                 <a href="{{$tread->path()}}">{{ $tread->replies_count }}{{ str_plural(' reply', $tread->replies_count)}}</a>
                             </div>
                             <div class="body">{{ $tread->body }}</div>
                         </article>
                         <hr>
                        @endforeach
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
