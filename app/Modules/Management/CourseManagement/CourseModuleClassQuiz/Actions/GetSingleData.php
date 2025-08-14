<?php

namespace App\Modules\Management\CourseManagement\CourseModuleClassQuiz\Actions;



class GetSingleData
{
    static $model = \App\Modules\Management\CourseManagement\CourseModuleClassQuiz\Models\Model::class;

    public static function execute($slug)
    {
        try {
            $with = [];
            $fields = request()->input('fields') ?? ['*'];
            
            // Get the first record to identify the group
            if (!$firstRecord = self::$model::query()->with($with)->select($fields)->where('slug', $slug)->first()) {
                return messageResponse('Data not found...', [], 404, 'error');
            }
            
            // Get all assignments for this course_module_id (same group)
            $assignments = self::$model::query()
                ->with($with)
                ->select($fields)
                ->where('course_id', $firstRecord->course_id)
                ->where('milestone_id', $firstRecord->milestone_id)
                ->where('course_module_id', $firstRecord->course_module_id)
                ->where('course_module_class_id', $firstRecord->course_module_class_id)
                ->get();
                
            return messageResponse('Data found', ['assignments' => $assignments], 200, 'success');
        } catch (\Exception $e) {
            return messageResponse($e->getMessage(),[], 500, 'server_error');
        }
    }
}