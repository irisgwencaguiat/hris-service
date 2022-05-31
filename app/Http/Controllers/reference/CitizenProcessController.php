<?php

namespace App\Http\Controllers\reference;

use App\Http\Controllers\Controller;
use App\Models\RefCitizenshipProcess;
use Illuminate\Http\Request;

class CitizenProcessController extends Controller
{
    public function index()
    {
        return customResponse()
            ->data(RefCitizenshipProcess::get())
            ->message('Citizen Process references successfully got.')
            ->success()
            ->generate();
    }
}
