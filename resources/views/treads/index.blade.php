@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                @forelse($treads as $tread)
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="level">
                            <h4 class="flex">
                                <a href="{{ $tread->path() }}">{{ $tread->title }}</a>
                            </h4>
                            <a href="{{$tread->path()}}">{{ $tread->replies_count }}{{ str_plural(' reply', $tread->replies_count)}}</a>
                        </div>
                    </div>

                    <div class="panel-body">

                             <div class="body">{{ $tread->body }}</div>

                         <hr>

                </div>
            </div>
                @empty
                    <p>There are no relevant result at this time.</p>
                @endforelse
        </div>
    </div>
    </div>
@endsection
