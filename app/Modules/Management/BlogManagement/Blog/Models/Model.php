<?php

namespace App\Modules\Management\BlogManagement\Blog\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Management\BlogManagement\BlogCategory\Models\Model as BlogsCategories;
use App\Modules\Management\BlogManagement\BlogTag\Models\Model as BlogTags;
use App\Modules\Management\BlogManagement\BlogWriter\Models\Model as BlogWriters;

class Model extends EloquentModel
{
    use SoftDeletes;
    protected $table = "blogs";
    protected $guarded = [];
    protected $casts = [
        'contributors' => 'array'
    ];
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
    public function blog_category_id()
    {
        return $this->belongsTo("App\Modules\Management\BlogManagement\BlogCategory\Models\Model", "blog_category_id");
    }
    public function writer()
    {
        return $this->belongsTo("App\Modules\Management\BlogManagement\BlogWriter\Models\Model", "writer");
    }

     public function category() {
        return $this->belongsToMany(BlogsCategories::class);
    }

    public function tag() {
        return $this->belongsToMany(BlogTags::class, 'blog_blog_tag', 'blog_id', 'blog_tag_id');
    }

    public function writers() {
        return $this->belongsToMany(BlogWriters::class, 'blog_blog_writer', 'blog_id', 'blog_writer_id');
    }
}
