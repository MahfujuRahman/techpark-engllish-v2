<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Modules\Management\CourseManagement\Course\Models\Model as Course;
use App\Modules\Management\CourseManagement\CourseBatch\Models\Model as CourseBatch;
use App\Modules\Management\CourseManagement\CourseCategory\Models\Model as CourseCategory;
use App\Modules\Management\CourseManagement\Course\Models\CourseFaqModel as CourseFaq;
use App\Modules\Management\CourseManagement\Course\Models\CourseForWhomModel as CourseForWhom;
use App\Modules\Management\CourseManagement\Course\Models\CourseJobPositionModel as CourseJobPosition;
use App\Modules\Management\CourseManagement\Course\Models\CourseWhyYouLearnFromUsModel as CourseWhyLearn;
use App\Modules\Management\CourseManagement\Course\Models\CourseYouWillLearnModel as CourseYouWillLearn;
use App\Modules\Management\CourseManagement\CourseMilestone\Models\Model as CourseMilestone;
use App\Modules\Management\CourseManagement\CourseModule\Models\Model as CourseModule;
use App\Modules\Management\CourseManagement\CourseModuleClass\Models\Model as CourseModuleClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

class CourseDetailsManagementController extends Controller
{
    // ===== COURSE MANAGEMENT =====
    
    /**
     * Get all courses
     */
    public function getAllCourses()
    {
        $paginate = (int) request()->paginate ?? 15;
        $orderBy = request()->orderBy ?? 'id';
        $orderByType = request()->orderByType ?? 'DESC';

        $status = 'active';
        if (request()->has('status')) {
            $status = request()->status;
        }

        $query = Course::where('status', $status)
            ->with(['course_category'])
            ->orderBy($orderBy, $orderByType);

        if (request()->has('search_key')) {
            $key = request()->search_key;
            $query->where(function ($q) use ($key) {
                return $q->where('title', 'LIKE', '%' . $key . '%')
                    ->orWhere('slug', 'LIKE', '%' . $key . '%');
            });
        }

        $courses = $query->paginate($paginate);
        return response()->json($courses);
    }

    /**
     * Get single course details
     */
    public function getCourseDetails($id)
    {
        $course = Course::with([
            'course_category',
            'course_batches',
            'course_instructors',
            'milestones',
            'course_faqs',
            'course_you_will_learns',
            'course_job_positions',
            'course_for_whoms',
            'course_why_you_learn_from_us'
        ])->find($id);

        if (!$course) {
            return response()->json([
                'message' => 'Course not found',
                'error' => true
            ], 404);
        }

        return response()->json([
            'data' => $course,
            'message' => 'Course details retrieved successfully'
        ]);
    }

