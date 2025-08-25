@php
    $meta = [
        'seo' => [
            'title' => 'Success Story All',
            'image' => asset('seo.jpg'),
        ],
    ];
@endphp
@extends('frontend.layouts.layout', $meta)

@section('contents')
    @include('frontend.pages.home.components.success_story', [
        'success_stories' => $success_stories,
    ])
@endsection
