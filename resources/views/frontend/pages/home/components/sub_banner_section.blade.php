<section class="course_item">
    <div class="container">
        <div class="course_item_content">
            @foreach ($subBanners as $key => $cat)
                <div class="course item_{{ $key + 1 }}">
                    <img class="rounded rounded-sm" src="{{ assetHelper(optional($cat)->image) }}"
                        alt="techpark english sub-banner" loading="lazy" style="max-height: 210px; width: 100%;">
                </div>
            @endforeach
        </div>
    </div>
</section>
