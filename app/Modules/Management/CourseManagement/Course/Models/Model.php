<?php

namespace App\Modules\Management\CourseManagement\Course\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

class Model extends EloquentModel
{
    use SoftDeletes;
    protected $table = "courses";
    protected $guarded = [];

    public static $course_batch = \App\Modules\Management\CourseManagement\CourseBatch\Models\Model::class;
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
    public function course_category()
    {
        return $this->belongsTo("App\Modules\Management\CourseManagement\CourseCategory\Models\Model", "course_category_id");
    }
    public function course_batch()
    {
        return $this->hasMany(self::$course_batch, 'course_id', 'id');
    }
}
