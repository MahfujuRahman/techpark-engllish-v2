<?php

namespace App\Modules\Management\CourseManagement\CourseInstructors\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Management\CourseManagement\Course\Models\Model as Course;
use \App\Modules\Management\CourseManagement\CourseBatch\Models\Model as CourseBatches;

class Model extends EloquentModel
{
    use SoftDeletes;
    protected $table = "course_instructors";
    protected $guarded = [];
    protected static function booted()
    {
        static::created(function ($data) {
            $random_no = random_int(100, 999) . $data->id . random_int(100, 999);
            $slug = $data->title . " " . $random_no;
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
    public function scopeTrased($q)
    {
        return $q->onlyTrashed();
    }
    public function user_id()
    {
        return $this->belongsTo("App\Modules\Management\UserManagement\User\Models\Model", "user_id");
    }

    public function courses() {
        return $this->belongsToMany(Course::class, 'course_course_instructors', 'instructor_id', 'course_id', 'id');
    }

    public function batches() {
        return $this->belongsToMany(CourseBatches::class, 'course_course_instructors', 'instructor_id', 'batch_id', 'id');
    }
}
