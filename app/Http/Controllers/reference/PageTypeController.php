<?php

namespace App\Http\Controllers\reference;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Reference\PageType;
use App\Models\User;

class PageTypeController extends Controller
{
    public function __construct()
    {
        // $this->middleware('scope:page-types::get')->only('index');
        // $this->middleware('scope:page-type::create')->only('store');
        // $this->middleware('scope:page-type::update')->only('update');
        // $this->middleware('scope:page-type::get')->only('show');
        // $this->middleware('scope:page-type::delete')->only('delete');
    }

    public function index(Request $request)
    {
        $apiParam = apiParam()
            ->request($request)
            ->generate();
        
        // get data
        $pageTypes = PageType::usingAPIParam($apiParam);

        return customResponse()
            ->success()
            ->message('Success in Getting Page Types.')
            ->data($pageTypes)
            ->logName('Get Page Types')
            ->logDesc("Total results: {$pageTypes->count()}")
            ->generateWithLog();
    }
}