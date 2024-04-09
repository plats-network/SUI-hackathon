@extends('admin.layout')
@section('content')
    <div id="app">
        <task-edit csrf="{{ csrf_token() }}" id="{{$id}}"></task-edit>
    </div>
@endsection
