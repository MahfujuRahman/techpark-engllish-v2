<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WebsiteCoreInformation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login()
    {
        if (auth()->check()) {
            return redirect('/');
        }

        return view('frontend.pages.auth.login');
    }

    public function login_submit()
    {
        $studentRoleSerial = config('roleManagement.student');

        $this->validate(request(), [
            "email" => ["required"],
            "password" => ["required"],
        ]);

        $user = User::where('email', request()->email)->first();

        if (!$user) {
            $error = ValidationException::withMessages([
                'email' => ['invalid email'],
            ]);
            throw $error;
        }

        $check_password = Hash::check(request()->password, $user->password);

        if (!$check_password) {
            $error = ValidationException::withMessages([
                'password' => ['invalid password'],
            ]);
            throw $error;
        }
        $studentRoleSerial = config('roleManagement.student');
        $check_student = $user->where('role_id', $studentRoleSerial)->exists();


        if ($check_student) {
            Auth::login($user);

            $urls = Session::get('url');

            if (isset($urls['intended'])) {

                $redirect_url = $urls['intended'];
                return redirect($redirect_url);
            } else {
                return redirect()->route('myCourse');
            }
        } else {
            return redirect()->back()->with('error', 'You are not authorized to access this platform.');
        }
    }

    public function logout_submit()
    {
        if (auth()->check()) {
            $this->remove_access_token();
            auth()->logout();
        }

        return redirect('/')->withCookie(cookie("AXRF-TOKEN", ''));
    }

    public function remove_access_token()
    {
        DB::table('oauth_access_tokens')->where('user_id', auth()->user()->id)->update(['revoked' => 1]);
    }

    public function register()
    {
        if (auth()->check()) {
            return redirect('/');
        }
        $website_about = WebsiteCoreInformation::where('status', 1)->first();
        return view('frontend.pages.register', compact('website_about'));
    }

    public function register_sumbit()
    {
        $this->validate(request(), [
            "first_name" => ["required"],
            "last_name" => ["required"],
            "mobile_number" => ["required", "unique:users"],
            "email" => ["unique:users"],
            "password" => ["required"],
        ]);

        $user = User::create([
            "first_name" => request()->first_name,
            "last_name" => request()->last_name,
            "email" => request()->email,
            "mobile_number" => request()->mobile_number,
            "password" => Hash::make(request()->password),
        ]);

        $user->roles()->attach([6]);

        Auth::login($user, true);

        $urls = Session::get('url');
        if (isset($urls['intended'])) {
            $redirect_url = $urls['intended'];
            return redirect($redirect_url);
        } else {
            return redirect()->route('myCourse');
        }
        // $redirect_url = $urls['intended'];
        // return redirect($redirect_url);
    }
}
