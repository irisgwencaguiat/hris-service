<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use Validator;
use DB;
use Auth;
use Illuminate\Database\QueryException;
use App\Models\Benefit;

class BenefitController extends Controller
{
    
    // get benefits
    public function index(Request $request)
    {
        $perPage = apiPerPage(intval($request->input('per_page', 5)), 5, 100);

        $search = ($request->has('search')) ? "%{$request->input('search')}%" : '%';

        $sortBy = apiSortBy($request->input('sort_by'), 
            $request->input('sort_desc'), 
            [], 
            ['created_at' => 'ASC']
        );
        
        // get data
        $benefits = Benefit::
            whereNested(function ($query) use ($search) {
                $query->where('benefit_desc', 'LIKE', $search)
                    ->orWhere('amount', 'LIKE', $search);
            })
            ->when($sortBy, function ($query, $sortBy) {
                foreach($sortBy as $column => $order)
                    $query->orderBy($column, $order);
            })
            ->paginate($perPage);

        $this->logActivity(
            'Benefit API', 
            'View Benefits', 
            'Success in Getting Benefits.'
        );

        return customResponse()
            ->message('Success in Getting Benefits.')
            ->data($benefits)
            ->success()
            ->generate();
    }

    // create benefit
    public function store(Request $request)
    {
        // request validation
        $validator = Validator::make($request->all(), [
            'benefit_desc' => 'unique:tbl_benefits,benefit_desc|string|required',
            'amount' => 'numeric|gte:0|required',
            'activated' => 'boolean|required'
        ]);

        if ($validator->fails()) {
            $this->logActivity(
                'Benefit API', 
                'Create Benefit', 
                'Please fill out the fields properly.'
            );

            return customResponse()
                ->message('Please fill out the fields properly.')
                ->failed()
                ->errors($validator->errors())
                ->generate();
        }

        // create
        $benefit = Benefit::create($request->all());

        $benefit->created_by = Auth::user()->user_id;
        $benefit->save();

        $this->logActivity(
            'Benefit API', 
            'Create Benefit', 
            'Success in Creating Benefit.'
        );

        return customResponse()
            ->message('Success in Creating Benefit.')
            ->success(201)
            ->generate();
    }

    // update benefit
    public function update(Request $request, $benefitId)
    {
        // resource validation
        $benefit = Benefit::find($benefitId);

        if ($benefit === null) {
            $this->logActivity(
                'Benefit API',
                'Update Benefit',
                'Benefit not found.'
            );

            return customResponse()
                ->message('Benefit not found.')
                ->success(404)
                ->generate();
        }

        // request validation   
        $validator = Validator::make($request->all(), [
            'benefit_desc' => "unique:tbl_benefits,benefit_desc,{$benefitId},benefit_id|
                string|required",
            'amount' => 'numeric|gte:0|required',
            'activated' => 'boolean|required'
        ]);

        if ($validator->fails()) {
            $this->logActivity(
                'Benefit API', 
                'Update Benefit', 
                'Please fill out the fields properly.'
            );

            return customResponse()
                ->message('Please fill out the fields properly.')
                ->failed()
                ->errors($validator->errors())
                ->generate();
        }

        // update
        $benefit->update($request->all());

        $benefit->updated_by = Auth::user()->user_id;
        $benefit->save();

        $this->logActivity(
            'Benefit API', 
            'Update Benefit', 
            'Success in Updating Benefit.'
        );

        return customResponse()
            ->message('Success in Updating Benefit.')
            ->success()
            ->generate();
    }

    // delete benefit
    public function destroy(Request $request, $benefitId)
    {
        // resource validation
        $benefit = Benefit::find($benefitId);

        if ($benefit === null) {
            $this->logActivity(
                'Benefit API',
                'Delete Benefit',
                'Benefit not found.'
            );

            return customResponse()
                ->message('Benefit not found.')
                ->success(404)
                ->generate();
        }

        // delete
        $benefit->deleted_by = Auth::user()->user_id;
        $benefit->save();

        $benefit->delete();

        $this->logActivity(
            'Benefit API',
            'Delete Benefit',
            'Success in Deleting Benefit.'
        );

        return customResponse()
            ->message('Success in Deleting Benefit')
            ->success()
            ->generate();
    }

    // get benefit
    public function show(Request $request, $benefitId)
    {
        // resource validation
        $benefit = Benefit::find($benefitId);

        if ($benefit === null) {
            $this->logActivity(
                'Benefit API',
                'Get Benefit',
                'Benefit not found.'
            );

            return customResponse()
                ->message('Benefit not found.')
                ->failed(404)
                ->generate();
        }

        // get

        $this->logActivity(
            'Benefit API',
            'Get Benefit',
            'Success in Getting Benefit.'
        );

        return customResponse()
            ->message('Success in Getting Benefit.')
            ->data($benefit)
            ->success()
            ->generate();
    }

    // activate benefit
    public function activate(Request $request, $benefitId)
    {
        // resource validation
        $benefit = Benefit::find($benefitId);

        if ($benefit === null) {
            $this->logActivity(
                'Benefit API',
                'Activate Benefit',
                'Benefit not found.'
            );

            return customResponse()
                ->message('Benefit not found.')
                ->success(404)
                ->generate();
        }

        // activate
        $benefit->update(['activated' => true]);

        $benefit->updated_by = Auth::user()->user_id;
        $benefit->save();

        $this->logActivity(
            'Benefit API',
            'Activate Benefit',
            'Success in Activating Benefit.'
        );

        return customResponse()
            ->message('Success in Activating Benefit.')
            ->success()
            ->generate();
    }
    
    // deactivate benefit
    public function deactivate(Request $request, $benefitId)
    {
        // resource validation
        $benefit = Benefit::find($benefitId);

        if ($benefit === null) {
            $this->logActivity(
                'Benefit API',
                'Deactivate Benefit',
                'Benefit not found.'
            );

            return customResponse()
                ->message('Benefit not found.')
                ->success(404)
                ->generate();
        }

        // activate
        $benefit->update(['activated' => false]);

        $benefit->updated_by = Auth::user()->user_id;
        $benefit->save();

        $this->logActivity(
            'Benefit API',
            'Deactivate Benefit',
            'Success in Deactivating Benefit.'
        );

        return customResponse()
            ->message('Success in Deactivating Benefit.')
            ->success()
            ->generate();
    }

    // get active benefits
    public function getActives(Request $request)
    {
        // get
        $benefit = Benefit::where('activated', true)->get();
        
        $this->logActivity(
            'Benefit API',
            'Get Active Benefits',
            'Success in Getting Active Benefits.'
        );

        return customResponse()
            ->message('Success in Getting Active Benefits.')
            ->data($benefit)
            ->success()
            ->generate();
    }
}
