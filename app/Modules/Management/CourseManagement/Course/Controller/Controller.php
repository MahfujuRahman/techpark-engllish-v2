<?php

namespace App\Modules\Management\CourseManagement\Course\Controller;
use App\Modules\Management\CourseManagement\Course\Actions\GetAllData;
use App\Modules\Management\CourseManagement\Course\Actions\DestroyData;
use App\Modules\Management\CourseManagement\Course\Actions\GetSingleData;
use App\Modules\Management\CourseManagement\Course\Actions\StoreData;
use App\Modules\Management\CourseManagement\Course\Actions\UpdateData;
use App\Modules\Management\CourseManagement\Course\Actions\UpdateStatus;
use App\Modules\Management\CourseManagement\Course\Actions\SoftDelete;
use App\Modules\Management\CourseManagement\Course\Actions\RestoreData;
use App\Modules\Management\CourseManagement\Course\Actions\ImportData;
use App\Modules\Management\CourseManagement\Course\Validations\BulkActionsValidation;
use App\Modules\Management\CourseManagement\Course\Validations\DataStoreValidation;
use App\Modules\Management\CourseManagement\Course\Actions\BulkActions;
use App\Http\Controllers\Controller as ControllersController;


class Controller extends ControllersController
{

    public function index( ){

        $data = GetAllData::execute();
        return $data;
    }

    public function store(DataStoreValidation $request)
    {
        $data = StoreData::execute($request);
        return $data;
    }

    public function show($slug)
    {
        $data = GetSingleData::execute($slug);
        return $data;
    }

    public function update(DataStoreValidation $request, $slug)
    {
        $data = UpdateData::execute($request, $slug);
        return $data;
    }
         public function updateStatus()
    {
        $data = UpdateStatus::execute();
        return $data;
    }

    public function softDelete()
    {
        $data = SoftDelete::execute();
        return $data;
    }
    public function destroy($slug)
    {
        $data = DestroyData::execute($slug);
        return $data;
    }
    public function restore()
    {
        $data = RestoreData::execute();
        return $data;
    }
    public function import()
    {
        $data = ImportData::execute();
        return $data;
    }
    public function bulkAction(BulkActionsValidation $request)
    {
        $data = BulkActions::execute($request);
        return $data;
    }
    
    public function getFullModule($id)
    {
        try {
            $course = \App\Modules\Management\CourseManagement\Course\Models\Model::with([
                'milestones' => function($query) {
                    $query->orderBy('id', 'asc');
                },
                'milestones.modules' => function($query) {
                    $query->orderBy('id', 'asc');
                },
                'milestones.modules.classes' => function($query) {
                    $query->orderBy('id', 'asc');
                }
            ])->find($id);

            if (!$course) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Course not found'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => $course
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to load course data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function saveFullModule(\Illuminate\Http\Request $request, $id)
    {
        try {
            $course = \App\Modules\Management\CourseManagement\Course\Models\Model::find($id);
            
            if (!$course) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Course not found'
                ], 404);
            }

            $milestones = $request->input('milestones', []);
            
            // Delete existing milestones and their related data
            $course->milestones()->delete();
            
            foreach ($milestones as $milestoneIndex => $milestoneData) {
                // Create milestone
                $milestone = \App\Modules\Management\CourseManagement\CourseMilestone\Models\Model::create([
                    'course_id' => $course->id,
                    'title' => $milestoneData['title'] ?? '',
                    'milestone_no' => $milestoneData['milestone_no'] ?? '',
                    'status' => 'active'
                ]);
                
                if (isset($milestoneData['modules'])) {
                    foreach ($milestoneData['modules'] as $moduleIndex => $moduleData) {
                        // Create module
                        $module = \App\Modules\Management\CourseManagement\CourseModule\Models\Model::create([
                            'milestone_id' => $milestone->id,
                            'course_id' => $course->id,
                            'title' => $moduleData['title'] ?? '',
                            'module_no' => $moduleData['module_no'] ?? '',
                            'status' => 'active'
                        ]);
                        
                        if (isset($moduleData['classes'])) {
                            foreach ($moduleData['classes'] as $classIndex => $classData) {
                                // Create class
                                \App\Modules\Management\CourseManagement\CourseModuleClass\Models\Model::create([
                                    'course_id' => $course->id,
                                    'milestone_id' => $milestone->id,
                                    'course_modules_id' => $module->id,
                                    'class_no' => $classIndex + 1,
                                    'title' => $classData['title'] ?? '',
                                    'type' => $classData['class_type'] ?? 'recorded',
                                    'class_video_link' => $classData['video_url'] ?? '',
                                    'class_video_poster' => $classData['class_video_poster'] ?? '',
                                    'status' => 'active'
                                ]);
                            }
                        }
                    }
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Course modules saved successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to save course modules',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}