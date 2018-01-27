@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="coi-md-8 col-md-offset-2">
                <div class="page-header">
                    <h1>
                        {{ $profileUser->name }}
                        <small>Since {{ $profileUser->created_at->diffForHumans() }}</small>
                    </h1>
                </div>

                @foreach($treads as $tread)
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="level">
                        <span class="flex">
                    <a href="#">{{ $tread->creator->name }}</a> posted:{{ $tread->title }}
                    </span>
                                <span>{{ $profileUser->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        <div class="panel-body">
                            {{ $tread->body }}
                        </div>
                    </div>
                @endforeach
                {{ $treads->links() }}
            </div>
        </div>
    </div>
@endsection