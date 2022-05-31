<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RefFile;

class RefFileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $files = [
            ['file_name' => '201 File']
        ];

        foreach ($files as $file) {
            RefFile::create($file);
        }
    }
}
