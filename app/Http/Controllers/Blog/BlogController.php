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

        $blog_single = Blogs::where('is_published', 1)->where('is_featured', '1')->where('status', 'active')->latest()->first();

        // $blog_single->category = $blog_single->category()->first();
        // $blog_single->writer = $blog_single->writer()->first();
        // dd($blog_single);

        $blogs = Blogs::where('published', 1)
            ->select([
                'id',
                'title',
                'is_featured',
                'short_description',
                'image',
                'published',
                'slug',
                'creator',
                'created_at'
            ])
            ->where('status', 'active')->whereNotIn('id', [$blog_single->id])
            ->with(['category', 'tag', 'writer'])->paginate(6);

        // dd($blogs);

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
