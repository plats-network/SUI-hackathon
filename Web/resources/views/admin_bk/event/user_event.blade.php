@extends('admin.layout')

@section('content')
    <div id="app">
        <event-user task_id="{{$task_id}}" link_cws="{{config('app.link_cws')}}" link_event="{{config('app.link_event')}}">

        </event-user>
    </div>
@endsection
