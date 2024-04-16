@extends('admin.layout')
@section('content')
    <div id="app">
        <task link_cws="{{config('app.link_cws')}}" link_event="{{config('app.link_event')}}"></task>
    </div>
@endsection
