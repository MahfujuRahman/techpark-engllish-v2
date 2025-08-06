<?php

namespace App\Modules\Management\CourseManagement\CourseHowIsStructured\Actions;

class UpdateData
{
    static $model = \App\Modules\Management\CourseManagement\CourseHowIsStructured\Models\Model::class;

    public static function execute($request,$slug)
    {
        try {
            if (!$data = self::$model::query()->where('slug', $slug)->first()) {
                return messageResponse('Data not found...',$data, 404, 'error');
            }
            
            $requestData = $request->validated();
            
            // Handle serial number logic
            if (isset($requestData['serial'])) {
                // If serial is 0, auto-assign the next available serial
                if ($requestData['serial'] == 0) {
                    $courseId = $requestData['course_id'] ?? $data->course_id;
                    
                    // Get the highest serial number for this course (excluding current item)
                    $maxSerial = self::$model::query()
                        ->where('course_id', $courseId)
                        ->where('id', '!=', $data->id)
                        ->max('serial');
                    
                    $requestData['serial'] = ($maxSerial ?? 0) + 1;
                }
                // If serial is provided and different from current, check for conflicts
                elseif ($requestData['serial'] != $data->serial) {
                    $courseId = $requestData['course_id'] ?? $data->course_id;
                    
                    // Check if this serial number is already taken by another item in the same course
                    $existingItem = self::$model::query()
                        ->where('course_id', $courseId)
                        ->where('serial', $requestData['serial'])
                        ->where('id', '!=', $data->id)
                        ->first();
                    
                    if ($existingItem) {
                        // Swap serial numbers to avoid conflicts
                        $existingItem->update(['serial' => $data->serial]);
                    }
                }
            }
            
            $data->update($requestData);
            return messageResponse('Item updated successfully',$data, 201);
        } catch (\Exception $e) {
            return messageResponse($e->getMessage(),[], 500, 'server_error');
        }
    }
}