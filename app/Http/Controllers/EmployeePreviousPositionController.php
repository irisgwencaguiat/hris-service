<?php

namespace App\Http\Controllers;

use App\Models\PreviousPosition;
use Illuminate\Http\Request;

class EmployeePreviousPositionController extends Controller
{
    public function show($id)
    {
        $previousPosition = PreviousPosition::with(['employee', 'position'])
            ->where('employee_id', $id)
            ->whereHas('position', function ($query) {
                $query->whereNull('deleted_at');
            })
            ->whereNull('deleted_at')
            ->latest()
            ->get();

        return customResponse()
            ->data($previousPosition)
            ->message('Previous Position successfully found.')
            ->success()
            ->generate();
    }
}
