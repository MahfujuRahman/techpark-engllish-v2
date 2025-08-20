<?php

namespace App\Modules\Management\UserManagement\User\Controller;

use App\Modules\Management\UserManagement\User\Actions\GetAllData;
use App\Modules\Management\UserManagement\User\Actions\DestroyData;
use App\Modules\Management\UserManagement\User\Actions\GetSingleData;
use App\Modules\Management\UserManagement\User\Actions\StoreData;
use App\Modules\Management\UserManagement\User\Actions\UpdateData;
use App\Modules\Management\UserManagement\User\Actions\SoftDelete;
use App\Modules\Management\UserManagement\User\Actions\RestoreData;
use App\Modules\Management\UserManagement\User\Actions\ImportData;
use App\Modules\Management\UserManagement\User\Actions\UserProfileUpdate;
use App\Modules\Management\UserManagement\User\Actions\UserChangePassword;

use App\Modules\Management\UserManagement\User\Validations\BulkActionsValidation;
use App\Modules\Management\UserManagement\User\Validations\UserProfileUpdateValidation;
use App\Modules\Management\UserManagement\User\Validations\UserChangePasswordValidation;
use App\Modules\Management\UserManagement\User\Validations\DataStoreValidation;
use App\Modules\Management\UserManagement\User\Validations\DataUpdateValidation;
use App\Modules\Management\UserManagement\User\Actions\BulkActions;

use App\Http\Controllers\Controller as ControllersController;
use Illuminate\Support\Facades\DB;

class Controller extends ControllersController
{

    public function index()
    {

        $data = GetAllData::execute();
        return $data;
    }

    public function store(DataStoreValidation $request)
    {

        $data = StoreData::execute($request);
        return $data;
    }

    public function show($slug)
    {
        $data = GetSingleData::execute($slug);
        return $data;
    }

    public function update(DataUpdateValidation $request, $slug)
    {
        $data = UpdateData::execute($request, $slug);
        return $data;
    }

    public function UserProfileUpdate(UserProfileUpdateValidation $request,)
    {
        $data = UserProfileUpdate::execute($request);
        return $data;
    }
    public function UserChangePassword(UserChangePasswordValidation $request,)
    {
        $data = UserChangePassword::execute($request);
        return $data;
    }

    public function softDelete()
    {
        $data = SoftDelete::execute();
        return $data;
    }
    public function destroy($slug)
    {
        $data = DestroyData::execute($slug);
        return $data;
    }
    public function restore()
    {
        $data = RestoreData::execute();
        return $data;
    }
    public function import()
    {
        $data = ImportData::execute();
        return $data;
    }
    public function bulkAction(BulkActionsValidation $request)
    {
        $data = BulkActions::execute($request);
        return $data;
    }

    public function imageDelete($dbName, $slug)
    {
        // Accept `data` coming as a query string (encoded JSON) or as an array in the request body
        $raw = request()->input('data', null);

        $parsed = null;
        if (is_string($raw) && $raw !== '') {
            // Try to decode JSON string. Laravel will typically URL-decode query params for us,
            // but be defensive in case it's still percent-encoded.
            $try = json_decode(urldecode($raw), true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($try)) {
                $parsed = $try;
            } else {
                $try2 = json_decode($raw, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($try2)) {
                    $parsed = $try2;
                }
            }
        } elseif (is_array($raw)) {
            $parsed = $raw;
        }

        // Fallback: sometimes callers send `field` directly
        if (!$parsed) {
            $parsed = [];
            if (request()->has('field')) {
                $parsed['field'] = request()->input('field');
            }
            if (request()->has('index')) {
                $parsed['index'] = request()->input('index');
            }
        }

        $imageField = $parsed['field'] ?? null;

        if (!$imageField) {
            return response()->json([
                'success' => false,
                'message' => 'No image field specified',
                'received' => $raw,
                'parsed' => $parsed,
            ], 400);
        }


        if ($imageField) {
            // Get the previous image path from the database
            $record = DB::table($dbName)->where('slug', $slug)->first();
            $imagePath = $record->{$imageField} ?? null;

            if ($imagePath && file_exists(public_path($imagePath))) {
                @unlink(public_path($imagePath));
            }
        }

        // Update the record to clear the image field
        DB::table($dbName)->where('slug', $slug)
            ->update([$imageField => null]);


        return response()->json([
            'success' => true,
            'message' => 'Image field cleared',
            'field' => $imageField,
        ]);



        // This is a placeholder; actual implementation may vary
        // $data = DestroyData::executeImageDelete($dbName, $slug);
        // return $data;
    }
}
