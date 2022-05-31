<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Auth;
use App\Models\UserTypePage;
use App\Models\User;
use Log;

class UserTypePageController extends Controller
{
    public function __construct()
    {
        // $this->middleware('scope:user-type-pages::get')->only('index');
        // $this->middleware('scope:user-type-page::create')->only('store');
        // $this->middleware('scope:user-type-page::update')->only('update');
        // $this->middleware('scope:user-type-page::get')->only('show');
        // $this->middleware('scope:user-type-page::delete')->only('delete');
    }

    public function index(Request $request)
    {
        $apiParam = apiParam()
            ->request($request)
            ->filterables([
                'user_type_id',
                'user_classification_id',
            ])
            ->filterColumns([
                'user_type_id' => 'tbl_user_type_pages.user_type_id',
                'user_classification_id' => 'tbl_user_type_pages.user_classification_id'
            ])
            ->generate();

        // get data
        $userTypePages = UserTypePage::
            with(
                'userType',
                'userClassification',
                'page',
                'page.type',
            
                'parentUserTypePage',
                'parentUserTypePage.page',
                'parentUserTypePage.page.type'
            )
            ->join(
                'tbl_user_types',
                'tbl_user_types.user_type_id',
                'tbl_user_type_pages.user_type_id'
            )
            ->join(
                'ref_user_classifications',
                'ref_user_classifications.user_classification_id',
                'tbl_user_type_pages.user_classification_id'
            )
            ->join(
                'ref_pages',
                'ref_pages.page_id',
                'tbl_user_type_pages.page_id'
            )
            ->searchExtras([
                'tbl_user_types.user_type_name',
                'ref_user_classifications.user_classification_name',
                'ref_pages.page_name',
                'ref_pages.route_name',
                'ref_pages.page_icon'
            ])
            ->orderByRaw("
                COALESCE(parent_user_type_page_id, user_type_page_id), 
                parent_user_type_page_id IS NOT NULL, 
                user_type_page_id
            ")
            ->usingAPIParam($apiParam);

        return customResponse()
            ->success()
            ->message('Success in Getting User Type Pages.')
            ->data($userTypePages)
            ->logName('Get User Type Pages')
            ->logDesc("Total results: {$userTypePages->count()}")
            ->generateWithLog();
    }

    public function show(Request $request, $userTypePageId)
    {
        // resource validation
        $userTypePage = UserTypePage::
            with(
                'userType',
                'userClassification',
                'page',
                'page.type',
            
                'parentUserTypePage',
                'parentUserTypePage.page',
                'parentUserTypePage.page.type'
            )
            ->find($userTypePageId);

        if ($userTypePage === null) {
            return customResponse()
                ->failed(404)
                ->message('User Type Page not found.')
                ->logName('Get User Type Page')
                ->generateWithLog();
        }

        return customResponse()
            ->success()
            ->message('Success in Getting User Type Page.')
            ->data($userTypePage)
            ->logName('Get User Type Page')
            ->generateWithLog();
    }

    public function store(Request $request)
    {
        // request validation
        $validator = Validator::make(
            $request->all(),
            [
                'user_type_id' =>
                    'uniqueOfMultiple:tbl_user_type_pages,user_classification_id,page_id|
                    exists:tbl_user_types,user_type_id|
                    required',

                'user_classification_id' => 
                    'exists:ref_user_classifications,user_classification_id|
                    required',
                
                'page_id' => 'exists:ref_pages,page_id|required',
                'order_no' => 'numeric|required',
                'parent_user_type_page_id' => 
                    'exists:tbl_user_type_pages,user_type_page_id|
                    nullable',

                'has_sub_pages' => 'boolean|required',
                'is_activated' => 'boolean|required'
            ],
            [
                'user_type_id.unique_of_multiple' => 
                    'The user type, user classification, and page have already been taken.'
            ]
        );

        if ($validator->fails()) {
            return customResponse()
                ->failed()
                ->message('Please fill out the fields properly.')
                ->errors($validator->errors())
                ->logName('Create User Type Page')
                ->generateWithLog();
        }

        // create
        $userTypePage = UserTypePage::create($request->all());

        return customResponse()
            ->success(201)
            ->message('Success in Creating User Type Page.')
            ->logName('Create User Type Page')
            ->generateWithLog();
    }

    public function update(Request $request, $userTypePageId)
    {
        // resource validation
        $userTypePage = UserTypePage::find($userTypePageId);

        if ($userTypePage === null) {
            return customResponse()
                ->failed(404)
                ->message('User Type Page not found.')
                ->logName('Update User Type Page')
                ->generateWithLog();
        }

        // request validation
        $validator = Validator::make(
            $request->all(), 
            [
                'user_type_id' =>
                    "uniqueOfMultiple:tbl_user_type_pages,user_classification_id,page_id,except,user_type_page_id,{$userTypePageId}|
                    exists:tbl_user_types,user_type_id|
                    required",

                'user_classification_id' => 
                    'exists:ref_user_classifications,user_classification_id|
                    required',
                
                'page_id' => 'exists:ref_pages,page_id|required',
                'order_no' => 'numeric|required',
                'parent_user_type_page_id' => 
                    'exists:tbl_user_type_pages,user_type_page_id|
                    nullable',

                'has_sub_pages' => 'boolean|required',
                'is_activated' => 'boolean|required'
            ],
            [
                'user_type_id.unique_of_multiple' => 
                    'The user type, user classification, and page have already been taken.'
            ]
        );

        if ($validator->fails()) {
            return customResponse()
                ->failed()
                ->message('Please fill out the fields properly.')
                ->errors($validator->errors())
                ->logName('Update User Type Page')
                ->generateWithLog();
        }

        // update
        $userTypePage->update($request->all());

        return customResponse()
            ->success()
            ->message('Success in Updating User Type Page.')
            ->logName('Update User Type Page')
            ->generateWithLog();
    }

    public function destroy(Request $request, $userTypePageId)
    {
        // resource validation
        $userTypePage = UserTypePage::find($userTypePageId);

        if ($userTypePage === null) {
            return customResponse()
                ->failed(404)
                ->message('User Type Page not found.')
                ->logName('Delete User Type Page')
                ->generateWithLog();
        }

        // delete
        $userTypePage->delete();

        return customResponse()
            ->success()
            ->message('Success in Deleting User Type Page.')
            ->logName('Delete User Type Page')
            ->generateWithLog();
    }
    
    // get auth user modules
    public function getAuthModules(Request $request)
    {
        $user = User::find(Auth::id());

        $modules = UserTypePage::
            with([
                'subPages' => function ($query) {
                    $query->where('is_activated', true);
                    $query->orderBy('order_no', 'ASC');
                },
                'page',
                'page.type',
                'subPages.page',
                'subPages.page.type'
            ])

            ->ofUserType($user->profile->user_type_id)
            ->ofUserClassification($user->profile->user_classification_id)

            ->withoutParentPage()
            ->activated()

            ->orderBy('order_no', 'ASC')
            ->get();

        return customResponse()
            ->success()
            ->message('Success in Getting Authenticated User Pages.')
            ->data($modules)
            ->logName('Authenticated User Pages')
            ->generateWithLog();    
    }

    // get default route
    public function getDefaultRoute(Request $request)
    {
        $user = User::with('profile')->find(Auth::id());

        // get data
        $userTypePages = UserTypePage::
            join(
                'ref_pages',
                'ref_pages.page_id',
                'tbl_user_type_pages.page_id'
            )
            ->where([
                'user_type_id' => $user->profile->user_type_id,
                'user_classification_id' => $user->profile->user_classification_id
            ])
            ->select(
                'ref_pages.page_name',
                'ref_pages.route_name',
            )
            ->orderByRaw("
                COALESCE(parent_user_type_page_id, user_type_page_id), 
                parent_user_type_page_id IS NOT NULL, 
                user_type_page_id
            ")
            ->get();

        foreach($userTypePages as $page)
        {
            if ($page->route_name) {
                return customResponse()
                    ->success()
                    ->data($page)
                    ->message('Success in Getting Default Route.')
                    ->logName('Get User Default Route')
                    ->generateWithLog();    
            }
        }

        return customResponse()
            ->failed()
            ->data((object)[
                'route_name' => ''
            ])
            ->message('Failed in Getting Default Route.')
            ->logName('Get User Default Route')
            ->generateWithLog();    
    }
}