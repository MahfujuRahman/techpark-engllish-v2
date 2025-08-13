<?php

namespace App\Modules\Management\CourseManagement\CourseInstructors\Actions;

class DestroyData
{
    static $model = \App\Modules\Management\CourseManagement\CourseInstructors\Models\Model::class;

    public static function execute($slug)
    {
        try {

            if (!$data = self::$model::where('slug', $slug)->first()) {
                return messageResponse('Data not found...', $data, 404, 'error');
            }
            if ($data->image) {
                $imagePath = public_path($data->image);

                if (file_exists($imagePath)) {
                    @unlink($imagePath);
                }
            }
            if ($data->cover_photo) {
                $imagePath = public_path($data->cover_photo);

                if (file_exists($imagePath)) {
                    @unlink($imagePath);
                }
            }
            $data->forceDelete();
            return messageResponse('Item Successfully deleted', [], 200, 'success');
        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}
