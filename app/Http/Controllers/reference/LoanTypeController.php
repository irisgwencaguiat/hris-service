<?php

namespace App\Http\Controllers\reference;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Log;
use Validator;
use DB;
use Auth;
use Illuminate\Database\QueryException;
use App\Models\References\LoanType;

class LoanTypeController extends Controller
{
    // get loan types
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
        $loanTypes = LoanType::
            whereNested(function ($query) use ($search) {
                $query->where('loan_type_name', 'LIKE', $search)
                    ->orWhere('loan_type_desc', 'LIKE', $search);
            })
            ->when($sortBy, function ($query, $sortBy) {
                foreach($sortBy as $column => $order)
                    $query->orderBy($column, $order);
            })
            ->paginate($perPage);

        $this->logActivity(
            'Loan Type Reference', 
            'View Loan Types', 
            'Success in Getting Loan Types.'
        );

        return customResponse()
            ->message('Success in Getting Loan Types.')
            ->data($loanTypes)
            ->success()
            ->generate();
    }

    // create loan type
    public function store(Request $request)
    {
        // request validation
        $validator = Validator::make($request->all(), [
            'loan_type_name' => 'unique:ref_loan_types,loan_type_name|string|required',
            'loan_type_desc' => 'string|required'
        ]);

        if ($validator->fails()) {
            $this->logActivity(
                'Loan Type Reference', 
                'Create Loan Type', 
                'Please fill out the fields properly.'
            );

            return customResponse()
                ->message('Please fill out the fields properly.')
                ->failed()
                ->errors($validator->errors())
                ->generate();
        }
        
        // create
        $loanType = LoanType::create($request->all());

        $loanType->created_by = Auth::user()->user_id;
        $loanType->save();

        $this->logActivity(
            'Loan Type Reference', 
            'Create Loan Type', 
            'Success in Creating Loan Type.'
        );

        return customResponse()
            ->message('Success in Creating Loan Type.')
            ->success(201)
            ->generate();
    }

    // update loan type
    public function update(Request $request, $loanTypeCode)
    {
        // resource validation
        $loanType = LoanType::find($loanTypeCode);

        if ($loanType === null) {
            $this->logActivity(
                'Loan Type Reference',
                'Update Loan Type',
                'Loan Type not found.'
            );

            return customResponse()
                ->message('Loan Type not found.')
                ->success(404)
                ->generate();
        }

        // request validation   
        $validator = Validator::make($request->all(), [
            'loan_type_name' => "unique:ref_loan_types,loan_type_name,{$loanTypeCode},loan_type_id|
                string|required",
            'loan_type_desc' => 'string|required'
        ]);

        if ($validator->fails()) {
            $this->logActivity(
                'Loan Type Reference', 
                'Update Loan Type', 
                'Please fill out the fields properly.'
            );

            return customResponse()
                ->message('Please fill out the fields properly.')
                ->failed()
                ->errors($validator->errors())
                ->generate();
        }

        // Update
        $loanType->update($request->all());

        $loanType->updated_by = Auth::user()->user_id;
        $loanType->save();

        $this->logActivity(
            'Loan Type Reference', 
            'Update Loan Type', 
            'Success in Updating Loan Type.'
        );

        return customResponse()
            ->message('Success in Updating Loan Type.')
            ->success()
            ->generate();
    }

    // delete loan type
    public function destroy(Request $request, $loanTypeCode)
    {
        // resource validation
        $loanType = LoanType::find($loanTypeCode);

        if ($loanType === null) {
            $this->logActivity(
                'Loan Type Reference',
                'Delete Loan Type',
                'Loan Type not found.'
            );

            return customResponse()
                ->message('Loan Type not found.')
                ->success(404)
                ->generate();
        }

        // delete
        $loanType->deleted_by = Auth::user()->user_id;
        $loanType->save();

        $loanType->delete();

        $this->logActivity(
            'Loan Type Reference',
            'Delete Loan Type',
            'Success in Deleting Loan Type.'
        );

        return customResponse()
            ->message('Success in Deleting Loan Type')
            ->success()
            ->generate();
    }

    // get loan type
    public function show(Request $request, $loanTypeCode)
    {
        // resource validation
        $loanType = LoanType::find($loanTypeCode);

        if ($loanType === null) {
            $this->logActivity(
                'Loan Type Reference',
                'Get Loan Type',
                'Loan Type not found.'
            );

            return customResponse()
                ->message('Loan Type not found.')
                ->failed(404)
                ->generate();
        }

        // get

        $this->logActivity(
            'Loan Type Reference',
            'Get Loan Type',
            'Success in Getting Loan Type.'
        );

        return customResponse()
            ->message('Success in Getting Loan Type.')
            ->data($loanType)
            ->success()
            ->generate();
    }
}
