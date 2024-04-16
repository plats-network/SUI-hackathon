@extends('admin.layout')

@section('content')
    <div id="app">
        <event-edit task_id="{{$task_id}}" csrf="{{ csrf_token() }}" link_cws="{{config('app.link_cws')}}" type_reward="{{json_encode(config('app.type_reward'))}}"></event-edit>
    </div>
@endsection
