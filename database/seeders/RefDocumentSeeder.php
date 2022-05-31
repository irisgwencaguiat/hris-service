<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RefDocument;
use App\Models\RefFile;

class RefDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $file201 = RefFile::where('file_name', '201 File')->value('file_id');
        $documents = [
            ['file_id' => $file201, 'document_name' => 'Personal Data Sheet', 'form_no' => 'CSC Form No. 212', 'revised_no' => 'Revised 2017'],
            ['file_id' => $file201, 'document_name' => 'Oath of Office'],
            ['file_id' => $file201, 'document_name' => 'Appointment'],
            ['file_id' => $file201, 'document_name' => 'Birth Certificates'],
        ];
        foreach ($documents as $document) {
            RefDocument::create($document);
        }
    }
}
