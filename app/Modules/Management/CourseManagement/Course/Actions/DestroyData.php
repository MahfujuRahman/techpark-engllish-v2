<?php

namespace App\Modules\Management\CourseManagement\Course\Actions;

class DestroyData
{
    static $model = \App\Modules\Management\CourseManagement\Course\Models\Model::class;

    public static function execute($slug)
    {
        try {

            if (!$data = self::$model::where('slug', $slug)->first()) {
                return messageResponse('Data not found...', $data, 404, 'error');
            }

            if ($data->image) {
                $imagePath = public_path( $data->image);

                if (file_exists($imagePath)) {
                    @unlink($imagePath);
                }
            }

            // Delete related milestones
            \App\Modules\Management\CourseManagement\CourseMilestone\Models\Model::where('course_id', $data->id)->forceDelete();
           
            // Delete related modules
            \App\Modules\Management\CourseManagement\CourseModule\Models\Model::where('course_id', $data->id)->forceDelete();

            // Delete related classes
            \App\Modules\Management\CourseManagement\CourseModuleClass\Models\Model::where('course_id', $data->id)->forceDelete();

            // Delete related quiz assignments
            \App\Modules\Management\CourseManagement\CourseModuleClassQuiz\Models\Model::where('course_id', $data->id)->forceDelete();

            $data->forceDelete();
            return messageResponse('Item Successfully deleted', [], 200, 'success');
        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}
