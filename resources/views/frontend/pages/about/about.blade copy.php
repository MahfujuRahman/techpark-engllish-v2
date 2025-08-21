

    <!-- our_course area start -->
    <section class="our_course_area">
        <div class="container">
            <div class="our_course_area_content" id="our_course_types">

                <!-- our_course_area_title start -->
                <div class="our_course_area_title">
                    <h2 class="area_title">
                        আমাদের কোর্সসমূহ
                    </h2>
                </div>
                <!-- our_course_area_title end -->

                <!-- course_schedule_name start-->
                <div class="course_schedule_name">
                    <ul>
                        <li class="course_type_active">
                            <a href="{{ route('about') }}">সকল কোর্স</a>
                        </li>
                        <li class="course_type_active">
                            @foreach ($course_types as $index => $item)
                                <a href="{{ route('about', ['slug' => $item->id]) }}"
                                    @if ($index > 0) class="ms-5" @endif>
                                    {{ $item->title }}
                                </a>
                            @endforeach
                        </li>
                    </ul>
                </div>
                <!-- course_schedule_name end-->

                <div class="our_course_all_card">

                    @foreach ($courses as $course)
                        <div class="c_card graphic_designer">
                            <!-- card_img start -->
                            <a href="{{ route('course_details', $course->slug) }}" class="card_img_area">
                                <div class="card_img">
                                    <img src="{{ $course->image }}" alt="graphic_designer, tech park it" />
                                </div>
                            </a>
                            <!-- card_img end -->

                            <!-- card_title_area start -->
                            <div class="card_title_area">
                                <!-- card_title start -->
                                <a href="{{ route('course_details', $course->slug) }}" class="card_title">
                                    <p class="title_text">{{ $course->title }}</p>
                                </a>
                                <!-- card_title end -->

                                <div>
                                    <!-- day_and_boking_area start -->
                                    <div class="day_and_boking_area">
                                        <div class="day_area">
                                            <span class="day_icon">
                                                <img src="{{ asset('frontend') }}/assets/images/home_page_image/our_course_area/clock.png"
                                                    alt="clock, tech park it" />
                                            </span>
                                            @php
                                                $admissionEndDate = isset($course->course_batch[0]->admission_end_date)
                                                    ? \Carbon\Carbon::parse(
                                                        $course->course_batch[0]->admission_end_date,
                                                    )
                                                    : null;
                                            @endphp
                                            <span class="day_tex">
                                                @if ($admissionEndDate && $admissionEndDate->isFuture())
                                                    @php
                                                        $now = \Carbon\Carbon::now();
                                                        $diff = $admissionEndDate->diff($now);
                                                    @endphp
                                                    {{ $diff->d }} দিন
                                                    {{ $diff->h }} ঘন্টা
                                                    {{ $diff->i }} মিনিট
                                                @else
                                                    0 দিন
                                                @endif
                                                বাকী
                                            </span>

                                        </div>
                                        <div class="boking_area">
                                            <span class="boking_text">
                                                {{ $course->course_batch[0]->booked_percent ?? 0 }}%
                                            </span>
                                            <span class="boking_text">
                                                বুকড
                                            </span>
                                        </div>
                                    </div>
                                    <!-- day_and_boking_area end -->

                                    <!-- amount_and_button_area start -->
                                    <div class="amount_and_button_area">
                                        <!-- all_amount area start -->
                                        <div class="all_amount">
                                            <div class="previous_amount_area">
                                                <p class="previous_amount">
                                                    <span class="taka"> ৳ </span>
                                                    <span class="taka">
                                                        {{ $course->course_batch[0]->course_price ?? 0 }}</span>
                                                </p>
                                            </div>
                                            <div class="current_amount_area">
                                                <p class="current_amount">
                                                    <span class="taka"> ৳ </span>
                                                    <span class="taka">
                                                        {{ $course->course_batch[0]->after_discount_price ?? 0 }}</span>
                                                </p>
                                            </div>
                                        </div>
                                        <!-- all_amount area end -->

                                        <!-- button_area start -->
                                        <a href="{{ route('course_details', $course->slug) }}" class="button_all">
                                            <span class="btn-text">কোর্সটি দেখি</span>
                                            <span class="btn_icon">
                                                <i class="fa-solid fa-arrow-right"></i>
                                            </span>
                                        </a>
                                        <!-- button_area end-->
                                    </div>
                                    <!-- amount_and_button_area end -->
                                </div>
                            </div>
                            <!-- card_title_area end -->
                        </div>
                    @endforeach

                </div>


                <div class="course_paginate_btns">
                    {{ $courses->links() }}
                </div>

            </div>

        </div>
    </section>

    <!-- our_course area end -->


    

    <!-- trainers area starts -->
    <section class="trainers_area">
        <div class="container">
            <div class="trainers_description">
                <div class="trainers_title">
                    <h2 class="trainers_title_bangla">{{ $website_about->our_trainers_heading }}</h2>
                    {{-- <h2 class="trainers_title_bangla">{{ $website_about->our_trainers_heading }}</h2> --}}
                </div>
                <div class="trainers_details">
                    @foreach ($teachers as $teacher)
                        <div class="trainer_details">
                            <div class="trainer_images">
                                <div class="image">
                                    <img src="{{ $teacher->instructor->photo }}" alt="">
                                </div>
                                <div class="trainer_info">
                                    <div class="trainer_name">{{ $teacher->full_name }}</div>
                                    <p>{{ $teacher->designation }}</p>
                                    <div class="trainer_link">
                                        <i class="fa-brands tw fa-square-twitter"></i>
                                        <i class="fa-brands fb fa-square-facebook"></i>
                                        <i class="fa-brands ld fa-linkedin"></i>
                                        <i class="fa-brands in fa-square-instagram"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="trainer_descrip">
                                <p>
                                    {{ $teacher->short_description }}
                                </p>
                            </div>
                            <div class="profational_trainer_button_area mt-4">
                                <a href="{{ route('teacher.details', ['teacher_name' => str_replace(' ', '-', $teacher->full_name), 'slug' => $teacher->slug]) }}"
                                    class="button_all">
                                    <span class="btn_text">বিস্তারিত দেখুন</span>
                                    <span class="btn_icon"><i class="fa-solid fa-arrow-right"></i></span>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>
    </section>
    <!-- /trainers area ends -->

    <!-- hire section start -->
    <section class="hire_area">
        <div class="container">
            <div class="hire_details">
                <div class="hire_title">
                    <h2 class="hire_title_bangla">আপনার কোন প্রজেক্টের জন্য আমাদেরকে Hire করুন</h2>
                    <p>
                        আপনার কোন প্রজেক্ট থাকলে আমাদেরকে হারার করতে পারেন, আমাদের রয়েছে কমপ্লিট ফ্রন্টএন্ড ও ব্যাকএন্ড টিম
                        ও এসইও স্পেসালিস্ট
                    </p>
                </div>
                <div class="hire_btn">Hire us</div>
            </div>
        </div>
    </section>
    <!-- /hire section end -->
@endsection
