<?php

namespace App\Http\Controllers\reference;

use App\Http\Controllers\Controller;
use App\Models\RefHolidayType;
use Illuminate\Http\Request;

class HolidayTypeController extends Controller
{
    public function index()
    {
        return customResponse()
            ->data(RefHolidayType::get())
            ->message('Holiday type references successfully got.')
            ->success()
            ->generate();
    }
}
