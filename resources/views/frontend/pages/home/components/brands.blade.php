<section class="working_company_name_area" id="brands">
    <div class="working_company_name_area_content">

        <!--working_company_name_area_title start -->
        <div class="working_company_name_area_title">
            <h2 class="area_title">
                Brands We Work With
            </h2>
        </div>
        <!-- -working_company_name_area_title end -->

        <!-- all_company_name and logo area start -->
        <div class="all_company_name">
            <div class="brand-slider" aria-hidden="false">
                <div class="brand-track">
                    <ul class="brand-list">
                        @foreach ($brands as $brand)
                            <li class="company_logo">
                                <img style="width: 100%" class="rounded rounded-sm" src="{{ assetHelper(optional($brand)->image) }}"
                                    alt="{{ $brand->title }}" loading="lazy">
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <style>
            /* Simple infinite horizontal slider for brand logos */
            .brand-slider { overflow: hidden; width: 100%; }
            .brand-track { display: flex; align-items: center; will-change: transform; }
            .brand-list { display: flex; gap: 18px; margin: 0; padding: 0; list-style: none; }
            .brand-list .company_logo { flex: 0 0 auto; width: 140px; }
            .brand-list .company_logo img { display: block; width: 100%; height: auto; }

            /* JS-driven continuous scroll; no CSS keyframes to avoid jump */

            /* reduce speed on small screens */
            @media (max-width: 576px) {
                .brand-list .company_logo { width: 90px; }
            }
        </style>

        <script>
            // Duplicate the brand list to create a seamless infinite loop and start CSS animation
            document.addEventListener('DOMContentLoaded', function () {
                try {
                    var track = document.querySelector('.brand-track');
                    var list = track.querySelector('.brand-list');
                    // clone the list and append
                    var clone = list.cloneNode(true);
                    clone.setAttribute('aria-hidden', 'true');
                    clone.style.pointerEvents = 'none';
                    track.appendChild(clone);

                    // measure width of one copy
                    function measure() {
                        return list.getBoundingClientRect().width;
                    }

                    var totalWidth = measure();
                    if (!totalWidth || totalWidth < 50) totalWidth = list.scrollWidth || 1000;

                    // pixels per second speed (adjustable)
                    var pxPerSec = 80;

                    var offset = 0;
                    var lastTime = null;

                    function step(timestamp) {
                        if (!lastTime) lastTime = timestamp;
                        var dt = (timestamp - lastTime) / 1000; // seconds
                        lastTime = timestamp;

                        // move left
                        offset -= pxPerSec * dt;

                        // reset offset to avoid large negative values and keep seamless loop
                        if (Math.abs(offset) >= totalWidth) {
                            offset += totalWidth;
                        }

                        track.style.transform = 'translate3d(' + offset + 'px, 0, 0)';
                        requestAnimationFrame(step);
                    }

                    // start animation after next paint to ensure sizes are correct
                    requestAnimationFrame(function () {
                        totalWidth = measure() || totalWidth;
                        requestAnimationFrame(step);
                    });

                    // pause on hover for accessibility
                    track.addEventListener('mouseenter', function () { pxPerSec = 0; });
                    track.addEventListener('mouseleave', function () { pxPerSec = 80; });

                } catch (e) {
                    console.error('Brand slider init error', e);
                }
            });
        </script>
        <!-- all_company_name and logo area end -->
    </div>
</section>
