
@component('profiles.activities.activity')
    @slot('heading')
        {{ $profileUser->name }} replied to
        <a href="{{ $activity->subject->tread->path()}}"> "{{ $activity->subject->tread->title }}" </a>
    @endslot
    @slot('body')
        {{ $activity->subject->body }}
    @endslot
@endcomponent


