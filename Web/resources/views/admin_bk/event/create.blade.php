@extends('admin.layout')

@section('content')
    <div id="app">
        <event-create csrf="{{ csrf_token() }}" link_cws="{{config('app.link_cws')}}" link_event="{{config('app.link_event')}}" type_reward="{{json_encode(config('app.type_reward'))}}"></event-create>
    </div>
@endsection
