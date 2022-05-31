<?php

namespace App\Http\Controllers\reference;

use App\Http\Controllers\Controller;
use App\Models\RefCountry;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function index()
    {
        return customResponse()
            ->data(RefCountry::get())
            ->message('Country references successfully got.')
            ->success()
            ->generate();
    }
}
