<?php

namespace App\Http\Controllers\About;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\About\Actions\About;



class AboutController extends Controller
{
    public function index()
    {
        $data = About::execute();
        return $data;
    }
}
