<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Home\Actions\Home;



class HomeController extends Controller
{
    public function index()
    {
        $data = Home::execute();
        return $data;
    }
}
