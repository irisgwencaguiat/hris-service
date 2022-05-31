<?php

namespace App\Http\Controllers\reference;

use App\Http\Controllers\Controller;
use App\Models\RefLearningDevelopmentType;
use Illuminate\Http\Request;

class LearningDevelopmentTypeController extends Controller
{
    public function index()
    {
        return customResponse()
            ->data(RefLearningDevelopmentType::get())
            ->message('Learning development type references successfully got.')
            ->success()
            ->generate();
    }
}
