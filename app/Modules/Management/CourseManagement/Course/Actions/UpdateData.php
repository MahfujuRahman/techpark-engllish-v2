<?php

namespace App\Modules\Management\CourseManagement\Course\Actions;

class UpdateData
{
    static $model = \App\Modules\Management\CourseManagement\Course\Models\Model::class;

    public static function execute($request, $slug)
    {
        try {
            if (!$data = self::$model::query()->where('slug', $slug)->first()) {
                return messageResponse('Data not found...', $data, 404, 'error');
            }
            $requestData = $request->validated();
            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $requestData['image'] = uploader($image, 'uploads/course');
            }

            $data->update($requestData);
            return messageResponse('Item updated successfully', $data, 201);
        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}
