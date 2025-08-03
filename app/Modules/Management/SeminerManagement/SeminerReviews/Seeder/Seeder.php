<?php
namespace App\Modules\Management\SeminerManagement\SeminerReviews\Seeder;

use Illuminate\Database\Seeder as SeederClass;
use Faker\Factory as Faker;

class Seeder extends SeederClass
{
    /**
     * Run the database seeds.
     php artisan db:seed --class="App\Modules\Management\SeminerManagement\SeminerReviews\Seeder\Seeder"
     */
    static $model = \App\Modules\Management\SeminerManagement\SeminerReviews\Models\Model::class;

    public function run(): void
    {
        $faker = Faker::create();
        self::$model::truncate();

        for ($i = 1; $i <= 100; $i++) {
            self::$model::create([                'seminar_id' => null,
                'comment' => $faker->text(255),
                'rating' => $faker->randomFloat(2, 0, 1000),
                'comment_reply' => json_encode([$faker->word, $faker->word]),
            ]);
        }
    }
}