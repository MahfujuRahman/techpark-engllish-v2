@php
    $meta = [
        'seo' => [
            'title' => 'contact',
            'image' => asset('seo.jpg'),
        ],
    ];
@endphp
@extends('frontend.layouts.layout', $meta)
@section('contents')
    <!-- contact area start -->
    <section class="contact_area">
        <div class="container">
            <div class="contact_area_content">
                <div class="title">
                    <!--contact_area title start -->
                    <div class="contact_area_title">
                        <h2 class="area_title">
                            Contact With Us
                        </h2>
                    </div>
                    <!-- contact_area title end -->

                    <!-- contact_area sub_title start -->
                    <div class="contact_area_sub_title">
                        <span class="sub_title">
                            You can visit our office directly for any needs. You may also call us to know any information
                            regarding our courses. Additionally, you can contact us via the mentioned email or Facebook
                            Messenger.
                        </span>
                    </div>
                    <!-- contact_area sub_title end -->
                </div>
                <!-- title end -->

                <!-- phone_number_and_form_area start -->
                <div class="phone_number_and_form_area">
                    <div class="row">
                        @include('frontend.pages.contact.includes.contact')

                        @include('frontend.pages.contact.includes.form')
                    </div>
                </div>
                <!-- phone_number_and_form_area end -->

                <div class="map_area">
                    @include('frontend.pages.contact.includes.map')
                </div>

            </div>

            @include('frontend.pages.contact.includes.general_qs')

        </div>
    </section>
    <!-- contact area end -->
@endsection
