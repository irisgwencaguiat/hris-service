<?php

namespace App\Http\Controllers\reference;

use App\Http\Controllers\Controller;
use App\Models\RefEducationLevel;
use Illuminate\Http\Request;

class EducationLevelController extends Controller
{
    public function index()
    {
        return customResponse()
            ->data(RefEducationLevel::get())
            ->message('Education level references successfully got.')
            ->success()
            ->generate();
    }
}
