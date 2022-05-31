<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use Validator;
use DB;
use Auth;
use Illuminate\Database\QueryException;
use App\Models\TrainLawTax;

class TrainLawTaxController extends Controller
{
    // get train law tax taxes
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
        $trainLawTaxes = TrainLawTax::
            whereNested(function ($query) use ($search) {
                $query->where('annual_income_upper_boundary', 'LIKE', $search)
                    ->orWhere('annual_income_lower_boundary', 'LIKE', $search)
                    ->orWhere('tax_rate', 'LIKE', $search)
                    ->orWhere('additional_tax_amount', 'LIKE', $search);
            })
            ->when($sortBy, function ($query, $sortBy) {
                foreach($sortBy as $column => $order)
                    $query->orderBy($column, $order);
            })
            ->paginate($perPage);

        $this->logActivity(
            'Train Law Tax API', 
            'View Train Law Tax Taxes', 
            'Success in Getting Train Law Tax Taxes.'
        );

        return customResponse()
            ->message('Success in Getting Train Law Tax Taxes.')
            ->data($trainLawTaxes)
            ->success()
            ->generate();
    }

    // create train law tax
    public function store(Request $request)
    {
        // request validation
        $validator = Validator::make($request->all(), [
            'train_law_id' => 'exists:tbl_train_laws,train_law_id,deleted_at,NULL|required',
            'lower_boundary_and_below' => 'boolean|required',
            'upper_boundary_and_above' => 'boolean|required',
            'annual_income_upper_boundary' => 'numeric|required_if:upper_boundary_and_above,true',
            'annual_income_lower_boundary' => 'numeric|required_if:lower_boundary_and_below,true',
            'tax_rate' => 'numeric|between:0,1|required',
            'additional_tax_amount' => 'numeric|nullable'
        ]);

        if ($validator->fails()) {
            $this->logActivity(
                'Train Law Tax API', 
                'Create Train Law Tax', 
                'Please fill out the fields properly.'
            );

            return customResponse()
                ->message('Please fill out the fields properly.')
                ->failed()
                ->errors($validator->errors())
                ->generate();
        }

        // create
        $trainLawTax = TrainLawTax::create($request->all());

        $trainLawTax->created_by = Auth::user()->user_id;
        $trainLawTax->save();

        $this->logActivity(
            'Train Law Tax API', 
            'Create Train Law Tax', 
            'Success in Creating Train Law Tax.'
        );

        return customResponse()
            ->message('Success in Creating Train Law Tax.')
            ->success(201)
            ->generate();
    }

    // update train law tax
    public function update(Request $request, $trainLawTaxId)
    {
        // resource validation
        $trainLawTax = TrainLawTax::find($trainLawTaxId);

        if ($trainLawTax === null) {
            $this->logActivity(
                'Train Law Tax API',
                'Update Train Law Tax',
                'Train Law Tax not found.'
            );

            return customResponse()
                ->message('Train Law Tax not found.')
                ->success(404)
                ->generate();
        }

        // request validation   
        $validator = Validator::make($request->all(), [
            'train_law_id' => 'exists:tbl_train_laws,train_law_id,deleted_at,NULL|required',
            'lower_boundary_and_below' => 'boolean|required',
            'upper_boundary_and_above' => 'boolean|required',
            'annual_income_upper_boundary' => 'numeric|required_if:upper_boundary_and_above,true',
            'annual_income_lower_boundary' => 'numeric|required_if:lower_boundary_and_below,true',
            'tax_rate' => 'numeric|between:0,1|required',
            'additional_tax_amount' => 'numeric|nullable'
        ]);

        if ($validator->fails()) {
            $this->logActivity(
                'Train Law Tax API', 
                'Update Train Law Tax', 
                'Please fill out the fields properly.'
            );

            return customResponse()
                ->message('Please fill out the fields properly.')
                ->failed()
                ->errors($validator->errors())
                ->generate();
        }

        // Update
        $trainLawTax->update($request->all());

        $trainLawTax->updated_by = Auth::user()->user_id;
        $trainLawTax->save();

        $this->logActivity(
            'Train Law Tax API', 
            'Update Train Law Tax', 
            'Success in Updating Train Law Tax.'
        );

        return customResponse()
            ->message('Success in Updating Train Law Tax.')
            ->success()
            ->generate();
    }

    // delete train law tax
    public function destroy(Request $request, $trainLawTaxId)
    {
        // resource validation
        $trainLawTax = TrainLawTax::find($trainLawTaxId);

        if ($trainLawTax === null) {
            $this->logActivity(
                'Train Law Tax API',
                'Delete Train Law Tax',
                'Train Law Tax not found.'
            );

            return customResponse()
                ->message('Train Law Tax not found.')
                ->success(404)
                ->generate();
        }

        // delete
        $trainLawTax->deleted_by = Auth::user()->user_id;
        $trainLawTax->save();

        $trainLawTax->delete();

        $this->logActivity(
            'Train Law Tax API',
            'Delete Train Law Tax',
            'Success in Deleting Train Law Tax.'
        );

        return customResponse()
            ->message('Success in Deleting Train Law Tax')
            ->success()
            ->generate();
    }

    // get train law tax
    public function show(Request $request, $trainLawTaxId)
    {
        // resource validation
        $trainLawTax = TrainLawTax::find($trainLawTaxId);

        if ($trainLawTax === null) {
            $this->logActivity(
                'Train Law Tax API',
                'Get Train Law Tax',
                'Train Law Tax not found.'
            );

            return customResponse()
                ->message('Train Law Tax not found.')
                ->failed(404)
                ->generate();
        }

        // get

        $this->logActivity(
            'Train Law Tax API',
            'Get Train Law Tax',
            'Success in Getting Train Law Tax.'
        );

        return customResponse()
            ->message('Success in Getting Train Law Tax.')
            ->data($trainLawTax)
            ->success()
            ->generate();
    }
}
