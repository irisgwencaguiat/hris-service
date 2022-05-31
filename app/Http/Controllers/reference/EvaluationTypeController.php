<?php

namespace App\Http\Controllers\reference;

use App\Http\Controllers\Controller;
use App\Models\RefEvaluationType;
use Illuminate\Http\Request;

class EvaluationTypeController extends Controller
{
    public function index()
    {
        return customResponse()
            ->data(RefEvaluationType::get())
            ->message('Evaluation Types references successfully got.')
            ->success()
            ->generate();
    }
}
