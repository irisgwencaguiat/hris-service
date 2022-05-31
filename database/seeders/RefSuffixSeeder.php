<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RefSuffix;

class RefSuffixSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $suffixes = [
            ['suffix_id' => 'Jr.', 'suffix_desc' => 'Junior'],
            ['suffix_id' => 'Sr.', 'suffix_desc' => 'Senior'],
            ['suffix_id' => 'I', 'suffix_desc' => 'I'],
            ['suffix_id' => 'II', 'suffix_desc' => 'II'],
            ['suffix_id' => 'III', 'suffix_desc' => 'III'],
            ['suffix_id' => 'IV', 'suffix_desc' => 'IV'],
            ['suffix_id' => 'V', 'suffix_desc' => 'V'],
            ['suffix_id' => 'VI', 'suffix_desc' => 'VI'],
        ];
        foreach ($suffixes as $suffix) {
            RefSuffix::create($suffix);
        }
    }
}
