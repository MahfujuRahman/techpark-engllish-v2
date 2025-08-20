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
    @include('frontend.pages.about.components.about_us')

    <!-- brands area start -->
    @include('frontend.pages.home.components.brands')
    <!-- brands area end -->

    <!-- our moto area start -->
    @include('frontend.pages.about.components.our_moto')
    <!-- our moto area end -->
@endsection
