<section class="student_success_history_area">
    <div class="container">
        <div class="student_success_history_content">

            <!--student_success_history_area_title start -->
            <div class="student_success_history_area_title">
                <h2 class="area_title">
                    Success Stories
                </h2>
            </div>
            <!-- student_success_history_area_title end -->

            <!-- student_success_history_area_sub_title start -->
            <div class="student_success_history_area_sub_title">
                <span class="sub_title">
                    Our Students Performance - See how our students have succeeded after completing their courses at
                    TechPark English.
                </span>
            </div>
            <!-- student_success_history_area_sub_title end -->

            <!-- student_success_video_area start -->
            <div class="student_success_video_area">
                @foreach ($success_stories as $story)
                    <button class="success_video_area" onclick="showVideo(`{{ $story->video_link }}`)">
                        <div class="success_video_cover_photo">
                            <img src="{{ assetHelper(optional($story)->thumbnail_image) }}" alt="video_img tech park it"
                                loading="lazy">
                        </div>
                        <div class="video_button_icon">
                            <i class="fa-solid fa-play"></i>
                        </div>
                    </button>
                @endforeach

            </div>
            <!-- student_success_video_area end -->

            <!-- student_success_history_area_button start-->
            @if (request()->routeIs('stories'))
                <div class="mr-paginate d-flex justify-content-center">
                    {{ $success_stories->links() }}
                </div>
            @else
                <div class="success_history_button_area">
                    <a href="{{ route('stories') }}" class="button_all">
                        <span class="btn_text">আরও দেখুন</span>
                        <span class="btn_icon"><i class="fa-solid fa-arrow-right"></i></span>
                    </a>
                </div>
            @endif

            <div id="story_modal" class="modal fade modal-xl" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body">
                        </div>
                    </div>
                </div>
            </div>
            <!-- student_success_history_area_button end-->

        </div>
    </div>
</section>

<script src="{{ asset('js/frontend.js') }}"></script>
