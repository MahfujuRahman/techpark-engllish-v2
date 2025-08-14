<?php

namespace App\Modules\Management\CourseManagement\CourseCourseInstructor\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;
class Model extends EloquentModel
{
    use SoftDeletes;
    protected $table = "course_course_instructors";
    protected $guarded = [];

    // Static model references for relationships
    public static $course_model = \App\Modules\Management\CourseManagement\Course\Models\Model::class;
    public static $batch_model = \App\Modules\Management\CourseManagement\CourseBatch\Models\Model::class;
    public static $instructor_model =  \App\Modules\Management\CourseManagement\CourseInstructors\Models\Model::class;

    protected static function booted()
    {
        static::created(function ($data) {
            $random_no = random_int(100, 999) . $data->id . random_int(100, 999);
            $slug = "course-instructor-" . $random_no;
            $data->slug = Str::slug($slug); //use Illuminate\Support\Str;
            if (strlen($data->slug) > 50) {
                $data->slug = substr($data->slug, strlen($data->slug) - 50, strlen($data->slug));
            }
            if (auth()->check()) {
                $data->creator = auth()->user()->id;
            }
            $data->save();
        });
    }

    public function scopeActive($q)
    {
        return $q->where('status', 'active');
    }

    public function scopeInactive($q)
    {
        return $q->where('status', 'inactive');
    }

    // Relationships
    public function instructor()
    {
        return $this->belongsTo(self::$instructor_model, 'instructor_id', 'id');
    }

    public function course()
    {
        return $this->belongsTo(self::$course_model, 'course_id', 'id');
    }

    public function batch()
    {
        return $this->belongsTo(self::$batch_model, 'batch_id', 'id');
    }

    public function scopeTrased($q)
    {
        return $q->onlyTrashed();
    }
}