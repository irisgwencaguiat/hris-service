<?php

namespace App\Http\Controllers\reference;

use App\Http\Controllers\Controller;
use App\Models\RefFile;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function index()
    {
        return customResponse()
            ->data(RefFile::get())
            ->message('File references successfully got.')
            ->success()
            ->generate();
    }
}
