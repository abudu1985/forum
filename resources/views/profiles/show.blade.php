@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="coi-md-8 col-md-offset-2">
                <div class="page-header">
                    <h2>
                        {{ $profileUser->name }}
                    </h2>
                </div>

                @foreach($activities as $date => $activity)
                    <h4 class="page-header">{{ $date }}</h4>

                    @foreach($activity as $record)
                        @include("profiles.activities.{$record->type}", ['activity'=> $record])
                    @endforeach
                @endforeach
{{--                {{ $treads->links() }}--}}
            </div>
        </div>
    </div>
@endsection