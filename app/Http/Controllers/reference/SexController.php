<?php

namespace App\Http\Controllers\reference;

use App\Http\Controllers\Controller;
use App\Models\RefSex;
use Illuminate\Http\Request;

class SexController extends Controller
{
    public function index()
    {
        return customResponse()
            ->data(RefSex::get())
            ->message('Sex references successfully got.')
            ->success()
            ->generate();
    }
}
