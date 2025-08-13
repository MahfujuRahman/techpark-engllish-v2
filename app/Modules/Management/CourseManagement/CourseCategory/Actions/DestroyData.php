<?php

namespace App\Modules\Management\CourseManagement\CourseCategory\Actions;

use App\Modules\Management\CourseManagement\Course\Actions\DestroyData as course;

class DestroyData
{
    static $model = \App\Modules\Management\CourseManagement\CourseCategory\Models\Model::class;
    static $courseModel = \App\Modules\Management\CourseManagement\Course\Models\Model::class;

    public static function execute($slug)
    {
        try {

            if (!$data = self::$model::where('slug', $slug)->first()) {
                return messageResponse('Data not found...', $data, 404, 'error');
            }

            $courseModel = self::$courseModel::where('course_category_id', $data->id)->get();

            foreach ($courseModel as $course) {
                course::execute($course->slug);
            }

            $data->forceDelete();
            return messageResponse('Item Successfully deleted', [], 200, 'success');
        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}
