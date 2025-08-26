@php
    $meta = [
        // "meta" => [],
        'seo' => [
            'title' => 'Refund Policy',
            'image' => asset('seo.jpg'),
        ],
    ];
@endphp
@extends('frontend.layouts.layout', $meta)
@section('contents')
    <style>
        .divider:after,
        .divider:before {
            content: "";
            flex: 1;
            height: 1px;
            background: #eee;
        }
    </style>
    <section class="py-5 my-5">
        <div class="container h-custom">
            <div class="content">
                <h2 class="text-center">Refund Policy</h2>

                <div class="mt-4">
                    {!! $website_about->description !!}
                </div>

            </div>
        </div>
    </section>
@endsection
