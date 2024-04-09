@extends('admin.layout')

@section('content')
    <div id="app">
        <group csrf="{{ csrf_token() }}"></group>
    </div>
@endsection
