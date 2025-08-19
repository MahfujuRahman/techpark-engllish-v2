<section class="banner_part ">
    <div class="container ">
        <div class="owl-carousel">
            @foreach ($banners as $banner)
                <div class="py-3">
                    <img class="rounded rounded-sm" src="{{ asset_or_default(optional($banner)->image) }}"
                        alt="techpark english banner" style="width: 100%;" loading="lazy">
                </div>
            @endforeach
        </div>
    </div>
</section>
