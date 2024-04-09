@extends('admin.layout')

@section('content')
    <div id="app">
        <reward csrf="{{ csrf_token() }}"></reward>
    </div>
@endsection
