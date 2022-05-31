<?php

namespace App\Http\Controllers\reference;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Reference\Page;
use Log;

class PageController extends Controller
{
    public function __construct()
    {
        // $this->middleware('scope:pages::get')->only('index');
        // $this->middleware('scope:page::create')->only('store');
        // $this->middleware('scope:page::update')->only('update');
        // $this->middleware('scope:page::get')->only('show');
        // $this->middleware('scope:page::delete')->only('delete');
    }

    public function index(Request $request)
    {
        $apiParam = apiParam()
            ->request($request)
            ->generate();
        
        // get data
        $Pages = Page::
            with('type')
            ->join(
                'ref_page_types', 
                'ref_page_types.page_type_id', 
                'ref_pages.page_type_id'
            )
            ->searchExtras([
                'ref_page_types.page_type_name'
            ])
            ->usingAPIParam($apiParam);

        return customResponse()
            ->success()
            ->message('Success in Getting Pages.')
            ->data($Pages)
            ->logName('Get Pages')
            ->logDesc("Total results: {$Pages->count()}")
            ->generateWithLog();
    }

    public function show(Request $request, $pageId)
    {
        // resource validation
        $page = Page::
            with('type')
            ->find($pageId);

        if ($page === null) {
            return customResponse()
                ->failed(404)
                ->message('Page not found.')
                ->logName('Get Page')
                ->generateWithLog();
        }

        return customResponse()
            ->success()
            ->message('Success in Getting Page.')
            ->data($page)
            ->logName('Get Page')
            ->generateWithLog();
    }

    public function store(Request $request)
    {
        // request validation
        $validator = Validator::make(
            $request->all(),
            [
                'page_name' => 
                    'uniqueOfMultiple:ref_pages,route_name|
                    required',
                
                'page_type_id' => 'exists:ref_page_types,page_type_id|required',
                'route_name' => 'string|required',
                'page_icon' => 'string|required'
            ],
            [
                'page_name.unique_of_multiple' => 
                    'The page name, page type, and route name have already been taken.'
            ]
        );

        if ($validator->fails()) {
            return customResponse()
                ->failed()
                ->message('Please fill out the fields properly.')
                ->errors($validator->errors())
                ->logName('Create Page')
                ->generateWithLog();
        }

        // create
        $page = Page::create($request->all());

        return customResponse()
            ->success(201)
            ->message('Success in Creating Page.')
            ->logName('Create Page')
            ->generateWithLog();
    }

    public function update(Request $request, $pageId)
    {
        // resource validation
        $page = Page::find($pageId);

        if ($page === null) {
            return customResponse()
                ->failed(404)
                ->message('Page not found.')
                ->logName('Update Page')
                ->generateWithLog();
        }

        // request validation
        $validator = Validator::make(
            $request->all(), 
            [
                'page_name' => 
                    "uniqueOfMultiple:ref_pages,route_name,except,page_id,{$pageId}|
                    required",
                
                'page_type_id' => 'exists:ref_page_types,page_type_id|required',
                'route_name' => 'string|required',
                'page_icon' => 'string|required'
            ],
            [
                'page_name.unique_of_multiple' => 
                    'The page name, page type, and route name have already been taken.'
            ]
        );

        if ($validator->fails()) {
            return customResponse()
                ->failed()
                ->message('Please fill out the fields properly.')
                ->errors($validator->errors())
                ->logName('Update Page')
                ->generateWithLog();
        }

        // update
        $page->update($request->all());

        return customResponse()
            ->success()
            ->message('Success in Updating Page.')
            ->logName('Update Page')
            ->generateWithLog();
    }

    public function destroy(Request $request, $pageId)
    {
        // resource validation
        $page = Page::find($pageId);

        if ($page === null) {
            return customResponse()
                ->failed(404)
                ->message('Page not found.')
                ->logName('Delete Page')
                ->generateWithLog();
        }

        // if already assigned, discontinue
        if ($page->no_of_assigned_pages > 0) {
            return customResponse()
                ->failed(400)
                ->message('Page is already assigned.')
                ->logName('Delete Page')
                ->generateWithLog();
        }

        // delete
        $page->delete();

        return customResponse()
            ->success()
            ->message('Success in Deleting Page.')
            ->logName('Delete Page')
            ->generateWithLog();
    }
    
}