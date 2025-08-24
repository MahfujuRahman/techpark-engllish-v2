<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Modules\Management\CourseManagement\CourseInstructors\Models\Model as CourseInstructors;

class TeacherController extends Controller
{
     public function teacher_details($teacher_name, $slug)
    {

        $teacher = CourseInstructors::where('slug', $slug)->with('courses', 'batches')->first();

        return view('frontend.pages.teacher.teacher-details', compact('teacher'));
    }

    public function trainer_details()
    {

        // $teacher = CourseInstructors::where('slug', $slug)->first();
        // $trainers = CourseInstructors::with('instructor', 'courses', 'batches')->limit(6)->get();
        $website_about = WebsiteCoreInformation::where('status', 1)->first();
        $trainers = CourseInstructors::where('status', 'active')->with('instructor', 'courses', 'batches')->paginate(9);

        return view('frontend.pages.trainer-details', compact('website_about', 'trainers'));
    }
}
