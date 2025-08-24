<div class="col-xs-12 col-md-6">
    <div class="left_area">
        <!-- contact_title start -->
        <div class="contact_title">
            <h2 class="title_text">Contact Us</h2>
        </div>
        <!-- contact_title end -->

        <!-- contact_number_and_email_area start -->
        <ul class="contact_number_and_email_area" id="jojajog_number">

            <div class="number">
                @php
                    $phone_numbers = setting('phone_numbers', true);
                    if (is_array($phone_numbers) && isset($phone_numbers[0]['setting_values'])) {
                        $phone_numbers = $phone_numbers[0]['setting_values'];
                    }
                @endphp
                @foreach ($phone_numbers as $item)
                    @php
                        $phone_number = is_array($item) ? $item['value'] ?? '' : $item;
                    @endphp

                    <li>
                        <a href="tel:{{ $phone_number }}" class="contact">
                            <div class="logo email">
                                <i class="fa-solid fa-mobile-screen-button"></i>
                            </div>
                            <div class="number email_address">
                                <p class="text"> {{ $phone_number }} </p>
                            </div>
                        </a>
                    </li>
                @endforeach
            </div>


            <li>
                <a href="https://wa.me/{{ setting(key: 'whatsapp') }}" target="_blank" class="contact">
                    <div class="logo whatsapp">
                        <i class="fa-brands fa-square-whatsapp"></i>
                    </div>
                    <div class="number">
                        <p class="text"> {{ setting(key: 'whatsapp') }} </p>
                    </div>
                </a>
            </li>

            <li>
                <a href="https://t.me/{{ setting('telegram') }}" target="_blank" class="contact">
                    <div class="logo telegram">
                        <i class="fa-brands fa-telegram"></i>
                    </div>
                    <div class="number">
                        <p class="text"> Join Telegram </p>
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
                    <a href="mailto:{{ $email }}" target="_blank" class="contact">
                        <div class="logo email">
                            <i class="fa-regular fa-envelope"></i>
                        </div>
                        <div class="number email_address">
                            <p class="text"> {{ $email }} </p>
                        </div>
                    </a>
                </li>
            @endforeach
        </ul>
        <!-- contact_number_and_email_area end -->

        <!-- social_media_area start -->
        <div class="social_media_area">
            <div class="social_media_title">
                <h2 class="text">আমাদের সোসাল মিডিয়া লিংক</h2>
            </div>

            <div class="social_media">
                <ul>
                    <li>
                        <a href="{{ setting(key: 'facebook') }}" target="_blank" class="facebook">
                            <i class="fa-brands fa-square-facebook"></i>
                        </a>
                    </li>
                    <li>
                        <a href="{{ setting(key: 'instagram') }}" target="_blank" class="instagram">
                            <i class="fa-brands fa-square-instagram"></i>
                        </a>
                    </li>
                    <li>
                        <a href="{{ setting(key: 'youtube') }}" target="_blank" class="youtube">
                            <i class="fa-brands fa-youtube"></i>
                        </a>
                    </li>
                    <li>
                        <a href="{{ setting(key: 'linkedin') }}" target="_blank" class="linkedin-in">
                            <i class="fa-brands fa-linkedin-in"></i>
                        </a>
                    </li>
                    <li>
                        <a href="{{ setting(key: 'twitter') }}" target="_blank" class="twitter">
                            <i class="fa-brands fa-twitter"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- social_media_area end -->
    </div>
</div>
