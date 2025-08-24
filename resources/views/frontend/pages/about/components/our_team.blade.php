@push('styles')
    <!-- Owl Carousel CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" />

    <style>
        /* Team section styling */
        .team_area {
            padding: 80px 0;
            background: #ffffff;
            position: relative;
        }

        .team_area::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, #e1e5e9, transparent);
        }

        .team_area_title_bangla {
            font-size: 42px;
            margin-bottom: 16px;
            color: #1a202c;
            font-weight: 800;
            text-align: center;
            position: relative;
        }

        .team_area_title_bangla::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 4px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 2px;
        }

        .team_area_details p {
            color: #4a5568;
            line-height: 1.7;
            margin-bottom: 50px;
            font-size: 17px;
            text-align: center;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }

        .team-area-carousel {
            position: relative;
            max-width: 1000px;
            margin: 0 auto;
        }

        .carousel-main-image {
            width: 100%;
            height: 500px;
            margin-bottom: 40px;
            border-radius: 20px;
            overflow: hidden;
            position: relative;
            background: #f7fafc;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.12);
            border: 1px solid #e2e8f0;
        }

        .carousel-main-image::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
            z-index: 1;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .carousel-main-image:hover::before {
            opacity: 1;
        }

        .carousel-main-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: all 0.4s ease;
            position: relative;
            z-index: 2;
        }

        .carousel-main-image:hover img {
            transform: scale(1.03);
        }

        /* Enhanced Owl Carousel Styling - Clean design for all devices */
        .carousel-thumbnails {
            background: #f8fafc;
            border-radius: 16px;
            padding: 15px;
            /* Consistent padding across devices */
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
            border: 1px solid #e2e8f0;
        }

        .carousel-thumbnails.owl-carousel {
            margin: 0;
        }

        .carousel-thumbnails .owl-stage-outer {
            border-radius: 12px;
            overflow: hidden;
        }

        .carousel-thumbnails .owl-nav {
            display: none !important;
            /* Hide navigation buttons on all devices */
        }

        .carousel-thumbnails .owl-nav button {
            display: none !important;
            /* Ensure buttons are completely hidden */
        }

        .carousel-thumb-item {
            padding: 6px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 85px;
            /* Consistent height across devices */
        }

        .carousel-thumb-item img {
            width: 100%;
            height: 70px;
            /* Consistent height across devices */
            object-fit: cover;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: 3px solid transparent;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            position: relative;
            display: block;
        }

        .carousel-thumb-item img::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(102, 126, 234, 0.2), rgba(118, 75, 162, 0.2));
            opacity: 0;
            transition: opacity 0.3s ease;
            border-radius: 9px;
        }

        .carousel-thumb-item img:hover {
            transform: translateY(-4px) scale(1.08);
            border-color: #667eea;
            box-shadow: 0 12px 30px rgba(102, 126, 234, 0.3);
        }

        .carousel-thumb-item img:hover::before {
            opacity: 1;
        }

        .carousel-thumb-item img.active {
            border-color: #e53e3e;
            box-shadow: 0 8px 25px rgba(229, 62, 62, 0.4);
            transform: translateY(-2px);
        }

        /* Loading animation */
        .carousel-main-image img {
            opacity: 1;
            transition: opacity 0.3s ease, transform 0.4s ease;
        }

        /* Enhanced Owl Carousel Item Styling - Consistent across devices */
        .carousel-thumbnails .owl-item {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 85px;
            /* Same as carousel-thumb-item */
        }

        .carousel-thumbnails .owl-stage {
            display: flex;
            align-items: center;
        }

        .carousel-thumbnails .owl-item .carousel-thumb-item {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .owl-carousel .owl-item img {
            display: block;
            width: 100%;
            height: unset;
            object-fit: cover;
        }

        /* Responsive Design - Consistent clean design across all devices */
        @media (max-width: 1024px) {
            .team-area-carousel {
                max-width: 95%;
                margin: 0 auto;
            }
        }

        @media (max-width: 768px) {
            .team_area {
                padding: 60px 0;
            }

            .team_area_title_bangla {
                font-size: 32px;
            }

            .team_area_details p {
                font-size: 16px;
                margin-bottom: 40px;
            }

            .carousel-main-image {
                height: 350px;
                margin-bottom: 30px;
                border-radius: 16px;
            }
        }

        @media (max-width: 480px) {
            .team_area {
                padding: 40px 0;
            }

            .team_area_title_bangla {
                font-size: 28px;
            }

            .carousel-main-image {
                height: 280px;
                border-radius: 12px;
            }

            .carousel-thumbnails {
                padding: 12px;
            }
        }
    </style>
@endpush



<!-- team area start -->
<section class="team_area bg_banner_area_right">
    <div class="container">
        <div class="team_area_details">
            <div class="team_area_title">
                <h2 class="team_area_title_bangla animate-text">{{ $our_team->title }}</h2>
                <p>
                    {!! $our_team->description !!}
                </p>
            </div>
            <div class="team-area-carousel">
                <!-- Main Image Display -->
                <div class="carousel-main-image">
                    <img id="mainTeamImage" src="{{ assetHelper(optional($team_top_image)->image) }}"
                        alt="{{ $team_top_image->title }}" loading="lazy">
                </div>

                <!-- Thumbnails Carousel -->
                <div class="carousel-thumbnails owl-carousel">
                    @foreach ($team_related_image as $single_image)
                        <div class="carousel-thumb-item">
                            <img src="{{ assetHelper(optional($single_image)->image) }}"
                                alt="{{ $single_image->title }}" loading="lazy" onclick="changeMainImage(this)">
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</section>
<!-- /team area end -->

@push('scripts')
    <script>
        let isTransitioning = false;

        function changeMainImage(el) {
            if (isTransitioning) return;

            const mainImage = document.getElementById('mainTeamImage');
            const currentSrc = mainImage.src;
            const newSrc = el.src;

            // Don't change if it's the same image
            if (currentSrc === newSrc) return;

            isTransitioning = true;

            // Enhanced fade effect with smooth transition
            mainImage.style.transition = 'opacity 0.3s cubic-bezier(0.4, 0, 0.2, 1), transform 0.3s ease';
            mainImage.style.opacity = '0';
            mainImage.style.transform = 'scale(0.98)';

            setTimeout(() => {
                mainImage.src = newSrc;
                mainImage.alt = el.alt;

                // Fade back in with scale
                setTimeout(() => {
                    mainImage.style.opacity = '1';
                    mainImage.style.transform = 'scale(1)';

                    setTimeout(() => {
                        isTransitioning = false;
                    }, 300);
                }, 50);
            }, 200);

            // Update active states with smooth animation
            $('.carousel-thumb-item img').removeClass('active');
            $(el).addClass('active');
        }

        function destroyAndReinitCarousel() {
            const $carousel = $('.carousel-thumbnails');

            // Destroy Owl if already initialized
            if ($carousel.hasClass('owl-loaded')) {
                $carousel.trigger('destroy.owl.carousel');
                $carousel.removeClass('owl-loaded owl-carousel');

                // Remove leftover DOM wrappers from OwlCarousel
                $carousel.find('.owl-stage-outer').children().unwrap();
                $carousel.find('.owl-item').children().unwrap();
                $carousel.find('.owl-stage-outer').remove();
            }

            // Re-add .owl-carousel for init
            $carousel.addClass('owl-carousel');

            // Enhanced Owl Carousel configuration - No navigation buttons
            $carousel.owlCarousel({
                autoplay: true,
                autoplayTimeout: 4000, // Slightly longer for better UX
                autoplayHoverPause: true,
                smartSpeed: 1000, // Smoother transitions
                loop: true,
                margin: 12,
                nav: false, // Disable navigation buttons
                dots: false,
                mouseDrag: true, // Allow mouse dragging
                touchDrag: true, // Allow touch dragging
                animateOut: 'fadeOut',
                animateIn: 'fadeIn',
                responsive: {
                    0: {
                        items: 2,
                        margin: 8
                    },
                    480: {
                        items: 3,
                        margin: 10
                    },
                    600: {
                        items: 4,
                        margin: 10
                    },
                    768: {
                        items: 5,
                        margin: 12
                    },
                    1000: {
                        items: 6,
                        margin: 15
                    }
                }
            });

            // Enhanced auto-change functionality
            let autoChangeTimeout;

            $carousel.on('changed.owl.carousel', function(event) {
                clearTimeout(autoChangeTimeout);

                autoChangeTimeout = setTimeout(() => {
                    const currentIndex = event.item.index;
                    const visibleItems = $(event.target).find(".owl-item:not(.cloned)");

                    if (visibleItems.length > 0) {
                        const targetIndex = currentIndex % visibleItems.length;
                        const currentItem = visibleItems.eq(targetIndex).find("img");

                        if (currentItem.length > 0 && !isTransitioning) {
                            changeMainImage(currentItem[0]);
                        }
                    }
                }, 100);
            });

            // Handle direct thumbnail clicks with better UX
            $carousel.on('click', '.carousel-thumb-item img', function(e) {
                e.preventDefault();
                e.stopPropagation();

                if (!isTransitioning) {
                    // Stop auto-play temporarily
                    $carousel.trigger('stop.owl.autoplay');
                    changeMainImage(this);

                    // Resume auto-play after user interaction
                    setTimeout(() => {
                        $carousel.trigger('play.owl.autoplay');
                    }, 3000);
                }
            });

            // Add loading animation
            $carousel.find('img').each(function() {
                $(this).on('load', function() {
                    $(this).addClass('loaded');
                });
            });
        }

        $(document).ready(function() {
            // Enhanced initialization
            setTimeout(() => {
                const firstThumb = $('.carousel-thumb-item img').first();
                if (firstThumb.length > 0) {
                    firstThumb.addClass('active');
                }

                destroyAndReinitCarousel();

                // Add smooth scroll behavior
                $('html').css('scroll-behavior', 'smooth');
            }, 300);
        });

        // Enhanced page restoration handling
        window.addEventListener('pageshow', function(event) {
            if (event.persisted || (performance.getEntriesByType("navigation")[0]?.type === 'back_forward')) {
                setTimeout(() => {
                    destroyAndReinitCarousel();
                }, 200);
            }
        });

        // Handle window resize for better responsiveness
        let resizeTimeout;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => {
                if ($('.carousel-thumbnails').hasClass('owl-loaded')) {
                    $('.carousel-thumbnails').trigger('refresh.owl.carousel');
                }
            }, 250);
        });
    </script>
@endpush
