<?php

namespace App\Http\Controllers\About\Actions;


use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

use App\Modules\Management\GalleryManagement\GalleryCategory\Models\Model as GalleryCategory;
use App\Modules\Management\GalleryManagement\Gallery\Models\Model as Gallery;

use App\Modules\Management\WebsiteManagement\AboutUs\Models\Model as AboutUs;
use App\Modules\Management\WebsiteManagement\WebsiteBrand\Models\Model as Brand;
use App\Modules\Management\WebsiteManagement\OurMoto\Models\Model as OurMoto;
use App\Modules\Management\WebsiteManagement\OurMission\Models\Model as OurMission;
use App\Modules\Management\WebsiteManagement\OurVision\Models\Model as OurVision;


class About
{
    static $model = \App\Modules\Management\CommunicationManagement\ContactMessage\Models\Model::class;

    public static function execute()
    {
        $cacheKey = 'About_page_html_v1';
        $ttlMinutes = 60; // cache time-to-live

        // if (Cache::has($cacheKey)) {
        //     $html = Cache::get($cacheKey);
        //     return response($html)->header('X-Cache-Status', 'HIT');
        // }

        $team_top_image = null;
        $team_related_image = null;

        $category = GalleryCategory::where('title', 'আমাদের টিম')->first();

        if ($category) {
            $team_top_image = Gallery::where('gallery_category_id', $category->id)->orderBy('top_image', 'DESC')->first();
            $team_related_image = Gallery::where('gallery_category_id', $category->id)->orderBy('top_image', 'DESC')->skip(1)->take(7)->get();
        }

        $AboutUs = AboutUs::where('status', 1)->first();
        $brands = Brand::where('status', 1)->get();
        $our_moto = OurMoto::where('status', 1)->first();
        $our_mission = OurMission::where('status', 1)->first();
        $our_vision = OurVision::where('status', 1)->first();

        // $teachers = CourseInstructors::where('status', 'active')->with('instructor', 'courses', 'batches')->limit(3)->get();

        // $all = $this->all_course();
        // $courses = $all['courses'];
        // $course_types = $all['course_types'];



        $data = [
            'team_top_image' => $team_top_image,
            'team_related_image' => $team_related_image,
            'AboutUs' => $AboutUs,
            'brands' => $brands,
            'our_moto' => $our_moto,
            'our_mission' => $our_mission,
            'our_vision' => $our_vision,
        ];

        // $html = view('frontend.pages.home.home', $data)->render();
        // Cache::put($cacheKey, $html, now()->addMinutes($ttlMinutes));

        // return response($html)->header('X-Cache-Status', 'MISS');

        Cache::forget($cacheKey);

        return view('frontend.pages.about.about', $data);
    }
}
