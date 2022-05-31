<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RefRating;

class RefRatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ratings = [
            ['upper_bound' => 4, 'lower_bound' => 3.75, 'rating' => 'Very Satisfactory'],
            ['upper_bound' => 3.74, 'lower_bound' => 3.50, 'rating' => 'Satisfactory'],
            ['upper_bound' => 3.49, 'lower_bound' => 0, 'rating' => 'Unknown'],
        ];
        foreach ($ratings as $rating) {
            RefRating::create($rating);
        }
    }
}
