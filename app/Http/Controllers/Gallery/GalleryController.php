<?php

namespace App\Http\Controllers\Gallery;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Management\GalleryManagement\GalleryCategory\Models\Model as GalleryCategory;
use App\Modules\Management\GalleryManagement\Gallery\Models\Model as Gallery;

class GalleryController extends Controller
{
    public function gallery()
    {
        if (request()->get('gallery_category_id')) {
            $galleryImages = Gallery::where('status', 'active')->where('gallery_category_id', request()->get('gallery_category_id'))->orderBy('top_image', 'DESC')->paginate(18);
            $galleryImages->appends('gallery_category_id', request()->gallery_category_id);
        } else {
            $galleryImages = Gallery::where('status', 'active')->orderBy('top_image', 'DESC')->paginate(18);
        }
        return view('frontend.pages.gallery', compact('galleryImages'));
    }
}
