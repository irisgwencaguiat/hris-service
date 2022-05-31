<?php

namespace App\Http\Controllers\reference;

use App\Http\Controllers\Controller;
use App\Models\RefProvince;
use Illuminate\Http\Request;
use Log;

class ProvinceController extends Controller
{
    public function index(Request $request)
    {
        $filters = apiFilters($request, [
            'reg_code' => 'reg_code'
        ], [], 'STRICT');

        return customResponse()
            ->data(RefProvince::where($filters)->get())
            ->message('Province references successfully got.')
            ->success()
            ->generate();
    }
}
