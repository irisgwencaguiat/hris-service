<?php

namespace App\Http\Controllers\reference;

use App\Http\Controllers\Controller;
use App\Models\RefCardType;
use Illuminate\Http\Request;

class CardTypeController extends Controller
{
    public function index()
    {
        return customResponse()
            ->data(RefCardType::get())
            ->message('Card type references successfully got.')
            ->success()
            ->generate();
    }
}
