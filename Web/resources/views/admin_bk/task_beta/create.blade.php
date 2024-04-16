@extends('admin.layout')
@section('content')
    <div id="app">
        <task-create csrf="{{ csrf_token() }}" link_cws="{{config('app.link_cws')}}" link_event="{{config('app.link_event')}}"></task-create>
    </div>
@endsection
