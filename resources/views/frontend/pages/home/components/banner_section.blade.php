<section class="banner_part ">
    <div class="container ">
        <div class="owl-carousel">
            @foreach ($banners as $banner)
                <div class="py-3">
                    <img src="{{ $banner->image }}" loading="lazy" class="rounded rounded-sm" alt="techpark english banner"
                        style="width: 100%;">
                </div>
            @endforeach
        </div>
    </div>
</section>
