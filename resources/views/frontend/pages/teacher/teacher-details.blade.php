@php
    $meta = [
        // "meta" => [],
        'seo' => [
            'title' => $teacher->full_name,
            'image' => asset('seo.jpg'),
        ],
    ];
@endphp


@extends('frontend.layouts.layout', $meta)
@section('contents')
    <section class="instructor_details">
        <div class="container">
            <div class="profile_image">
                <div class="profile_cover">
                    <img class="rounded rounded-sm" style="max-height: 400px; object-fit: cover;"
                        src="{{ assetHelper(optional($teacher)->cover_photo) }}" alt="{{ $teacher->title }}" loading="lazy">
                </div>
                <div class="profile_bottom">
                    <div class="left">
                        <img class="rounded rounded-sm" src="{{ assetHelper(optional($teacher)->image) }}"
                            alt="{{ $teacher->title }}" loading="lazy">
                    </div>
                    <div class="right">
                        <h2 class="teacher_name">{{ $teacher?->full_name }}</h2>
                        <div class="social-links mt-2">
                            @if($teacher?->facebook)
                                <a href="{{ $teacher->facebook }}" target="_blank" class="me-2" title="Facebook">
                                    <i class="fab fa-facebook fa-lg"></i>
                                </a>
                            @endif
                            @if($teacher?->linkedin)
                                <a href="{{ $teacher->linkedin }}" target="_blank" class="me-2" title="LinkedIn">
                                    <i class="fab fa-linkedin fa-lg"></i>
                                </a>
                            @endif
                            @if($teacher?->twitter)
                                <a href="{{ $teacher->twitter }}" target="_blank" class="me-2" title="Twitter">
                                    <i class="fab fa-twitter fa-lg"></i>
                                </a>
                            @endif
                            @if($teacher?->instagram)
                                <a href="{{ $teacher->instagram }}" target="_blank" class="me-2" title="Instagram">
                                    <i class="fab fa-instagram fa-lg"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mt-4">
        <div class="container">
            <div class="mwidth" style="">
                <div>
                    <span class="fw-bold">Designation:</span>
                    <span class="text-muted">{{ $teacher?->designation }}</span>
                </div>

                <div>
                    <span class="fw-bold">Email:</span>
                    <span class="text-muted">{{ $teacher?->email }}</span>
                </div>
                
                <div class="mb-3">
                    <span class="fw-bold">Phone:</span>
                    <span class="text-muted">{{ $teacher?->phone }}</span>
                </div>

                <div class="teacher-description mb-4" style="line-height: 1.7;">
                    {!! $teacher?->description !!}
                </div>


                <div class="" style="margin-top: 40px;">
                    <strong style="margin-bottom: 50px;">Courses: </strong>
                    <div class="d-flex flex-wrap gap-3">
                        @foreach ($teacher->courses as $course)
                            <div class="card mx-1" style="width: 18rem;">
                                <img class="card-img-top mt-2" height="150" src="/{{ $course->image }}" alt="card image"
                                    style="object-fit: cover;">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $course->title }}</h5>
                                    <p class="card-text">{{ $course->type }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
