<?php

namespace App\Modules\Management\CourseManagement\CourseModuleClassQuiz\Actions;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateData
{
    static $model = \App\Modules\Management\CourseManagement\CourseModuleClassQuiz\Models\Model::class;

    public static function execute($request, $slug)
    {
        try {
            // For update, we use the same sync logic as store
            // The slug parameter is not used since we're doing bulk sync operations
            $requestData = $request->all();
            $assignments = $requestData['assignments'] ?? [];
            $affected = [];

            if (empty($assignments)) {
                return messageResponse('No assignments provided', [], 400);
            }

            // Use database transaction to ensure atomicity
            DB::beginTransaction();

            $model = self::$model;
            
            // Get course ID from first assignment (all should have same course)
            $courseId = null;
            $normalizedAssignments = [];
            
            foreach ($assignments as $a) {
                $courseId = $courseId ?? ($a['course_id'] ?? null);
                $milestone = $a['milestone_id'] ?? $a['course_milestone_id'] ?? null;
                $moduleId = $a['course_module_id'] ?? null;
                $classId = $a['course_module_class_id'] ?? $a['course_class_id'] ?? null;
                $quizId = $a['quiz_id'] ?? $a['course_quiz_id'] ?? null;
                
                if (!$courseId || !$classId || !$quizId) {
                    continue;
                }
                
                $normalizedAssignments[] = [
                    'course_id' => $courseId,
                    'milestone_id' => $milestone,
                    'course_module_id' => $moduleId,
                    'course_module_class_id' => $classId,
                    'quiz_id' => $quizId,
                ];
            }

            if (empty($normalizedAssignments) || !$courseId) {
                DB::rollBack();
                return messageResponse('No valid assignments found', [], 400);
            }

            // Get unique class IDs from the assignments
            $classIds = array_unique(array_column($normalizedAssignments, 'course_module_class_id'));

            // SYNC APPROACH: Delete ALL existing assignments for ALL classes in this submission
            // We need to match the full context (course + milestone + module + class)
            foreach ($classIds as $classId) {
                // Find the milestone and module for this class from our normalized data
                $classData = collect($normalizedAssignments)->firstWhere('course_module_class_id', $classId);
                
                $deleteWhere = [
                    'course_id' => $courseId,
                    'course_module_class_id' => $classId
                ];
                
                // Include milestone and module in deletion criteria if they exist
                if (!is_null($classData['milestone_id'])) {
                    $deleteWhere['milestone_id'] = $classData['milestone_id'];
                }
                if (!is_null($classData['course_module_id'])) {
                    $deleteWhere['course_module_id'] = $classData['course_module_id'];
                }
                
                $deletedCount = $model::where($deleteWhere)->forceDelete();
                // Debug: Log what was deleted
                Log::info("Updated - Deleted {$deletedCount} records for class {$classId} with conditions: " . json_encode($deleteWhere));
            }

            // Now create all new assignments
            foreach ($normalizedAssignments as $data) {
                $created = $model::create($data);
                if ($created) {
                    $affected[] = $created;
                }
            }

            DB::commit();

            if (count($affected)) {
                return messageResponse('Class quiz assignments updated successfully', $affected, 200);
            }
            return messageResponse('No assignments updated', [], 400);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}