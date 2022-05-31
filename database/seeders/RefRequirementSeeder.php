<?php

namespace Database\Seeders;

use App\Models\RefRequirement;
use Illuminate\Database\Seeder;

class RefRequirementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $requirements = [
            ['requirement_name' => 'Copy of Resume'],
            ['requirement_name' => '2pcs of 2x2 picture'],
            ['requirement_name' => '2pcs of 1x1 picture'],
            ['requirement_name' => 'Photocopy of Birth Certificate'],
            [
                'requirement_name' =>
                    'Photocopy of Birth Certificate of Dependents',
            ],
            ['requirement_name' => 'Photocopy of Marriage Contract'],
            ['requirement_name' => 'Photocopy of 2 valid ID'],
            ['requirement_name' => 'Photocopy of SSS ID'],
            ['requirement_name' => 'Photocopy of Philhealth ID'],
            ['requirement_name' => 'Photocopy of TIN ID'],
        ];
        foreach ($requirements as $requirement) {
            RefRequirement::create($requirement);
        }
    }
}
