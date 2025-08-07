<?php

namespace App\Modules\Management\CourseManagement\CourseModuleClass\Actions;

class StoreData
{
    static $model = \App\Modules\Management\CourseManagement\CourseModuleClass\Models\Model::class;

    public static function execute($request)
    {
        try {
            $requestData = $request->validated();

            // Handle image upload
            if ($request->hasFile('class_video_poster')) {
                $class_video_poster = $request->file('class_video_poster');
                $requestData['class_video_poster'] = uploader($class_video_poster, 'uploads/course/class_video_posters');
            }

            if ($data = self::$model::query()->create($requestData)) {
                return messageResponse('Item added successfully', $data, 201);
            }
        } catch (\Exception $e) {
            return messageResponse($e->getMessage(),[], 500, 'server_error');
        }
    }
}