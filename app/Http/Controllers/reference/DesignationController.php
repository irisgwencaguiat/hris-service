<?php

namespace App\Http\Controllers\reference;

use App\Http\Controllers\Controller;
use App\Models\RefDesignation;
use Illuminate\Http\Request;

class DesignationController extends Controller
{
    public function index()
    {
        return customResponse()
            ->data(RefDesignation::get())
            ->message('Designation references successfully got.')
            ->success()
            ->generate();
    }
}
