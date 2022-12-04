@extends('site::layouts.master.master')

@section('sidebar')
    @parent
@endsection

@section('content')
    <h1 class="text-center text-md-start">{{ $page->title }}</h1>
    <br>
    {!! $page->content !!}
    <hr>
@endsection