<!-- aboutus -->
<section class="aboutus_part aboutus_section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-7 aboutus_text">
                <div class="">
                    <h2 class="aboutus_title_bangla">
                        {{ $AboutUs->title }}
                    </h2>
                </div>
                <div class="aboutus_relation_description">
                    <p class="aboutus_info">
                        {!! $AboutUs->description !!}
                    </p>
                </div>
            </div>
            <div class="col-md-5 aboutus_media">
                <div class="aboutus_image">
                    <img class="rounded rounded-sm" src="{{ assetHelper(optional($AboutUs)->image) }}"
                        alt="techpark english banner" loading="lazy">
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /aboutus -->

<style>
    /* About section visual improvements */
    .aboutus_section {
        padding: 48px 0;
    }

    .aboutus_text {
        padding-right: 30px;
    }

    .aboutus_title_bangla {
        font-size: 2rem;
        margin-bottom: 18px;
        font-weight: 700;
    }

    .aboutus_info {
        font-size: 1rem;
        color: #444;
        line-height: 1.9;
        text-align: justify;
    }

    .aboutus_image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 10px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
    }

    .aboutus_media {
        display: flex;
        justify-content: center;
    }

    /* subtle background band and spacing */
    .aboutus_section {
        background: linear-gradient(180deg, rgba(255, 255, 255, 1) 0%, rgba(250, 250, 250, 1) 100%);
    }

    @media (max-width: 767.98px) {
        .aboutus_text {
            padding-right: 0;
            margin-bottom: 18px;
        }

        .aboutus_title_bangla {
            font-size: 1.5rem;
        }

        .aboutus_list {
            text-align: left;
        }
    }

    .aboutus_part .aboutus_relation_description {
        width: unset;
        margin-top: 24px;
        text-align: justify;
    }
</style>
