<?php

use App\Http\Controllers\Api\CourseDetailsManagementController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('course-details-management')->group(function () {
        
        // Course Management
        Route::get('courses', [CourseDetailsManagementController::class, 'getAllCourses']);
        Route::get('courses/{id}', [CourseDetailsManagementController::class, 'getCourseDetails']);
        Route::post('courses', [CourseDetailsManagementController::class, 'createCourse']);
        Route::put('courses/{id}', [CourseDetailsManagementController::class, 'updateCourse']);
        
        // Course Banner
        Route::put('courses/{id}/banner', [CourseDetailsManagementController::class, 'updateCourseBanner']);
        
        // Course Batches
        Route::get('courses/{courseId}/batches', [CourseDetailsManagementController::class, 'getCourseBatches']);
        Route::post('courses/{courseId}/batches', [CourseDetailsManagementController::class, 'createCourseBatch']);
        
        // Course What Will Learn
        Route::get('courses/{courseId}/what-will-learn', [CourseDetailsManagementController::class, 'getCourseWhatWillLearn']);
        Route::post('courses/{courseId}/what-will-learn', [CourseDetailsManagementController::class, 'createCourseWhatWillLearn']);
        
        // Course Job Positions
        Route::get('courses/{courseId}/job-positions', [CourseDetailsManagementController::class, 'getCourseJobPositions']);
        Route::post('courses/{courseId}/job-positions', [CourseDetailsManagementController::class, 'createCourseJobPosition']);
        
        // Course For Whom
        Route::get('courses/{courseId}/for-whom', [CourseDetailsManagementController::class, 'getCourseForWhom']);
        Route::post('courses/{courseId}/for-whom', [CourseDetailsManagementController::class, 'createCourseForWhom']);
        
        // Course Why Learn
        Route::get('courses/{courseId}/why-learn', [CourseDetailsManagementController::class, 'getCourseWhyLearn']);
        Route::post('courses/{courseId}/why-learn', [CourseDetailsManagementController::class, 'createCourseWhyLearn']);
        
        // Course Milestones
        Route::get('courses/{courseId}/milestones', [CourseDetailsManagementController::class, 'getCourseMilestones']);
        Route::post('courses/{courseId}/milestones', [CourseDetailsManagementController::class, 'createCourseMilestone']);
        
        // Course Modules
        Route::get('courses/{courseId}/modules', [CourseDetailsManagementController::class, 'getCourseModules']);
        Route::post('courses/{courseId}/modules', [CourseDetailsManagementController::class, 'createCourseModule']);
        
        // Course Classes
        Route::get('courses/{courseId}/classes', [CourseDetailsManagementController::class, 'getCourseClasses']);
        Route::post('courses/{courseId}/classes', [CourseDetailsManagementController::class, 'createCourseClass']);
        
        // Course FAQs
        Route::get('courses/{courseId}/faqs', [CourseDetailsManagementController::class, 'getCourseFAQs']);
        Route::post('courses/{courseId}/faqs', [CourseDetailsManagementController::class, 'createCourseFAQ']);
        
        // Helper Routes
        Route::get('course-categories', [CourseDetailsManagementController::class, 'getCourseCategories']);
    });
});
