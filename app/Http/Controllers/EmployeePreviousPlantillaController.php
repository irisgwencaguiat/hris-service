<?php

namespace App\Http\Controllers;

use App\Models\PreviousPlantilla;
use Illuminate\Http\Request;

class EmployeePreviousPlantillaController extends Controller
{
    public function show($id)
    {
        $previousPlantilla = PreviousPlantilla::with(['employee', 'plantilla'])
            ->where('employee_id', $id)
            ->whereHas('plantilla', function ($query) {
                $query->whereNull('deleted_at');
            })
            ->whereNull('deleted_at')
            ->latest()
            ->get();

        return customResponse()
            ->data($previousPlantilla)
            ->message('Previous Plantilla successfully found.')
            ->success()
            ->generate();
    }
}
