<section class="working_company_name_area" id="brands">
    <div class="working_company_name_area_content">

        <!--working_company_name_area_title start -->
        <div class="working_company_name_area_title">
            <h2 class="area_title">
                আমরা যাদের সাথে কাজ করছি
            </h2>
        </div>
        <!-- -working_company_name_area_title end -->

        <!-- all_company_name and logo area start -->
        <div class="all_company_name">
            <ul>
                @foreach ($brands as $brand)
                    <li class="company_logo">
                        <a href="#">
                            <img src="/{{ $brand->image }}" alt="{{ $brand->title }}" />
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
        <!-- all_company_name and logo area end -->
    </div>
</section>
