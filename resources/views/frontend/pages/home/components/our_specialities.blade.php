<section class="our_course_specialty" {{-- style="background-image: url(http://techparkit.org/frontend/assets/images/home_page_image/our_course_specialty/bg_img.png);" --}} style="background: #43a5c9">
    <div class="container">
        <div class="our_course_specialty_area_content">

            <!--our_course_specialty_area_title start -->
            <div class="our_course_specialty_area_title">
                <h2 class="area_title">
                    OUR SPECIALITIES IN COURSES
                </h2>
            </div>
            <!-- our_course_specialty_area_title end -->

            <!-- course_card_area start -->
            <div class="owl-carousel course_speciality">
                @foreach ($our_speciality as $item)
                    <div class="c_card">
                        <div class="card_img_area">
                            <img src="{{ asset($item->image) }}" alt="card tech park it">
                        </div>
                    </div>
                @endforeach
            </div>
            <!-- course_card_area end -->
        </div>
    </div>
</section>
<script>
    $('.owl-carousel.course_speciality').owlCarousel({
        loop: true,
        margin: 10,
        nav: true,
        autoplay: true,
        autoplayTimeout: 2000, // 1 second
        responsive: {
            0: {
                items: 1
            },
            800: {
                items: 2
            },
            1000: {
                items: 3
            }
        }
    });
</script>
