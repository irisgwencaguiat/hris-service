<?php

namespace App\Http\Controllers\reference;

use App\Http\Controllers\Controller;
use App\Models\RefCity;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index(Request $request)
    {
        $filters = apiFilters($request, [
            'reg_code' => 'reg_code',
            'prov_code' => 'prov_code'
        ], [], 'STRICT');

        return customResponse()
            ->data(RefCity::where($filters)->get())
            ->message('City references successfully got.')
            ->success()
            ->generate();
    }
}
