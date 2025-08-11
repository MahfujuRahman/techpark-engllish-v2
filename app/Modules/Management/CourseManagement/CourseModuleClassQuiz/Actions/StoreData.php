<?php

namespace App\Modules\Management\CourseManagement\CourseModuleClassQuiz\Actions;

class StoreData
{
    static $model = \App\Modules\Management\CourseManagement\CourseModuleClassQuiz\Models\Model::class;

    public static function execute($request)
    {
        try {
            $requestData = $request->all();
            $assignments = $requestData['assignments'] ?? [];
            $created = [];
            foreach ($assignments as $assignment) {
                $item = self::$model::query()->create($assignment);
                if ($item) {
                    $created[] = $item;
                }
            }
            if (count($created)) {
                return messageResponse('Class quiz assignments added successfully', $created, 201);
            } else {
                return messageResponse('No assignments saved', [], 400);
            }
        } catch (\Exception $e) {
            return messageResponse($e->getMessage(),[], 500, 'server_error');
        }
    }
}