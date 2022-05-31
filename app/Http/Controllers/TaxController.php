<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use Validator;
use DB;
use Auth;
use Illuminate\Database\QueryException;
use App\Models\Tax;

class TaxController extends Controller
{
    // get taxes
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
        $taxes = Tax::
            whereNested(function ($query) use ($search) {
                $query->where('tax_desc', 'LIKE', $search)
                    ->orWhere('fixed_rate', 'LIKE', $search)
                    ->orWhere('fixed_amount', 'LIKE', $search)
                    ->orWhere('reference_table', 'LIKE', $search);
            })
            ->when($sortBy, function ($query, $sortBy) {
                foreach($sortBy as $column => $order)
                    $query->orderBy($column, $order);
            })
            ->paginate($perPage);

        $this->logActivity(
            'Tax API', 
            'View Taxes', 
            'Success in Getting Taxes.'
        );

        return customResponse()
            ->message('Success in Getting Taxes.')
            ->data($taxes)
            ->success()
            ->generate();
    }

    // create tax
    public function store(Request $request)
    {
        // request validation
        $validator = Validator::make($request->all(), [
            'tax_desc' => 'string|unique:tbl_taxes,tax_desc|required',
            'fixed_rate' => 'numeric|between:0,1|required_if:fixed_amount,0',
            'fixed_amount' => 'numeric|gte:0|required',
            'has_reference_table' => 'boolean|required',
            'reference_table' => 'exclude_if:has_reference_table,false',
            'activated' => 'boolean|required'
        ]);

        if ($validator->fails()) {
            $this->logActivity(
                'Tax API', 
                'Create Tax', 
                'Please fill out the fields properly.'
            );

            return customResponse()
                ->message('Please fill out the fields properly.')
                ->failed()
                ->errors($validator->errors())
                ->generate();
        }
        
        // create
        $tax = Tax::create($request->all());

        $tax->created_by = Auth::user()->user_id;
        $tax->save();

        $this->logActivity(
            'Tax API', 
            'Create Tax', 
            'Success in Creating Tax.'
        );

        return customResponse()
            ->message('Success in Creating Tax.')
            ->success(201)
            ->generate();
    }

    // update tax
    public function update(Request $request, $taxId)
    {
        // resource validation
        $tax = Tax::find($taxId);

        if ($tax === null) {
            $this->logActivity(
                'Tax API',
                'Update Tax',
                'Tax not found.'
            );

            return customResponse()
                ->message('Tax not found.')
                ->success(404)
                ->generate();
        }

        // request validation   
        $validator = Validator::make($request->all(), [
            'tax_desc' => "string|unique:tbl_taxes,tax_desc,{$taxId},tax_id|required",
            'fixed_rate' => 'numeric|between:0,1|required_if:fixed_amount,0',
            'fixed_amount' => 'numeric|gte:0|required',
            'has_reference_table' => 'boolean|required',
            'reference_table' => 'exclude_if:has_reference_table,false',
            'activated' => 'boolean|required'
        ]);

        if ($validator->fails()) {
            $this->logActivity(
                'Tax API', 
                'Update Tax', 
                'Please fill out the fields properly.'
            );

            return customResponse()
                ->message('Please fill out the fields properly.')
                ->failed()
                ->errors($validator->errors())
                ->generate();
        }

        // Update
        $tax->update($request->all());

        $tax->updated_by = Auth::user()->user_id;
        $tax->save();

        $this->logActivity(
            'Tax API', 
            'Update Tax', 
            'Success in Updating Tax.'
        );

        return customResponse()
            ->message('Success in Updating Tax.')
            ->success()
            ->generate();
    }

    // delete tax
    public function destroy(Request $request, $taxId)
    {
        // resource validation
        $tax = Tax::find($taxId);

        if ($tax === null) {
            $this->logActivity(
                'Tax API',
                'Delete Tax',
                'Tax not found.'
            );

            return customResponse()
                ->message('Tax not found.')
                ->success(404)
                ->generate();
        }

        // delete
        $tax->deleted_by = Auth::user()->user_id;
        $tax->save();

        $tax->delete();

        $this->logActivity(
            'Tax API',
            'Delete Tax',
            'Success in Deleting Tax.'
        );

        return customResponse()
            ->message('Success in Deleting Tax')
            ->success()
            ->generate();
    }

    // get tax
    public function show(Request $request, $taxId)
    {
        // resource validation
        $tax = Tax::find($taxId);

        if ($tax === null) {
            $this->logActivity(
                'Tax API',
                'Get Tax',
                'Tax not found.'
            );

            return customResponse()
                ->message('Tax not found.')
                ->failed(404)
                ->generate();
        }

        // get

        $this->logActivity(
            'Tax API',
            'Get Tax',
            'Success in Getting Tax.'
        );

        return customResponse()
            ->message('Success in Getting Tax.')
            ->data($tax)
            ->success()
            ->generate();
    }

    // activate tax
    public function activate(Request $request, $taxId)
    {
        // resource validation
        $tax = Tax::find($taxId);

        if ($tax === null) {
            $this->logActivity(
                'Tax API',
                'Activate Tax',
                'Tax not found.'
            );

            return customResponse()
                ->message('Tax not found.')
                ->success(404)
                ->generate();
        }

        // activate
        $tax->update(['activated' => true]);

        $tax->updated_by = Auth::user()->user_id;
        $tax->save();

        $this->logActivity(
            'Tax API',
            'Activate Tax',
            'Success in Activating Tax.'
        );

        return customResponse()
            ->message('Success in Activating Tax.')
            ->success()
            ->generate();
    }
    
    // deactivate tax
    public function deactivate(Request $request, $taxId)
    {
        // resource validation
        $tax = Tax::find($taxId);

        if ($tax === null) {
            $this->logActivity(
                'Tax API',
                'Deactivate Tax',
                'Tax not found.'
            );

            return customResponse()
                ->message('Tax not found.')
                ->success(404)
                ->generate();
        }

        // activate
        $tax->update(['activated' => false]);

        $tax->updated_by = Auth::user()->user_id;
        $tax->save();

        $this->logActivity(
            'Tax API',
            'Deactivate Tax',
            'Success in Deactivating Tax.'
        );

        return customResponse()
            ->message('Success in Deactivating Tax.')
            ->success()
            ->generate();
    }

    // get active taxes
    public function getActives(Request $request)
    {
        // get
        $tax = Tax::where('activated', true)->get();

        $this->logActivity(
            'Tax API',
            'Get Active Tax',
            'Success in Getting Active Taxes.'
        );

        return customResponse()
            ->message('Success in Getting Active Taxes.')
            ->data($tax)
            ->success()
            ->generate();
    }
}
