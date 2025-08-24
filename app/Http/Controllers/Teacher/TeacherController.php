<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Modules\Management\CourseManagement\CourseInstructors\Models\Model as CourseInstructors;
use App\Modules\Management\WebsiteManagement\OurTrainer\Models\Model as OurTrainer;

class TeacherController extends Controller
{
     public function teacher_details($teacher_name, $slug)
    {

        $teacher = CourseInstructors::where('slug', $slug)->with('courses', 'batches')->first();

        return view('frontend.pages.teacher.teacher-details', compact('teacher'));
    }

    public function trainer_details()
    {

        $trainers = CourseInstructors::where('status', 'active')->with( 'courses', 'batches')->paginate(9);
        $heading = OurTrainer::where('status', 'active')->first();
        return view('frontend.pages.teacher.trainer-details', compact( 'trainers', 'heading'));
    }
}
