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

@push('styles')
    <style>
        /* Teacher details - responsive, mobile-first */
        .instructor_details .profile_cover img {
            width: 100%;
            height: clamp(200px, 30vw, 420px);
            object-fit: cover;
            border-radius: 12px;
            display: block;
        }

        .profile_bottom {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-top: -48px;
            align-items: center;
            padding: 0 12px;
        }

        .profile_bottom .left img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 12px;
            border: 5px solid #fff;
            box-shadow: 0 8px 30px rgba(2, 6, 23, 0.08);
        }

        .profile_bottom .right {
            text-align: center;
        }

        .teacher_name {
            font-size: clamp(20px, 3.6vw, 28px);
            margin: 0;
            font-weight: 800;
            color: #0f172a;
        }

        .social-links {
            margin-top: 8px;
        }

        .social-links a {
            margin-right: 8px;
            color: #334155;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            border-radius: 8px;
            transition: all .18s ease;
            background: transparent;
        }

        .social-links a:hover {
            background: linear-gradient(90deg, #667eea, #764ba2);
            color: #fff;
            transform: translateY(-2px);
        }

        .mwidth {
            max-width: 1100px;
            margin: 0 auto;
        }

        .mwidth>div {
            display: grid;
            grid-template-columns: 1fr;
            gap: 10px;
        }

        .teacher-description {
            margin-top: 12px;
        }



        /* Larger screens: layout adjustments */
        @media(min-width:768px) {
            .profile_bottom {
                flex-direction: row;
                align-items: flex-end;
                gap: 20px;
                margin-top: -60px;
                padding: 0;
            }

            .profile_bottom .right {
                text-align: left;
                flex: 1;
            }

            .mwidth>div {
                grid-template-columns: repeat(2, 1fr);
                gap: 18px;
            }

            .teacher-description {
                grid-column: 1 / -1;
            }

            .profational_trainer_button_area {
                margin-left: auto;
                align-self: center;
            }
        }

        @media(min-width:1024px) {
            .profile_bottom .left img {
                width: 140px;
                height: 140px;
            }
        }

        @media(max-width:420px) {
            .profile_bottom .left img {
                width: 88px;
                height: 88px;
                border-width: 3px;
            }

            .teacher_name {
                font-size: 18px;
            }
        }
    </style>
@endpush

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
                            @if ($teacher?->facebook)
                                <a href="{{ $teacher->facebook }}" target="_blank" class="me-2" title="Facebook">
                                    <i class="fab fa-facebook fa-lg"></i>
                                </a>
                            @endif
                            @if ($teacher?->linkedin)
                                <a href="{{ $teacher->linkedin }}" target="_blank" class="me-2" title="LinkedIn">
                                    <i class="fab fa-linkedin fa-lg"></i>
                                </a>
                            @endif
                            @if ($teacher?->twitter)
                                <a href="{{ $teacher->twitter }}" target="_blank" class="me-2" title="Twitter">
                                    <i class="fab fa-twitter fa-lg"></i>
                                </a>
                            @endif
                            @if ($teacher?->instagram)
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
            </div>
            <div style="margin-top: 40px;">
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
    </section>
@endsection
