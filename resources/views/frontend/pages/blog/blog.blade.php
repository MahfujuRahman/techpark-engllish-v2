@php
    $meta = [
        'seo' => [
            'title' => 'blog',
            'image' => asset('seo.jpg'),
        ],
    ];
@endphp

@extends('frontend.layouts.layout', $meta)
@section('contents')

    <!-- blog area start -->
    <div class="science_and_technology">
        <div class="container">
            <!-- science_and_technology_content start -->
            <div class="science_and_technology_content">
                <div class="science_and_technology_text_area">
                    <div class="title">
                        <p class="text">{{ $blog_single->category->title ?? '' }}</p>
                    </div>
                    <div class="content_text">
                        <h2 class="text">{{ $blog_single?->title }}</h2>
                    </div>
                    <div class="sub_content_text_and_button">
                        <div class="sub_content_text">
                            <p class="text">{{ \Str::limit(strip_tags($blog_single?->description), 200) }}</p>
                        </div>
                        <a href="{{ route('blog_details', $blog_single->slug) }}" class="button_all">
                            Read More
                        </a>
                    </div>
                </div>
                <div class="science_and_technology_img_area">
                    <img class="blog_img ounded rounded-sm" src="{{ assetHelper(optional($blog_single)->images) }}"
                        alt="{{ $blog_single->title }}" loading="lazy">

                    <img class="color_image_left" src="{{ asset('frontend') }}/assets/images/blog_page_image/color1.png"
                        alt="tech park it" loading="lazy">

                    <img class="color_image_right" src="{{ asset('frontend') }}/assets/images/blog_page_image/color2.png"
                        alt="tech park it" loading="lazy">
                </div>
            </div>
            <!-- science_and_technology_content end -->

            <!-- blog nav bar area start -->
            <div class="blog_nav">
                <ul>
                    <li>
                        <a href="{{ route('blog') }}">সব</a>
                    </li>
                    @foreach ($blog_categories as $category)
                        <li>
                            <a href="{{ route('blog', ['category' => $category->slug]) }}">{{ $category->title }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <!-- blog nav bar area end -->

            <!-- blog list area start -->
            <div class="blog_list">
                <!-- blog start -->
                @if ($blogs->count() > 0)
                    @foreach ($blogs as $blog)
                        <div class="blog">
                            <div class="blog_image_and_image_button_area">
                                <div class="blog_image">
                                    <img class="blog_img ounded rounded-sm"
                                        src="{{ assetHelper(optional($blog)->images) }}" alt="{{ $blog->title }}"
                                        loading="lazy">
                                </div>
                            </div>

                            <div class="blog_text_content">
                                <div class="day_and_time">
                                    <span class="day_text">{{ $blog->created_at->diffForHumans() }} | </span>
                                    <span class="min_text">{{ rand(6, 15) }} min. to read </span>
                                </div>
                                <div class="text_title">
                                    <a href="{{ route('blog_details', $blog->slug) }}">
                                        <h2 class="title">{{ $blog->title }}</h2>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="no_blogs_available">
                        <p>No blogs available at the moment. Please check back later!</p>
                    </div>
                @endif
                <!-- blog end -->
            </div>
            <!-- blog list area end -->

            <!-- next_page_button_area start -->
            <div class="next_page_button_area">
                {{ $blogs->links() }}
            </div>
            <!-- next_page_button_area end -->

            <!-- subscribe_area_start -->
            <section class="subscribe_area">
                <div class="subscribe_area_title">
                    <h2 class="title">Subscribe for blog updates</h2>
                </div>
                <div class="subscribe_area_sub_title">
                    <p class="sub_title">Subscribe to our newsletter for regular updates on new blogs.</p>
                </div>
                <!-- subscribe_form_area start -->
                <form action="#">
                    <div class="subscribe_form_area">
                        <input type="text" placeholder="mail@yourmail.com">
                        <button type="button" class="subscribe_button">Subscribe Us</button>
                    </div>
                </form>
                <!-- subscribe_form_area end -->
            </section>
            <!-- subscribe_area_end -->
        </div>
    </div>
    <!-- blog area end -->
@endsection
