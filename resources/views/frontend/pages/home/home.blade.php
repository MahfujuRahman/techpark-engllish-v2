@php
    $meta = [
        // "meta" => [],
        'seo' => [
            'title' => 'Tech Park English',
            'image' => asset('seo.jpg'),
        ],
    ];
@endphp
@extends('frontend.layouts.layout', $meta)
@section('contents')

    <!-- banner_part start -->
    @include('frontend.pages.home.components.banner_section')
    <!-- banner_part end -->

    <!-- subBanner start -->
    @include('frontend.pages.home.components.sub_banner_section')
    <!-- subBanner end -->

    <!-- our_course area start -->
    {{-- @include('frontend.pages.home.components.course_section') --}}
    <!-- our_course area end -->

    <!-- our_course_specialty area start -->
    @include('frontend.pages.home.components.our_specialities')
    <!-- our_course_specialty area end -->

    <!-- earning_area start -->
    <!-- কোর্স শেষেই আর্নিং শুরু করুন -->
    {{-- @include('frontend.pages.home.components.course_outcome_steps', [
        'course_learning_steps' => $course_learning_steps,
    ]) --}}
    <!-- earning_area end -->

    <!-- Student success history arer start -->
    @include('frontend.pages.home.components.success_story', [
        'success_stories' => $success_stories,
    ])
    <!-- Student success history arer end -->

    <!-- our_trainer area start-->
    @include('frontend.pages.home.components.our_trainers')
    <!-- our_trainer area end-->

    <!-- free_seminar_area_start -->
    @include('frontend.pages.home.components.seminar')
    <!-- free_seminar_area_end -->

    <!-- job_career_area start-->
    <!-- আপনার জব ক্যারিয়ার নিয়ে চিন্তিত? -->
    {{-- @include('frontend.pages.home.components.career_counseling_section') --}}
    <!-- job_career area end-->


    <!-- brands area start -->
    @include('frontend.pages.home.components.brands')
    <!-- brands area end -->
@endsection
