<?php

namespace App\Http\Controllers\reference;

use App\Http\Controllers\Controller;
use App\Models\RefRequirement;
use Illuminate\Http\Request;

class RequirementController extends Controller
{
    public function index()
    {
        return customResponse()
            ->data(RefRequirement::get())
            ->message('Requirements references successfully got.')
            ->success()
            ->generate();
    }
}
