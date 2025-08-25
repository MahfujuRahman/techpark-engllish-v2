<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Modules\Management\WebsiteManagement\SuccssStories\Models\Model as SuccessStory;
use App\Http\Controllers\Home\Actions\Home;



class HomeController extends Controller
{
    public function index()
    {
        $data = Home::execute();
        return $data;
    }


    public function stories()
    {
        $success_stories = SuccessStory::where('status', 1)->orderBy('id', 'desc')->paginate(6);
        return view('frontend.pages.success_stories.success_story_all', compact('success_stories'));
    }
}
