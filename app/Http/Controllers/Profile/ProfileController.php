<?php

namespace App\Http\Controllers\Profile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Modules\Management\UserManagement\User\Models\Model as User;

class ProfileController extends Controller
{
    public function profile()
    {
        $user = User::find(auth()->user()->id);
        return view('frontend.pages.profile', compact('user'));
    }

    public function profileUpdate(Request $request)
    {
        dd($request->all());
        $validatedData = $request->validate([
            'first_name'        => 'required|string|max:255',
            'last_name'         => 'nullable|string|max:255',
            'email'             => 'required|email|max:255',
            'image'             => 'nullable|image|max:2048',
            'password'          => 'nullable|string|min:8|confirmed',

            'whatsapp_number'   => 'nullable|string|max:20',
            'phone_number'      => 'nullable|string|max:20',
            'country'           => 'nullable|string|max:255',
            'city'              => 'nullable|string|max:255',
            'state'             => 'nullable|string|max:255',
            'post'               => 'nullable|string|max:20',

            'father_name'       => 'nullable|string|max:255',
            'mother_name'      => 'nullable|string|max:255',
            'gender'            => 'nullable|string|in:Male,Female,Other',
            'guardian_number'   => 'nullable|string|max:20',
            'blood_group'       => 'nullable|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',

            'occupation'        => 'nullable|string|max:255',
            'designation'       => 'nullable|string|max:255',  
            'organization'       => 'nullable|string|max:255',  

            'institution'       => 'nullable|string|max:255',
            'class' => 'nullable|string|max:255',
            'last_certificate_name' => 'nullable|string|max:255',
            'reference_source'  => 'nullable|string|max:255',
        ]);

        $user = User::where('id', auth()->user()->id)->first();

         // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $validatedData['image'] = uploader($image, 'uploads/profile_photos');
            }

        if ($request->filled('password')) {
            $validatedData['password'] = Hash::make($request->password);
        } else {
            unset($validatedData['password']);
        }

        $user->update($validatedData);

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }
}
