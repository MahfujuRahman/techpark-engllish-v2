<?php
namespace App\Modules\Management\CourseManagement\CourseCourseInstructor\Seeder;

use Illuminate\Database\Seeder as SeederClass;
use Faker\Factory as Faker;
use App\Models\User;
use App\Modules\Management\CourseManagement\Course\Models\Model as CourseModel;
use App\Modules\Management\CourseManagement\CourseBatch\Models\Model as CourseBatchModel;

class Seeder extends SeederClass
{
    /**
     * Run the database seeds.
     php artisan db:seed --class="App\Modules\Management\CourseManagement\CourseCourseInstructor\Seeder\Seeder"
     */
    static $model = \App\Modules\Management\CourseManagement\CourseCourseInstructor\Models\Model::class;

    public function run(): void
    {
        $faker = Faker::create();

        // Get sample data
        $users = User::take(5)->get();
        $courses = CourseModel::take(3)->get();
        $batches = CourseBatchModel::take(3)->get();

        if ($users->isEmpty() || $courses->isEmpty() || $batches->isEmpty()) {
            echo "Please ensure you have users, courses, and batches in the database first.\n";
            return;
        }

        // Clear existing data
        self::$model::truncate();

        for ($i = 1; $i <= 10; $i++) {
            self::$model::create([
                'instructor_id' => $users->random()->id,
                'course_id' => $courses->random()->id,
                'batch_id' => $batches->random()->id,
                'status' => $faker->randomElement(['active', 'inactive']),
            ]);
        }

        echo "Course instructor seeder completed successfully.\n";
    }
}