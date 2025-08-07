<?php

namespace App\Modules\Management\CourseManagement\CourseModuleClass\Actions;

class UpdateData
{
    static $model = \App\Modules\Management\CourseManagement\CourseModuleClass\Models\Model::class;

    public static function execute($request, $slug)
    {
        try {
            if (!$data = self::$model::query()->where('slug', $slug)->first()) {
                return messageResponse('Data not found...', $data, 404, 'error');
            }
            $requestData = $request->validated();
            // Handle image upload
            if ($request->hasFile('class_video_poster')) {
                $class_video_poster = $request->file('class_video_poster');
                $requestData['class_video_poster'] = uploader($class_video_poster, 'uploads/course/class_video_posters');
            }
            $data->update($requestData);
            return messageResponse('Item updated successfully', $data, 201);
        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}
