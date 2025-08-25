<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Modules\Management\BlogManagement\BlogCategory\Models\Model as BlogsCategories;
use App\Modules\Management\BlogManagement\Blog\Models\Model as Blogs;
use App\Modules\Management\BlogManagement\BlogTag\Models\Model as BlogTags;
use App\Modules\Management\BlogManagement\BlogWriter\Models\Model as BlogWriters;


class BlogController extends Controller
{
    public function blog()
    {

        $blog_categories = BlogsCategories::active()->get();
        $blog_tags = BlogTags::active()->get();
        $blog_writers = BlogWriters::active()->get();

        $blog_single = Blogs::where('is_published', 1)
            ->where('is_featured', '1')
            ->where('status', 'active')
            ->with('category')
            ->latest()
            ->first();

        // dd($blog_single);

        $blogs = Blogs::where('is_published', 1)
            ->select([
                'id',
                'title',
                'is_featured',
                'short_description',
                'image',
                'is_published',
                'slug',
                'creator',
                'created_at'
            ])
            ->where('status', 'active')
            ->when($blog_single, function ($query) use ($blog_single) {
                return $query->whereNotIn('id', [$blog_single->id]);
            })
            ->with('category')->paginate(6);

        // dd($blogs->toArray());

        return view('frontend.pages.blog', compact('blog_categories', 'blog_tags', 'blog_writers', 'blog_single', 'blogs'));
    }

    public function blog_details($slug)
    {
        $blog = Blogs::where('slug', $slug)->where('published', 1)
            ->where('status', 'active')
            ->with(['category', 'tag', 'writer'])->first();

        return view('frontend.pages.blog-details', compact('blog'));
    }
}
