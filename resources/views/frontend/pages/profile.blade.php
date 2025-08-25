@php
    $meta = [
        'seo' => [
            'title' => 'Edit Profile',
            'image' => asset('seo.jpg'),
        ],
    ];
@endphp

@extends('frontend.layouts.layout', $meta)

@section('contents')
    <style>
        .divider:after,
        .divider:before {
            content: "";
            flex: 1;
            height: 1px;
            background: #eee;
        }

        .card-custom {
            border: none;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .card-header-custom {
            background: #f8f9fa;
            font-size: 1.25rem;
            font-weight: bold;
            border-bottom: 1px solid #dee2e6;
        }
    </style>

    <section class="py-5 my-5">
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="col-12 col-md-10">
                    <div class="card card-custom">
                        <div class="card-header card-header-custom text-center">
                            Edit Profile
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('update_profile') }}" enctype="multipart/form-data">
                                @csrf

                                <!-- Personal information -->
                                <h5 class="mb-3">Personal Information</h5>
                                <div class="row gx-3">
                                    <div class="col-12 col-md-4">
                                        <div class="mb-3">
                                            <label for="first_name" class="form-label">First name <span
                                                    class="text-danger">*</span></label>
                                            <input id="first_name" name="first_name" type="text" class="form-control"
                                                value="{{ old('first_name', auth()->user()->first_name) }}"
                                                placeholder="John">
                                            @error('first_name')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="mb-3">
                                            <label for="last_name" class="form-label">Last name</label>
                                            <input id="last_name" name="last_name" type="text" class="form-control"
                                                value="{{ old('last_name', auth()->user()->last_name) }}" placeholder="Doe">
                                            @error('last_name')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="mb-3">
                                            <label for="gender" class="form-label">Gender</label>
                                            <select id="gender" name="gender" class="form-select">
                                                <option value="">Choose...</option>
                                                <option value="Male"
                                                    {{ old('gender', auth()->user()->gender) == 'Male' ? 'selected' : '' }}>
                                                    Male</option>
                                                <option value="Female"
                                                    {{ old('gender', auth()->user()->gender) == 'Female' ? 'selected' : '' }}>
                                                    Female</option>
                                                <option value="Other"
                                                    {{ old('gender', auth()->user()->gender) == 'Other' ? 'selected' : '' }}>
                                                    Other</option>
                                            </select>
                                            @error('gender')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row align-items-center mb-3">
                                    <div class="col-12 col-md-3 text-center text-md-start">
                                        @if (auth()->user()->image)
                                            <img src="{{ asset(auth()->user()->image) }}" alt="avatar"
                                                class="img-thumbnail" style="width:110px; height:110px; object-fit:cover;">
                                        @else
                                            <div class="img-thumbnail d-inline-block"
                                                style="width:110px; height:110px; background:#f1f5f9;border-radius:6px;">
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-12 col-md-9">
                                        <label for="image" class="form-label">Profile picture</label>
                                        <input id="image" name="image" type="file" class="form-control">
                                        @error('image')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row gx-3">
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label for="reference_source" class="form-label">Reference</label>
                                            <select id="reference_source" name="reference_source" class="form-select">
                                                <option value="">Select</option>
                                                @php $ref = old('reference_source', auth()->user()->reference_source); @endphp
                                                @foreach (['Facebook', 'Youtube', 'Organization', 'Ex-Student', 'Employee', 'Telesales', 'Offline Marketing', 'Friend', 'Other'] as $r)
                                                    <option value="{{ $r }}"
                                                        {{ $ref == $r ? 'selected' : '' }}>{{ $r }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>


                                <!-- Contact information -->
                                <hr>
                                <h5 class="mb-3 mt-2">Contact</h5>
                                <div class="row gx-3">
                                    <div class="col-12 col-md-4">
                                        <div class="mb-3">
                                            <label for="mobile_number" class="form-label">Mobile number <span
                                                    class="text-danger">*</span></label>
                                            <input id="mobile_number" name="mobile_number" type="text"
                                                class="form-control"
                                                value="{{ old('mobile_number', auth()->user()->mobile_number) }}"
                                                placeholder="01XXXXXXXXX">
                                            @error('mobile_number')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="mb-3">
                                            <label for="whatsapp_number" class="form-label">WhatsApp</label>
                                            <input id="whatsapp_number" name="whatsapp_number" type="text"
                                                class="form-control"
                                                value="{{ old('whatsapp_number', auth()->user()->whatsapp_number) }}"
                                                placeholder="01XXXXXXXXX">
                                            @error('whatsapp_number')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="mb-3">
                                            <label for="guardian_number" class="form-label">Guardian's number</label>
                                            <input id="guardian_number" name="guardian_number" type="text"
                                                class="form-control"
                                                value="{{ old('guardian_number', auth()->user()->guardian_number) }}"
                                                placeholder="Optional">
                                            @error('guardian_number')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Location -->
                                <hr>
                                <h5 class="mb-3 mt-2">Location</h5>
                                <div class="row gx-3">
                                    <div class="col-6 col-md-3">
                                        <div class="mb-3">
                                            <label for="country" class="form-label">Country</label>
                                            <input id="country" name="country" type="text" class="form-control"
                                                value="{{ old('country', auth()->user()->country) }}"
                                                placeholder="Country">
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="mb-3">
                                            <label for="state" class="form-label">State</label>
                                            <input id="state" name="state" type="text" class="form-control"
                                                value="{{ old('state', auth()->user()->state) }}" placeholder="State">
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="mb-3">
                                            <label for="city" class="form-label">City</label>
                                            <input id="city" name="city" type="text" class="form-control"
                                                value="{{ old('city', auth()->user()->city) }}" placeholder="City">
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="mb-3">
                                            <label for="post" class="form-label">Post/Zip</label>
                                            <input id="post" name="post" type="text" class="form-control"
                                                value="{{ old('post', auth()->user()->post) }}" placeholder="Post code">
                                        </div>
                                    </div>
                                </div>

                                <!-- Professional -->
                                <hr>
                                <h5 class="mb-3 mt-2">Professional</h5>
                                <div class="row gx-3">
                                    <div class="col-12 col-md-4">
                                        <div class="mb-3">
                                            <label for="occupation" class="form-label">Occupation</label>
                                            <input id="occupation" name="occupation" type="text" class="form-control"
                                                value="{{ old('occupation', auth()->user()->occupation) }}"
                                                placeholder="e.g. Student / Developer">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="mb-3">
                                            <label for="organization" class="form-label">Organization</label>
                                            <input id="organization" name="organization" type="text"
                                                class="form-control"
                                                value="{{ old('organization', auth()->user()->organization) }}"
                                                placeholder="Organization">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="mb-3">
                                            <label for="designation" class="form-label">Designation</label>
                                            <input id="designation" name="designation" type="text"
                                                class="form-control"
                                                value="{{ old('designation', auth()->user()->designation) }}"
                                                placeholder="Job title">
                                        </div>
                                    </div>
                                </div>


                                <!-- Institutional -->
                                <hr>
                                <h5 class="mb-3 mt-2">Institutional</h5>
                                <div class="row gx-3">
                                    <div class="col-12 col-md-4">
                                        <div class="mb-3">
                                            <label for="institution" class="form-label">Institution</label>
                                            <input id="institution" name="institution" type="text"
                                                class="form-control"
                                                value="{{ old('institution', auth()->user()->institution) }}"
                                                placeholder="Organization / School">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="mb-3">
                                            <label for="class" class="form-label">Class </label>
                                            <input id="class" name="class" type="text" class="form-control"
                                                value="{{ old('class', auth()->user()->class) }}" placeholder="Class">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="mb-3">
                                            <label for="last_certificate_name" class="form-label">Last certificate</label>
                                            <input id="last_certificate_name" name="last_certificate_name" type="text"
                                                class="form-control"
                                                value="{{ old('last_certificate_name', auth()->user()->last_certificate_name) }}"
                                                placeholder="e.g. HSC / Diploma">
                                        </div>
                                    </div>
                                </div>

                                <!-- Security -->
                                <hr>
                                <h5 class="mb-3 mt-2">Security</h5>
                                <div class="row gx-3">
                                    <div class="col-12 col-md-6">
                                        <div class="mb-3">
                                            <label for="password" class="form-label">Password</label>
                                            <input id="password" name="password" type="password" class="form-control"
                                                placeholder="Leave empty to keep current">
                                            @error('password')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="mb-3">
                                            <label for="password_confirmation" class="form-label">Confirm password</label>
                                            <input id="password_confirmation" name="password_confirmation"
                                                type="password" class="form-control" placeholder="Repeat password">
                                            @error('password_confirmation')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center mt-4">
                                    <button type="submit" class="btn btn-primary rounded-pill px-4">Update
                                        Profile</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
