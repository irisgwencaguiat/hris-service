<?php

namespace App\Http\Controllers\reference;

use App\Http\Controllers\Controller;
use App\Models\RefLeaveType;
use Illuminate\Http\Request;

class LeaveTypeController extends Controller
{
    public function index()
    {
        return customResponse()
            ->data(RefLeaveType::get())
            ->message('Leave Types references successfully got.')
            ->success()
            ->generate();
    }
}
