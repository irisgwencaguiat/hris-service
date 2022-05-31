<?php

namespace App\Http\Controllers\reference;

use App\Http\Controllers\Controller;
use App\Models\RefBarangay;
use Illuminate\Http\Request;

class BarangayController extends Controller
{
    public function index(Request $request)
    {
        $filters = apiFilters($request, [
            'reg_code' => 'reg_code',
            'prov_code' => 'prov_code',
            'city_code' => 'city_code'
        ], [], 'STRICT');

        return customResponse()
            ->data(RefBarangay::where($filters)->get())
            ->message('Barangay references successfully got.')
            ->success()
            ->generate();
    }
}
