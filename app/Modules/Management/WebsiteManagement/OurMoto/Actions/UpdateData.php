<?php

namespace App\Modules\Management\WebsiteManagement\OurMoto\Actions;

class UpdateData
{
    static $model = \App\Modules\Management\WebsiteManagement\OurMoto\Models\Model::class;

    public static function execute($request,$slug)
    {
        try {
            if (!$data = self::$model::query()->where('slug', $slug)->first()) {
                return messageResponse('Data not found...',$data, 404, 'error');
            }
            $requestData = $request->validated();
            
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $requestData['image'] = uploader($file, 'uploads/WebsiteManagement/OurMoto');
            }
            $data->update($requestData);
            return messageResponse('Item updated successfully',$data, 201);
        } catch (\Exception $e) {
            return messageResponse($e->getMessage(),[], 500, 'server_error');
        }
    }
}