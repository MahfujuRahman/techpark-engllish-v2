<?php

namespace App\Modules\Management\CourseManagement\CourseHowIsStructured\Actions;

class StoreData
{
    static $model = \App\Modules\Management\CourseManagement\CourseHowIsStructured\Models\Model::class;

    public static function execute($request)
    {
        try {
            $requestData = $request->validated();

            // Auto-assign serial number if not provided or is 0
            $courseId = $requestData['course_id'];
            // If serial is not set or is 0, assign next available serial
            if (!isset($requestData['serial']) || $requestData['serial'] == 0) {
                $maxSerial = self::$model::query()
                    ->where('course_id', $courseId)
                    ->max('serial');
                $requestData['serial'] = ($maxSerial ?? 0) + 1;
            } else {
                // If serial is set, check if it already exists for this course
                $exists = self::$model::query()
                    ->where('course_id', $courseId)
                    ->where('serial', $requestData['serial'])
                    ->exists();
                if ($exists) {
                    // Assign next available serial if duplicate found
                    $maxSerial = self::$model::query()
                        ->where('course_id', $courseId)
                        ->max('serial');
                    $requestData['serial'] = ($maxSerial ?? 0) + 1;
                }
            }

            if ($data = self::$model::query()->create($requestData)) {
                return messageResponse('Item added successfully', $data, 201);
            }
        } catch (\Exception $e) {
            return messageResponse($e->getMessage(),[], 500, 'server_error');
        }
    }
}