    /**
     * Create new course
     */
    public function createCourse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'course_category_id' => 'required|exists:course_categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'intro_video' => 'nullable|string',
            'type' => 'nullable|string',
            'is_published' => 'required|boolean',
            'published_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
                'error' => true
            ], 422);
        }

        $course = new Course();
        $course->title = $request->title;
        $course->slug = Str::slug($request->title);
        $course->course_category_id = $request->course_category_id;
        $course->intro_video = $request->intro_video;
        $course->type = $request->type ?? 'regular';
        $course->is_published = $request->is_published;
        $course->published_at = $request->published_at;
        $course->status = 'active';
        $course->save();

        // Handle image upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = 'uploads/course/course-' . $course->id . '-' . time() . '.' . $file->getClientOriginalExtension();
            Image::make($file)->fit(720, 450)->save(public_path($path));
            $course->image = $path;
            $course->save();
        }

        return response()->json([
            'data' => $course,
            'message' => 'Course created successfully'
        ], 201);
    }

    /**
     * Update course
     */
    public function updateCourse(Request $request, $id)
    {
        $course = Course::find($id);
        if (!$course) {
            return response()->json([
                'message' => 'Course not found',
                'error' => true
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'course_category_id' => 'required|exists:course_categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
                'error' => true
            ], 422);
        }

        $course->title = $request->title;
        $course->slug = Str::slug($request->title);
        $course->course_category_id = $request->course_category_id;
        $course->intro_video = $request->intro_video;
        $course->type = $request->type ?? $course->type;
        $course->is_published = $request->is_published ?? $course->is_published;
        $course->published_at = $request->published_at ?? $course->published_at;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = 'uploads/course/course-' . $course->id . '-' . time() . '.' . $file->getClientOriginalExtension();
            Image::make($file)->fit(720, 450)->save(public_path($path));
            $course->image = $path;
        }

        $course->save();

        return response()->json([
            'data' => $course,
            'message' => 'Course updated successfully'
        ]);
    }

    // ===== COURSE BANNER MANAGEMENT =====
    
    public function updateCourseBanner(Request $request, $id)
    {
        $course = Course::find($id);
        if (!$course) {
            return response()->json(['message' => 'Course not found', 'error' => true], 404);
        }

        if ($request->hasFile('banner_image')) {
            $file = $request->file('banner_image');
            $path = 'uploads/course/banner-' . $course->id . '-' . time() . '.' . $file->getClientOriginalExtension();
            Image::make($file)->fit(1200, 600)->save(public_path($path));
            $course->banner_image = $path;
        }

        $course->banner_title = $request->banner_title ?? $course->banner_title;
        $course->banner_description = $request->banner_description ?? $course->banner_description;
        $course->save();

        return response()->json([
            'data' => $course,
            'message' => 'Course banner updated successfully'
        ]);
    }

    // ===== COURSE BATCH MANAGEMENT =====
    
    public function getCourseBatches($courseId)
    {
        $batches = CourseBatch::where('course_id', $courseId)
            ->with(['course_instructors'])
            ->paginate(15);

        return response()->json($batches);
    }

    public function createCourseBatch(Request $request, $courseId)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'capacity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
                'error' => true
            ], 422);
        }

        $batch = new CourseBatch();
        $batch->course_id = $courseId;
        $batch->title = $request->title;
        $batch->start_date = $request->start_date;
        $batch->end_date = $request->end_date;
        $batch->capacity = $request->capacity;
        $batch->status = 'active';
        $batch->save();

        return response()->json([
            'data' => $batch,
            'message' => 'Course batch created successfully'
        ], 201);
    }

    // ===== COURSE WHAT WILL LEARN =====
    
    public function getCourseWhatWillLearn($courseId)
    {
        $items = CourseYouWillLearn::where('course_id', $courseId)->paginate(15);
        return response()->json($items);
    }

    public function createCourseWhatWillLearn(Request $request, $courseId)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
                'error' => true
            ], 422);
        }

        $item = new CourseYouWillLearn();
        $item->course_id = $courseId;
        $item->title = $request->title;
        $item->description = $request->description;
        $item->status = 'active';
        $item->save();

        return response()->json([
            'data' => $item,
            'message' => 'What will learn item created successfully'
        ], 201);
    }

    // ===== COURSE JOB POSITIONS =====
    
    public function getCourseJobPositions($courseId)
    {
        $positions = CourseJobPosition::where('course_id', $courseId)->paginate(15);
        return response()->json($positions);
    }

    public function createCourseJobPosition(Request $request, $courseId)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
                'error' => true
            ], 422);
        }

        $position = new CourseJobPosition();
        $position->course_id = $courseId;
        $position->title = $request->title;
        $position->description = $request->description;
        $position->status = 'active';
        $position->save();

        return response()->json([
            'data' => $position,
            'message' => 'Job position created successfully'
        ], 201);
    }

    // ===== COURSE FOR WHOM =====
    
    public function getCourseForWhom($courseId)
    {
        $items = CourseForWhom::where('course_id', $courseId)->paginate(15);
        return response()->json($items);
    }

    public function createCourseForWhom(Request $request, $courseId)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
                'error' => true
            ], 422);
        }

        $item = new CourseForWhom();
        $item->course_id = $courseId;
        $item->title = $request->title;
        $item->description = $request->description;
        $item->status = 'active';
        $item->save();

        return response()->json([
            'data' => $item,
            'message' => 'For whom item created successfully'
        ], 201);
    }

    // ===== COURSE WHY LEARN =====
    
    public function getCourseWhyLearn($courseId)
    {
        $items = CourseWhyLearn::where('course_id', $courseId)->paginate(15);
        return response()->json($items);
    }

    public function createCourseWhyLearn(Request $request, $courseId)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
                'error' => true
            ], 422);
        }

        $item = new CourseWhyLearn();
        $item->course_id = $courseId;
        $item->title = $request->title;
        $item->description = $request->description;
        $item->status = 'active';
        $item->save();

        return response()->json([
            'data' => $item,
            'message' => 'Why learn item created successfully'
        ], 201);
    }

    // ===== COURSE MILESTONES =====
    
    public function getCourseMilestones($courseId)
    {
        $milestones = CourseMilestone::where('course_id', $courseId)
            ->with(['modules'])
            ->paginate(15);
        return response()->json($milestones);
    }

    public function createCourseMilestone(Request $request, $courseId)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
                'error' => true
            ], 422);
        }

        $milestone = new CourseMilestone();
        $milestone->course_id = $courseId;
        $milestone->title = $request->title;
        $milestone->description = $request->description;
        $milestone->status = 'active';
        $milestone->save();

        return response()->json([
            'data' => $milestone,
            'message' => 'Milestone created successfully'
        ], 201);
    }

    // ===== COURSE MODULES =====
    
    public function getCourseModules($courseId)
    {
        $modules = CourseModule::where('course_id', $courseId)
            ->with(['milestone', 'classes'])
            ->paginate(15);
        return response()->json($modules);
    }

    public function createCourseModule(Request $request, $courseId)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'milestone_id' => 'required|exists:course_milestones,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
                'error' => true
            ], 422);
        }

        $module = new CourseModule();
        $module->course_id = $courseId;
        $module->milestone_id = $request->milestone_id;
        $module->title = $request->title;
        $module->description = $request->description;
        $module->status = 'active';
        $module->save();

        return response()->json([
            'data' => $module,
            'message' => 'Module created successfully'
        ], 201);
    }

    // ===== COURSE CLASSES =====
    
    public function getCourseClasses($courseId)
    {
        $classes = CourseModuleClass::whereHas('module', function($query) use ($courseId) {
            $query->where('course_id', $courseId);
        })->with(['module'])->paginate(15);
        
        return response()->json($classes);
    }

    public function createCourseClass(Request $request, $courseId)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'module_id' => 'required|exists:course_modules,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
                'error' => true
            ], 422);
        }

        $class = new CourseModuleClass();
        $class->module_id = $request->module_id;
        $class->title = $request->title;
        $class->description = $request->description;
        $class->status = 'active';
        $class->save();

        return response()->json([
            'data' => $class,
            'message' => 'Class created successfully'
        ], 201);
    }

    // ===== COURSE FAQ =====
    
    public function getCourseFAQs($courseId)
    {
        $faqs = CourseFaq::where('course_id', $courseId)->paginate(15);
        return response()->json($faqs);
    }

    public function createCourseFAQ(Request $request, $courseId)
    {
        $validator = Validator::make($request->all(), [
            'question' => 'required|string',
            'answer' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
                'error' => true
            ], 422);
        }

        $faq = new CourseFaq();
        $faq->course_id = $courseId;
        $faq->question = $request->question;
        $faq->answer = $request->answer;
        $faq->status = 'active';
        $faq->save();

        return response()->json([
            'data' => $faq,
            'message' => 'FAQ created successfully'
        ], 201);
    }

    // ===== COURSE CATEGORIES (Helper) =====
    
    public function getCourseCategories()
    {
        $categories = CourseCategory::where('status', 'active')->get();
        return response()->json(['data' => $categories]);
    }
}
