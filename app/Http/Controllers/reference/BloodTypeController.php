<?php

namespace App\Http\Controllers\reference;

use App\Http\Controllers\Controller;
use App\Models\RefBloodType;
use Illuminate\Http\Request;

class BloodTypeController extends Controller
{
    public function index()
    {
        return customResponse()
            ->data(RefBloodType::get())
            ->message('Blood Type references successfully got.')
            ->success()
            ->generate();
    }
}
