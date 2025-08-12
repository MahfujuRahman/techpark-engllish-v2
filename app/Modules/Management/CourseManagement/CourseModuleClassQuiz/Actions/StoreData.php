<?php

namespace App\Modules\Management\CourseManagement\CourseModuleClassQuiz\Actions;

use Illuminate\Support\Facades\DB;

class StoreData
{
    static $model = \App\Modules\Management\CourseManagement\CourseModuleClassQuiz\Models\Model::class;

    public static function execute($request)
    {
        try {
            $requestData = $request->all();
            $assignments = $requestData['assignments'] ?? [];
            $affected = [];

            if (empty($assignments)) {
                return messageResponse('No assignments provided', [], 400);
            }

            // Use database transaction to ensure atomicity
            DB::beginTransaction();

            // Normalise keys & group by class context (course_id + milestone_id + module_id + class_id)
            $grouped = [];
            foreach ($assignments as $a) {
                $courseId  = $a['course_id'] ?? null;
                $milestone = $a['milestone_id'] ?? $a['course_milestone_id'] ?? null;
                $moduleId  = $a['course_module_id'] ?? null;
                $classId   = $a['course_module_class_id'] ?? $a['course_class_id'] ?? null;
                $quizId    = $a['quiz_id'] ?? $a['course_quiz_id'] ?? null;
                
                if (!$courseId || !$classId || !$quizId) {
                    // skip invalid row
                    continue;
                }
                
                $normalized = [
                    'course_id'              => $courseId,
                    'milestone_id'           => $milestone,
                    'course_module_id'       => $moduleId,
                    'course_module_class_id' => $classId,
                    'quiz_id'                => $quizId,
                ];
                
                // merge back any extra updatable columns (status, creator, etc.)
                $extra = array_diff_key($a, $normalized);
                $row   = array_merge($normalized, $extra);
                
                $groupKey = implode('-', [
                    $normalized['course_id'],
                    $normalized['milestone_id'] ?? 'null',
                    $normalized['course_module_id'] ?? 'null',
                    $normalized['course_module_class_id']
                ]);
                
                // Only keep unique quiz assignments per class context
                $grouped[$groupKey]['context'] = $normalized;
                $grouped[$groupKey]['rows'][$quizId] = $row;
            }

            if (empty($grouped)) {
                DB::rollBack();
                return messageResponse('No valid assignments after normalization', [], 400);
            }

            $model = self::$model;

            foreach ($grouped as $group) {
                $context = $group['context'];
                $rows    = $group['rows'];

                // SYNC: Delete ALL existing assignments for this class context first
                $deleteConditions = [
                    'course_id' => $context['course_id'],
                    'course_module_class_id' => $context['course_module_class_id']
                ];
                
                if (!is_null($context['milestone_id'])) {
                    $deleteConditions['milestone_id'] = $context['milestone_id'];
                }
                if (!is_null($context['course_module_id'])) {
                    $deleteConditions['course_module_id'] = $context['course_module_id'];
                }

                // Force delete with explicit conditions
                $deletedCount = $model::where($deleteConditions)->forceDelete();

                // Then create all new assignments (fresh sync)
                foreach ($rows as $quizId => $data) {
                    // Double check no existing record before create
                    $existingCheck = $model::where([
                        'course_id' => $data['course_id'],
                        'course_module_class_id' => $data['course_module_class_id'],
                        'quiz_id' => $data['quiz_id'],
                        'milestone_id' => $data['milestone_id'],
                        'course_module_id' => $data['course_module_id']
                    ])->first();

                    if (!$existingCheck) {
                        $created = $model::create($data);
                        if ($created) {
                            $affected[] = $created;
                        }
                    }
                }
            }

            DB::commit();

            if (count($affected)) {
                return messageResponse('Class quiz assignments saved successfully', $affected, 201);
            }
            return messageResponse('No assignments saved', [], 400);
        } catch (\Exception $e) {
            DB::rollBack();
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}