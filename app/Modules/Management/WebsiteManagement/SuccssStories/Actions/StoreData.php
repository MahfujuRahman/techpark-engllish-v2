<?php

namespace App\Modules\Management\WebsiteManagement\SuccssStories\Actions;

class StoreData
{
    static $model = \App\Modules\Management\WebsiteManagement\SuccssStories\Models\Model::class;

    public static function execute($request)
    {
        try {
            $requestData = $request->validated();

            // Process file uploads for specific fields
            if ($request->hasFile('thumbnail_image')) {
                $file = $request->file('thumbnail_image');
                $requestData['thumbnail_image'] = uploader($file, 'uploads/WebsiteManagement/SuccssStories');
            }

            if ($data = self::$model::query()->create($requestData)) {
                return messageResponse('Item added successfully', $data, 201);
            }
        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}
