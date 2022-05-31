<?php

namespace App\Http\Controllers\reference;

use App\Http\Controllers\Controller;
use App\Models\RefLeaveCommutationtype;
use Illuminate\Http\Request;

class LeaveCommutationTypeController extends Controller
{
    public function index()
    {
        return customResponse()
            ->data(RefLeaveCommutationtype::get())
            ->message('Leave commutation type references successfully got.')
            ->success()
            ->generate();
    }
}
