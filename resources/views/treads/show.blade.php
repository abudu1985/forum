@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
{{--                        <a href="{{url('/profiles/') . '/'}}{{ $tread->creator->name }}">--}}
                        <div class="level">
                            <span class="flex">
                                   <a href="{{ route('profile', $tread->creator) }}">
                                {{ $tread->creator->name }}</a> posted:
                                {{ $tread->title }}
                            </span>
                            <form action="{{ $tread->path()}}" method="POST">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                <button type="submit" class="btn btn-link">Delete Thread</button>
                            </form>
                        </div>

                    </div>

                    <div class="panel-body">
                      {{ $tread->body }}
                    </div>
                </div>

                @foreach($replies as $reply)
                    @include ('treads.reply')
                @endforeach
                {{ $replies->links()}}

                @if( auth()->check())
                            <form method="POST" action="{{ $tread->path() . '/replies' }}">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <textarea name="body" id="body" class="form-control" placeholder="Have something to say..."></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Post</button>
                            </form>
                @else
                    <p class="text-center">Please <a href="{{ route('login') }}">sign in</a> to participate in this discussion.</p>
                @endif
            </div>

            <div class="col-md-4">
                <div class="panel panel-default">

                    <div class="panel-body">
                        <p>
                            This thread was published {{ $tread->created_at->diffForHumans()}}
                            by <a href="#">{{ $tread->creator->name }}</a>, and currently has <b>{{ $tread->replies_count }}</b> {{ str_plural('comment', $tread->replies_count) }}.
                        </p>
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection
