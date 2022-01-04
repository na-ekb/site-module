@extends('site::layouts.master.master')

@section('sidebar')
    @parent
    <p>This is appended to the master sidebar.</p>
@endsection

@section('content')
    <h1>{{ $page->title }}</h1>
    <br>
    {!! $page->content !!}
    <hr>
@endsection