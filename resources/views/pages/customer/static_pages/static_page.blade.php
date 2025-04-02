@extends('layouts.users.app')<!-- Assuming this is your layout file -->


@section('content')
    <div class="container mx-auto py-8 px-4">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">{{ $page->title }}</h1>
        <div class="prose max-w-none">
            {!! $page->content !!}
        </div>
    </div>
@endsection