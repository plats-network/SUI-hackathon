@extends('admin.layout')

@section('content')
    <div id="app">
        <user csrf="{{ csrf_token() }}"></user>
    </div>
@endsection
