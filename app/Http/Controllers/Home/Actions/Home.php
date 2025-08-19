<?php

namespace App\Http\Controllers\Home\Actions;


use Illuminate\Support\Carbon;
use App\Modules\Management\SeminerManagement\Seminer\Models\Model as Seminars;
use App\Modules\Management\WebsiteManagement\WebsiteBrand\Models\Model as Brand;
use App\Modules\Management\WebsiteManagement\SubBanner\Models\Model as SubBanner;
use App\Modules\Management\WebsiteManagement\WebsiteBanner\Models\Model as Banner;
use App\Modules\Management\WebsiteManagement\OurTrainer\Models\Model as OurTrainer;
use App\Modules\Management\CourseManagement\CourseBatch\Models\Model as CourseBatches;
use App\Modules\Management\WebsiteManagement\SuccssStories\Models\Model as SuccessStory;
use App\Modules\Management\WebsiteManagement\OurSpeciality\Models\Model as OurSpeciality;
use App\Modules\Management\CourseManagement\Course\Models\Model as CourseOutcomeStepModel;
use App\Modules\Management\CourseManagement\CourseCategory\Models\Model as CourseCategory;

class Home
{
    static $model = \App\Modules\Management\CommunicationManagement\ContactMessage\Models\Model::class;

    public static function execute()
    {
        $banners = Banner::where('is_featured', 1)->where('status', 1)->orderBy('id', 'desc')->get();
        $subBanners = SubBanner::where('status', 1)->orderBy('id', 'desc')->get();
        $our_speciality = OurSpeciality::where('status', 1)->orderBy('id', 'desc')->get();
        $success_stories = SuccessStory::where('status', 1)->limit(6)->orderBy('id', 'desc')->get();
        $our_trainers = OurTrainer::where('status', 1)->orderBy('id', 'desc')->first();
        $seminars = Seminars::where('date_time', '>', Carbon::today())->where('status', 'active')->get();
        $brands = Brand::where('status', 1)->get();

        $course_categories = CourseCategory::where('status', 'active')->get();

        // $all = $this->all_course();
        // $courses = $all['courses'];
        // $course_types = $all['course_types'];

        $courseBatch = CourseBatches::active()->orderBy('id', 'DESC')->get();


        $course_learning_steps = CourseOutcomeStepModel::get();

        return view(
            'frontend.pages.home.home',
            [
                'banners' => $banners,
                'subBanners' => $subBanners,

                'our_speciality' => $our_speciality,
                'success_stories' => $success_stories,
                'our_trainers' => $our_trainers,
                "seminars" => $seminars,
                'brands' => $brands,


                'course_categories' => $course_categories,
                // 'course_types' => $course_types,

                // 'courses' => $courses,
                'course_learning_steps' => $course_learning_steps,
                'courseBatches' => $courseBatch
            ]
        );
    }
}
