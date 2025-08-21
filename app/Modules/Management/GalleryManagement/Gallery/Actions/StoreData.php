<?php

namespace App\Modules\Management\GalleryManagement\Gallery\Actions;

class StoreData
{
    static $model = \App\Modules\Management\GalleryManagement\Gallery\Models\Model::class;

    public static function execute($request)
    {
        try {
            $requestData = $request->validated();

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $requestData['image'] = uploader($file, 'uploads/Gallery');
            }

            if (!empty($requestData['top_image']) && $requestData['top_image']) {
                self::$model::query()
                    ->where('gallery_category_id', $requestData['gallery_category_id'])
                    ->where('top_image', true)
                    ->update(['top_image' => 0]);
            }

            if ($data = self::$model::query()->create([
                'gallery_category_id' => $requestData['gallery_category_id'],
                'title' => $requestData['title'],
                'description' => $requestData['description'],
                'image' => $requestData['image'],
                'top_image' => $requestData['top_image'],
            ])) {
                return messageResponse('Item added successfully', $data, 201);
            }
        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}
