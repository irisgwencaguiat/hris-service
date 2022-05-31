<?php

namespace App\Http\Controllers\reference;

use App\Http\Controllers\Controller;
use App\Models\RefAppointmentNature;
use Illuminate\Http\Request;

class AppointmentNature extends Controller
{
    public function index()
    {
        return customResponse()
            ->data(RefAppointmentNature::get())
            ->message('Appointment Nature references successfully got.')
            ->success()
            ->generate();
    }
}
