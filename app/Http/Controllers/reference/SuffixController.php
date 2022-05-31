<?php

namespace App\Http\Controllers\reference;

use App\Http\Controllers\Controller;
use App\Models\RefSuffix;
use Illuminate\Http\Request;

class SuffixController extends Controller
{
    public function index()
    {
        return customResponse()
            ->data(RefSuffix::get())
            ->message('Suffix references successfully got.')
            ->success()
            ->generate();
    }
}
