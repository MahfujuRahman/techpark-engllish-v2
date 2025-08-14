<?php

namespace App\Modules\Management\CourseManagement\Course\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller as ControllersController;
use App\Modules\Management\CourseManagement\Course\Actions\StoreData;
use App\Modules\Management\CourseManagement\Course\Actions\GetAllData;
use App\Modules\Management\CourseManagement\Course\Actions\ImportData;
use App\Modules\Management\CourseManagement\Course\Actions\SoftDelete;
use App\Modules\Management\CourseManagement\Course\Actions\UpdateData;
use App\Modules\Management\CourseManagement\Course\Actions\BulkActions;
use App\Modules\Management\CourseManagement\Course\Actions\DestroyData;
use App\Modules\Management\CourseManagement\Course\Actions\RestoreData;
use App\Modules\Management\CourseManagement\Course\Actions\UpdateStatus;
use App\Modules\Management\CourseManagement\Course\Actions\GetSingleData;
use App\Modules\Management\CourseManagement\Course\Validations\DataStoreValidation;
use App\Modules\Management\CourseManagement\Course\Validations\BulkActionsValidation;


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
            DB::beginTransaction();
            
            $course = \App\Modules\Management\CourseManagement\Course\Models\Model::find($id);
            
            if (!$course) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Course not found'
                ], 404);
            }

            $milestones = $request->input('milestones', []);
            
            // Get existing structure
            $existingMilestones = \App\Modules\Management\CourseManagement\CourseMilestone\Models\Model::where('course_id', $course->id)->get();
            $existingModules = \App\Modules\Management\CourseManagement\CourseModule\Models\Model::where('course_id', $course->id)->get();
            $existingClasses = \App\Modules\Management\CourseManagement\CourseModuleClass\Models\Model::where('course_id', $course->id)->get();
            
            $submittedMilestoneIds = [];
            $submittedModuleIds = [];
            $submittedClassIds = [];
            
            foreach ($milestones as $milestoneIndex => $milestoneData) {
                // Skip empty milestones
                if (empty($milestoneData['title'])) {
                    continue;
                }
                
                $milestone = null;
                
                // Check if milestone exists (by ID)
                if (isset($milestoneData['id']) && $milestoneData['id']) {
                    $milestone = $existingMilestones->find($milestoneData['id']);
                    if ($milestone) {
                        // Update existing milestone
                        $milestone->update([
                            'title' => $milestoneData['title'],
                            'milestone_no' => $milestoneData['milestone_no'] ?? ($milestoneIndex + 1),
                            'status' => 'active'
                        ]);
                        $submittedMilestoneIds[] = $milestone->id;
                    }
                }
                
                // Create new milestone if not found
                if (!$milestone) {
                    $milestone = \App\Modules\Management\CourseManagement\CourseMilestone\Models\Model::create([
                        'course_id' => $course->id,
                        'title' => $milestoneData['title'],
                        'milestone_no' => $milestoneData['milestone_no'] ?? ($milestoneIndex + 1),
                        'slug' => Str::random(7),
                        'status' => 'active'
                    ]);
                    $submittedMilestoneIds[] = $milestone->id;
                }
                
                if (isset($milestoneData['modules']) && is_array($milestoneData['modules'])) {
                    foreach ($milestoneData['modules'] as $moduleIndex => $moduleData) {
                        // Skip empty modules
                        if (empty($moduleData['title'])) {
                            continue;
                        }
                        
                        $module = null;
                        
                        // Check if module exists (by ID)
                        if (isset($moduleData['id']) && $moduleData['id']) {
                            $module = $existingModules->find($moduleData['id']);
                            if ($module) {
                                // Update existing module
                                $module->update([
                                    'milestone_id' => $milestone->id,
                                    'title' => $moduleData['title'],
                                    'module_no' => $moduleData['module_no'] ?? ($moduleIndex + 1),
                                    'status' => 'active'
                                ]);
                                $submittedModuleIds[] = $module->id;
                            }
                        }
                        
                        // Create new module if not found
                        if (!$module) {
                            $module = \App\Modules\Management\CourseManagement\CourseModule\Models\Model::create([
                                'milestone_id' => $milestone->id,
                                'course_id' => $course->id,
                                'title' => $moduleData['title'],
                                'module_no' => $moduleData['module_no'] ?? ($moduleIndex + 1),
                                'slug' => Str::random(7),
                                'status' => 'active'
                            ]);
                            $submittedModuleIds[] = $module->id;
                        }
                        
                        if (isset($moduleData['classes']) && is_array($moduleData['classes'])) {
                            foreach ($moduleData['classes'] as $classIndex => $classData) {
                                // Skip empty classes
                                if (empty($classData['title'])) {
                                    continue;
                                }
                                
                                $class = null;
                                
                                // Check if class exists (by ID)
                                if (isset($classData['id']) && $classData['id']) {
                                    $class = $existingClasses->find($classData['id']);
                                    if ($class) {
                                            // Determine poster path: uploaded file, base64 preview, deletion flag, or provided value
                                            $posterPath = $classData['class_video_poster'] ?? '';

                                            // If frontend sent nested file input, check for it using both dot and bracket notation
                                            $fileKeyDot = "milestones.$milestoneIndex.modules.$moduleIndex.classes.$classIndex.class_video_poster";
                                            $fileKeyBracket = "milestones[$milestoneIndex][modules][$moduleIndex][classes][$classIndex][class_video_poster]";

                                            if ($request->hasFile($fileKeyDot)) {
                                                $class_video_poster_file = $request->file($fileKeyDot);
                                                // Handle image upload
                                                $posterPath = uploader($class_video_poster_file, 'uploads/course/class_video_posters');
                                            } elseif ($request->hasFile($fileKeyBracket)) {
                                                $class_video_poster_file = $request->file($fileKeyBracket);
                                                $posterPath = uploader($class_video_poster_file, 'uploads/course/class_video_posters');
                                            } elseif (!empty($classData['imagePreview']) && strpos($classData['imagePreview'], 'data:') === 0) {
                                                // Save base64 imagePreview to disk
                                                try {
                                                    $data = $classData['imagePreview'];
                                                    $parts = explode(',', $data, 2);
                                                    if (count($parts) === 2) {
                                                        $meta = $parts[0];
                                                        $content = $parts[1];
                                                        // extract mime
                                                        if (preg_match('/data:(.*);base64/', $meta, $m)) {
                                                            $mime = $m[1];
                                                        } else {
                                                            $mime = 'image/jpeg';
                                                        }
                                                        $ext = explode('/', $mime)[1] ?? 'jpg';
                                                        $decoded = base64_decode($content);
                                                        $filename = time() . '_' . Str::random(6) . '.' . $ext;
                                                        $relativePath = 'uploads/course/class_video_posters/' . $filename;
                                                        file_put_contents(public_path($relativePath), $decoded);
                                                        $posterPath = $relativePath;
                                                    }
                                                } catch (\Exception $e) {
                                                    // ignore and keep existing posterPath
                                                }
                                            }

                                            // If frontend indicated deletion explicitly
                                            if (!empty($classData['poster_deleted'])) {
                                                // delete existing file if present
                                                if (!empty($class->class_video_poster) && file_exists(public_path($class->class_video_poster))) {
                                                    @unlink(public_path($class->class_video_poster));
                                                }
                                                $posterPath = '';
                                            }

                                            // Update existing class
                                            $class->update([
                                                'milestone_id' => $milestone->id,
                                                'course_modules_id' => $module->id,
                                                'class_no' => $classData['class_no'] ?? ($classIndex + 1),
                                                'title' => $classData['title'],
                                                'type' => $classData['class_type'] ?? 'recorded',
                                                'class_video_link' => $classData['class_video_link'] ?? '',
                                                'class_video_poster' => $posterPath,
                                                'status' => 'active'
                                            ]);
                                        $submittedClassIds[] = $class->id;
                                    }
                                }
                                
                                // Create new class if not found
                                if (!$class) {
                                    // Determine poster path for new class as well
                                    $posterPath = $classData['class_video_poster'] ?? '';
                                    $fileKeyDot = "milestones.$milestoneIndex.modules.$moduleIndex.classes.$classIndex.class_video_poster";
                                    $fileKeyBracket = "milestones[$milestoneIndex][modules][$moduleIndex][classes][$classIndex][class_video_poster]";
                                    if ($request->hasFile($fileKeyDot)) {
                                        $class_video_poster_file = $request->file($fileKeyDot);
                                        $posterPath = uploader($class_video_poster_file, 'uploads/course/class_video_posters');
                                    } elseif ($request->hasFile($fileKeyBracket)) {
                                        $class_video_poster_file = $request->file($fileKeyBracket);
                                        $posterPath = uploader($class_video_poster_file, 'uploads/course/class_video_posters');
                                    } elseif (!empty($classData['imagePreview']) && strpos($classData['imagePreview'], 'data:') === 0) {
                                        try {
                                            $data = $classData['imagePreview'];
                                            $parts = explode(',', $data, 2);
                                            if (count($parts) === 2) {
                                                $meta = $parts[0];
                                                $content = $parts[1];
                                                if (preg_match('/data:(.*);base64/', $meta, $m)) {
                                                    $mime = $m[1];
                                                } else {
                                                    $mime = 'image/jpeg';
                                                }
                                                $ext = explode('/', $mime)[1] ?? 'jpg';
                                                $decoded = base64_decode($content);
                                                $filename = time() . '_' . Str::random(6) . '.' . $ext;
                                                $relativePath = 'uploads/course/class_video_posters/' . $filename;
                                                file_put_contents(public_path($relativePath), $decoded);
                                                $posterPath = $relativePath;
                                            }
                                        } catch (\Exception $e) {
                                            // ignore
                                        }
                                    }

                                    $class = \App\Modules\Management\CourseManagement\CourseModuleClass\Models\Model::create([
                                        'course_id' => $course->id,
                                        'milestone_id' => $milestone->id,
                                        'course_modules_id' => $module->id,
                                        'class_no' => $classData['class_no'] ?? ($classIndex + 1),
                                        'title' => $classData['title'],
                                        'type' => $classData['class_type'] ?? 'recorded',
                                        'class_video_link' => $classData['class_video_link'] ?? '',
                                        'class_video_poster' => $posterPath,
                                        'slug' => Str::random(7),
                                        'status' => 'active'
                                    ]);
                                    $submittedClassIds[] = $class->id;
                                }
                            }
                        }
                    }
                }
            }
            
            // Delete items that were removed (not in submitted data)
            $existingMilestones->whereNotIn('id', $submittedMilestoneIds)->each(function($milestone) {
                $milestone->forceDelete();
            });
            
            $existingModules->whereNotIn('id', $submittedModuleIds)->each(function($module) {
                $module->forceDelete();
            });
            
            $existingClasses->whereNotIn('id', $submittedClassIds)->each(function($class) {
                $class->forceDelete();
            });

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Course modules synchronized successfully'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to synchronize course modules',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}