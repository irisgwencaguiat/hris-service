<?php

namespace App\Http\Controllers\reference;

use App\Http\Controllers\Controller;
use App\Models\RefEmploymentStatus;
use Illuminate\Http\Request;

class EmploymentStatusController extends Controller
{
    public function index()
    {
        return customResponse()
            ->data(RefEmploymentStatus::get())
            ->message('Employment Status references successfully got.')
            ->success()
            ->generate();
    }
}
