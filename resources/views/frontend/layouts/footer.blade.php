<!-- footer area start -->
<footer class="footer_area_start">
    <div class="container">
        <div class="footer_area_content">
            <div class="row">

                <!-- footer_logo_and_contact_area start -->
                <div class="col-xs-12 col-xl-3">
                    <div class="footer_colum footer_logo_and_contact_area">
                        <!-- footer_logo area start -->
                        <div class="footer_logo_area">
                            <a href="/">
                                <img style="width: 90%; class="rounded rounded-sm" src="{{ assetHelper(setting('image')) }}" alt="logo"
                                    loading="lazy">
                            </a>
                        </div>
                        <!-- footer_logo area end -->

                        <!-- footer_contact_area start -->
                        <div class="footer_contact_area">

                            <!-- contact_title start -->
                            <div class="contact_title">
                                <p class="title_text">যোগাযোগ করুন</p>
                            </div>
                            <!-- contact_title end -->

                            <!-- contact_number_and_email_area start -->
                            <ul class="contact_number_and_email_area">
                                <li>
                                    <div href="#" class="contact align-items-start">
                                        <div class="logo phone">
                                            <i class="fa-solid fa-phone"></i>
                                        </div>
                                        <div class="number">
                                            @php
                                                $phone_numbers = setting('phone_numbers', true);
                                                // If $phone_numbers is an array with 'setting_values', extract that
                                                if (
                                                    is_array($phone_numbers) &&
                                                    isset($phone_numbers[0]['setting_values'])
                                                ) {
                                                    $phone_numbers = $phone_numbers[0]['setting_values'];
                                                }
                                            @endphp
                                            @foreach ($phone_numbers as $item)
                                                @php
                                                    $phone_number = is_array($item) ? $item['value'] ?? '' : $item;
                                                @endphp
                                                <a href="tel:{{ $phone_number }}" class="text">
                                                    {{ $phone_number }} </a> <br>
                                            @endforeach
                                        </div>
                                    </div>
                                </li>

                                <li>
                                    <a href="https://wa.me/{{ setting(key: 'whatsapp') }}"
                                        class="contact align-items-start">
                                        <div class="logo whatsapp">
                                            <i class="fa-brands fa-square-whatsapp"></i>
                                        </div>
                                        <div class="number">
                                            <p class="text">{{ setting(key: 'whatsapp') }}</p>
                                        </div>
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ setting(key: 'telegram') }}" class="contact align-items-start">
                                        <div class="logo telegram">
                                            <i class="fa-brands fa-telegram"></i>
                                        </div>
                                        <div class="number">
                                            <p class="text">Join Telegram </p>
                                        </div>
                                    </a>
                                </li>


                                @php
                                    $emails = setting('emails', true);
                                    // If $emails is an array with 'setting_values', extract that
                                    if (is_array($emails) && isset($emails[0]['setting_values'])) {
                                        $emails = $emails[0]['setting_values'];
                                    }
                                @endphp
                                @foreach ($emails as $item)
                                    @php
                                        $email = is_array($item) ? $item['value'] ?? '' : $item;
                                    @endphp
                                    <li>
                                        <a href="mailto:{{ $email }}" class="contact align-items-start">
                                            <div class="logo email">
                                                <i class="fa-regular fa-envelope"></i>
                                            </div>
                                            <div class="number email_address">
                                                <p class="text">{{ $email }}</p>
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                            <!-- contact_number_and_email_area end -->

                            <!-- social_media_area start -->
                            <div class="social_media_area">
                                <div class="social_media_title">
                                    <p class="text">আমাদের সোসাল মিডিয়া লিংক</p>
                                </div>

                                <div class="social_media">
                                    <ul>
                                        <li>
                                            <a href="{{ setting(key: 'facebook') }}" class="facebook">
                                                <i class="fa-brands fa-square-facebook"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ setting(key: 'instagram') }}" class="instagram">
                                                <i class="fa-brands fa-square-instagram"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ setting(key: 'youtube') }}" class="youtube">
                                                <i class="fa-brands fa-youtube"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ setting(key: 'linkedin') }}" class="linkedin-in">
                                                <i class="fa-brands fa-linkedin-in"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ setting(key: 'twitter') }}" class="twitter">
                                                <i class="fa-brands fa-twitter"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <!-- social_media_area end -->

                        </div>
                        <!-- footer_contact_area end -->
                    </div>
                </div>
                <!-- footer_logo_and_contact_area end -->

                <!-- footer_blog_area start -->
                <div class="col-xs-12 col-md-8 col-xl-6">
                    <div class="footer_colum footer_blog_area">

                        <!-- blog_title_and-item_area start -->
                        <div class="blog_title_and-item_area">
                            <div class="row">
                                <div class="col-xs-12 col-md-6">

                                    <!-- quick_link_title start -->
                                    <div class="quick_and_importent_link_title">
                                        <div class="logo">
                                            <i class="fa-solid fa-link"></i>
                                        </div>
                                        <p class="title_text">কুইক লিংক</p>
                                    </div>
                                    <!-- quick_link_title end -->

                                    <!-- blog_item start -->
                                    <ul class="blog_item">
                                        <li>
                                            <a href="#">
                                                <span class="text"> ব্লগ</span>
                                                <div class="icon">
                                                    <i class="fa-solid fa-arrow-right"></i>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('refund.policy') }}">
                                                <span class="text"> রিফান্ড পলিসি</span>
                                                <div class="icon">
                                                    <i class="fa-solid fa-arrow-right"></i>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('affiliation.policy') }}">
                                                <span class="text">এফিলিয়েশন</span>
                                                <div class="icon">
                                                    <i class="fa-solid fa-arrow-right"></i>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <span class="text"> ক্যারিয়ার</span>
                                                <div class="icon">
                                                    <i class="fa-solid fa-arrow-right"></i>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <span class="text"> শিক্ষক হিসেবে জয়েন করুন</span>
                                                <div class="icon">
                                                    <i class="fa-solid fa-arrow-right"></i>
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                    <!-- blog_item running -->
                                </div>

                                <div class="col-xs-12 col-md-6">

                                    <!-- importent_link_title start -->
                                    {{-- <div class="quick_and_importent_link_title">
                                            <div class="logo">
                                                <i class="fa-solid fa-link"></i>
                                            </div>
                                            <p class="title_text">জরুরী লিংক</p>
                                        </div> --}}
                                    <!-- importent_link_title end -->

                                    <!-- blog_item running -->
                                    <ul class="blog_item">
                                        {{-- <li>
                                                <a href="#">
                                                    <span class="text"> সার্টিফিকেট ভেরিফাই</span>
                                                    <div class="icon">
                                                        <i class="fa-solid fa-arrow-right"></i>
                                                    </div>
                                                </a>
                                            </li> --}}
                                        <li>
                                            <a href="{{ route('terms.policy') }}">
                                                <span class="text"> টার্মস এন্ড কন্ডিশন</span>
                                                <div class="icon">
                                                    <i class="fa-solid fa-arrow-right"></i>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('cookies.policy') }}">
                                                <span class="text"> কুকিস পলিসি</span>
                                                <div class="icon">
                                                    <i class="fa-solid fa-arrow-right"></i>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('privacy.policy') }}">
                                                <span class="text"> প্রাইভেসি পলিসি</span>
                                                <div class="icon">
                                                    <i class="fa-solid fa-arrow-right"></i>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('sitemap.policy') }}">
                                                <span class="text">সাইট ম্যাপ</span>
                                                <div class="icon">
                                                    <i class="fa-solid fa-arrow-right"></i>
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                    <!-- blog_item end -->
                                </div>
                            </div>
                        </div>
                        <!-- blog_item_area end -->
                    </div>
                </div>
                <!-- footer_blog_area end -->


                <!-- footer_address_area start -->
                <div class="col-xs-12 col-md-4 col-xl-3">
                    <div class="footer_colum footer_address_area">

                        <!-- address_title_area start -->
                        <div class="address_title_area">
                            <div class="logo">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>
                            <div class="title">
                                <p class="text">ঠিকানা</p>
                            </div>
                        </div>
                        <!-- address_title_area end -->

                        <!-- address start -->
                        <div class="address">
                            <p class="text">{{ setting(key: 'address_bangla') }}</p>
                        </div>
                        <!-- address end -->

                        <!-- map_area start -->
                        <div class="map_area">
                            <div class="map_title">
                                <p class="text">ম্যাপে দেখুন</p>
                            </div>

                            <div class="full_map">
                                {!! setting(key: 'map_link') !!}
                                {{-- <iframe
                                        src="{{setting(key:'map_link')}}"
                                        width="100%" height="" style="border:0;" allowfullscreen=""
                                        loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe> --}}
                            </div>
                        </div>
                        <!-- map_area end -->

                    </div>
                </div>
                <!-- footer_address_area end -->
            </div>
        </div>
    </div>

    <!-- footer_copyright_area start -->
    <div class="footer_copyright_area">
        <p class="text">{{ setting(key: 'copy_right') }}</p>
    </div>
    <!-- footer_copyright_area end -->
</footer>
<!-- footer area end -->
