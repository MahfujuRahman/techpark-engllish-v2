@php
    $meta = [
        // "meta" => [],
        'seo' => [
            'title' => 'about',
            'image' => asset('seo.jpg'),
        ],
    ];
@endphp


{{-- @dd($website_about) --}}
@extends('frontend.layouts.layout', $meta)
@section('contents')
    @include('frontend.pages.about.components.aboutus')

    <!-- brands area start -->
    @include('frontend.pages.home.components.brands')
    <!-- brands area end -->
@endsection
