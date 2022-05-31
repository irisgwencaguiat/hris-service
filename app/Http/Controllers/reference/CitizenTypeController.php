<?php

namespace App\Http\Controllers\reference;

use App\Http\Controllers\Controller;
use App\Models\RefCitizenshipType;
use Illuminate\Http\Request;

class CitizenTypeController extends Controller
{
    public function index()
    {
        return customResponse()
            ->data(RefCitizenshipType::get())
            ->message('Citizen Type references successfully got.')
            ->success()
            ->generate();
    }
}
