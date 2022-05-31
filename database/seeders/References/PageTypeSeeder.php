<?php

namespace Database\Seeders\References;

use Illuminate\Database\Seeder;
use Auth;
use App\Models\Reference\PageType;

class PageTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pageTypes = [
            'Module',
            'Tab'
        ];

        foreach ($pageTypes as $pageType) {
            PageType::create([
                'page_type_name' => $pageType
            ]);
        }
    }
}
