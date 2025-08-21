<?php

namespace App\Modules\Management\GalleryManagement\Gallery\Actions;

class UpdateData
{
    static $model = \App\Modules\Management\GalleryManagement\Gallery\Models\Model::class;

    public static function execute($request, $slug)
    {
        try {
            if (!$data = self::$model::query()->where('slug', $slug)->first()) {
                return messageResponse('Data not found...', $data, 404, 'error');
            }
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
            $data->update($requestData);
            return messageResponse('Item updated successfully', $data, 201);
        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}
