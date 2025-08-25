<?php

namespace App\Modules\Management\BlogManagement\Blog\Actions;

class StoreData
{
    static $model = \App\Modules\Management\BlogManagement\Blog\Models\Model::class;

    public static function execute($request)
    {
        try {
            $requestData = $request->validated();

            if ($request->hasFile('images')) {
                $file = $request->file('images');
                $requestData['images'] = uploader($file, 'uploads/Blog');
            }

            if (isset($requestData['is_featured']) && $requestData['is_featured'] == 1) {
                self::$model::query()->update(['is_featured' => 0]);
            }

            if ($data = self::$model::query()->create($requestData)) {
                return messageResponse('Item added successfully', $data, 201);
            }
        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}
