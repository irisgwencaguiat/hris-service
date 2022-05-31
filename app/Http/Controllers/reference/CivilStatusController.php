<?php

namespace App\Http\Controllers\reference;

use App\Http\Controllers\Controller;
use App\Models\RefCivilStatus;
use Illuminate\Http\Request;

class CivilStatusController extends Controller
{
    public function index()
    {
        return customResponse()
            ->data(RefCivilStatus::get())
            ->message('Civil Statuses references successfully got.')
            ->success()
            ->generate();
    }
}
