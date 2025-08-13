<?php

namespace App\Modules\Management\CourseManagement\CourseInstructors\Actions;

class StoreData
{
    static $model = \App\Modules\Management\CourseManagement\CourseInstructors\Models\Model::class;

    public static function execute($request)
    {
        try {
            $requestData = $request->validated();

            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $requestData['image'] = uploader($image, 'uploads/course/instructor');
            }

            // Handle image upload
            if ($request->hasFile('cover_photo')) {
                $coverPhoto = $request->file('cover_photo');
                $requestData['cover_photo'] = uploader($coverPhoto, 'uploads/course/instructor');
            }

            if ($data = self::$model::query()->create($requestData)) {
                return messageResponse('Item added successfully', $data, 201);
            }
        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}
