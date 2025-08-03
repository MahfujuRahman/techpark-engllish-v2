<?php

namespace App\Modules\Management\SeminerManagement\Seminer\Actions;

class StoreData
{
    static $model = \App\Modules\Management\SeminerManagement\Seminer\Models\Model::class;

    public static function execute($request)
    {
        try {
            $requestData = $request->validated();

            // Process file uploads for specific fields
                            if ($request->hasFile('poster')) {
                    $file = $request->file('poster');
                    $requestData['poster'] = uploader($file, 'uploads/SeminerManagement/Seminer');
                }
          
            if ($data = self::$model::query()->create($requestData)) {
                return messageResponse('Item added successfully', $data, 201);
            }
        } catch (\Exception $e) {
            return messageResponse($e->getMessage(),[], 500, 'server_error');
        }
    }
}