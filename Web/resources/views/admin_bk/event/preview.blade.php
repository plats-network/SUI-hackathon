@extends('admin.layout')

@section('content')
    <div id="app">
        <event-preview task_id="{{$task_id}}" link_cws="{{config('app.link_cws')}}" link_event="{{config('app.link_event')}}" link_mini_game="{{config('app.link_minigame')}}"></event-preview>
    </div>
@endsection
