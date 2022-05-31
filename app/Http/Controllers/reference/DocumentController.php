<?php

namespace App\Http\Controllers\reference;

use App\Http\Controllers\Controller;
use App\Models\RefDocument;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index()
    {
        return customResponse()
            ->data(RefDocument::get())
            ->message('Document references successfully got.')
            ->success()
            ->generate();
    }
}
