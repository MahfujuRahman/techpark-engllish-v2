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

    <!-- our mission area start -->
    @include('frontend.pages.about.components.our_mission')
    <!-- our mission area end -->

    <!-- our vision area start -->
    @include('frontend.pages.about.components.our_vision')
    <!-- our vision area end -->

    @include('frontend.pages.about.components.our_team')

    <!-- professional trainers area start -->
    @include('frontend.pages.about.components.professional_trainers')
    <!-- professional trainers area end -->

@endsection
