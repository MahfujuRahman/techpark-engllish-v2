@php
    $meta = [
        // "meta" => [],
        'seo' => [
            'title' => 'Professional Trainer Details',
            'image' => asset('seo.jpg'),
        ],
    ];
@endphp


@extends('frontend.layouts.layout', $meta)
@section('contents')
    <!-- trainers area starts -->
    <section class="trainers_area" style="margin-top: 60px;">
        <div class="container">
            <div class="trainers_description">
                <div class="trainers_title">
                    <h2 class="trainers_title_bangla">{{ $heading?->title }}</h2>
                    <div style="text-align: center;">
                        {!! $heading?->description !!}
                    </div>
                </div>
                <div class="trainers_details">
                    @foreach ($trainers as $trainer)
                        <div class="trainer_details">
                            <div class="trainer_images">
                                <div class="image">
                                    <img class="rounded rounded-sm" src="{{ assetHelper(optional($trainer)->image) }}"
                                        alt="{{ $trainer->title }}" loading="lazy">
                                </div>
                                <div class="trainer_info">
                                    <div class="trainer_name">{{ $trainer->full_name }}</div>
                                    <p>{{ $trainer->designation }}</p>
                                    <div class="trainer_link">
                                        @if ($trainer?->facebook)
                                            <a href="{{ $trainer->facebook }}" target="_blank" class="me-2"
                                                title="Facebook">
                                                <i class="fa-brands fb fa-square-facebook"></i>
                                            </a>
                                        @endif
                                        @if ($trainer?->linkedin)
                                            <a href="{{ $trainer->linkedin }}" target="_blank" class="me-2"
                                                title="LinkedIn">
                                                <i class="fa-brands ld fa-linkedin"></i>
                                            </a>
                                        @endif
                                        @if ($trainer?->twitter)
                                            <a href="{{ $trainer->twitter }}" target="_blank" class="me-2"
                                                title="Twitter">
                                                <i class="fa-brands tw fa-square-twitter"></i>
                                            </a>
                                        @endif
                                        @if ($trainer?->instagram)
                                            <a href="{{ $trainer->instagram }}" target="_blank" class="me-2"
                                                title="Instagram">
                                                <i class="fa-brands in fa-square-instagram"></i>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="trainer_descrip">
                                <p>
                                    {!! $trainer->short_description ?? '' !!}
                                </p>
                            </div>
                            <div class="profational_trainer_button_area mt-4">
                                <a href="{{ route('teacher.details', ['teacher_name' => str_replace(' ', '-', $trainer->full_name), 'slug' => $trainer->slug]) }}"
                                    class="button_all">
                                    <span class="btn_text">বিস্তারিত দেখুন</span>
                                    <span class="btn_icon"><i class="fa-solid fa-arrow-right"></i></span>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mr-paginate">
                    {{ $trainers->links() }}
                </div>
            </div>
        </div>
    </section>
    <!-- /trainers area ends -->
@endsection
