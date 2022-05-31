<?php

namespace App\Http\Controllers\reference;

use App\Http\Controllers\Controller;
use App\Models\RefUserClassification;
use Illuminate\Http\Request;

class UserClassificationController extends Controller
{
    public function index()
    {
        return customResponse()
            ->data(RefUserClassification::get())
            ->message('User references successfully got.')
            ->success()
            ->generate();
    }
}
