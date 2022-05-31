<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use Validator;
use DB;
use Auth;
use Illuminate\Database\QueryException;
use App\Models\PhilhealthRate;

class PhilhealthRateController extends Controller
{
    // get philhealth rates
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
        $philhealthRates = PhilhealthRate::
            whereNested(function ($query) use ($search) {
                $query->where('year', 'LIKE', $search)
                    ->orWhere('premium_rate', 'LIKE', $search);
            })
            ->when($sortBy, function ($query, $sortBy) {
                foreach($sortBy as $column => $order)
                    $query->orderBy($column, $order);
            })
            ->paginate($perPage);

        $this->logActivity(
            'Philhealth Rate API', 
            'View Philhealth Rates', 
            'Success in Getting Philhealth Rates.'
        );

        return customResponse()
            ->message('Success in Getting Philhealth Rates.')
            ->data($philhealthRates)
            ->success()
            ->generate();
    }

    // create philhealth rate
    public function store(Request $request)
    {
        // request validation
        $validator = Validator::make($request->all(), [
            'year' => 'integer|required',
            'premium_rate' => 'numeric|between:0,1|required',
            'ps_rate' => 'numeric|between:0,1|required',
            'gs_rate' => 'numeric|between:0,1|required',
            'activated' => 'boolean|required'
        ]);

        if ($validator->fails()) {
            $this->logActivity(
                'Philhealth Rate API', 
                'Create Philhealth Rate', 
                'Please fill out the fields properly.'
            );

            return customResponse()
                ->message('Please fill out the fields properly.')
                ->failed()
                ->errors($validator->errors())
                ->generate();
        }

        // if activated, deactivate activated
        if ($request->input('activated') == true) {
            PhilhealthRate::where('activated', true)->update([
                'activated' => false,
                'updated_by' => Auth::user()->user_id
            ]);
        }
        
        // create
        $philhealthRate = PhilhealthRate::create($request->all());

        $philhealthRate->created_by = Auth::user()->user_id;
        $philhealthRate->save();

        $this->logActivity(
            'Philhealth Rate API', 
            'Create Philhealth Rate', 
            'Success in Creating Philhealth Rate.'
        );

        return customResponse()
            ->message('Success in Creating Philhealth Rate.')
            ->success(201)
            ->generate();
    }

    // update philhealth rate
    public function update(Request $request, $phrId)
    {
        // resource validation
        $philhealthRate = PhilhealthRate::find($phrId);

        if ($philhealthRate === null) {
            $this->logActivity(
                'Philhealth Rate API',
                'Update Philhealth Rate',
                'Philhealth Rate not found.'
            );

            return customResponse()
                ->message('Philhealth Rate not found.')
                ->success(404)
                ->generate();
        }

        // request validation   
        $validator = Validator::make($request->all(), [
            'year' => 'integer|required',
            'premium_rate' => 'numeric|between:0,1|required',
            'ps_rate' => 'numeric|between:0,1|required',
            'gs_rate' => 'numeric|between:0,1|required',
            'activated' => 'boolean|required'
        ]);

        if ($validator->fails()) {
            $this->logActivity(
                'Philhealth Rate API', 
                'Update Philhealth Rate', 
                'Please fill out the fields properly.'
            );

            return customResponse()
                ->message('Please fill out the fields properly.')
                ->failed()
                ->errors($validator->errors())
                ->generate();
        }

        // if activated, deactivate activated other than itself
        if ($request->input('activated') == true) {
            PhilhealthRate::where('activated', true)
                ->where('philhealth_rate_id', '!=', $phrId)
                ->update([
                    'activated' => false,
                    'updated_by' => Auth::user()->user_id
                ]);
        }

        // Update
        $philhealthRate->update($request->all());

        $philhealthRate->updated_by = Auth::user()->user_id;
        $philhealthRate->save();

        $this->logActivity(
            'Philhealth Rate API', 
            'Update Philhealth Rate', 
            'Success in Updating Philhealth Rate.'
        );

        return customResponse()
            ->message('Success in Updating Philhealth Rate.')
            ->success()
            ->generate();
    }

    // delete philhealth rate
    public function destroy(Request $request, $phrId)
    {
        // resource validation
        $philhealthRate = PhilhealthRate::find($phrId);

        if ($philhealthRate === null) {
            $this->logActivity(
                'Philhealth Rate API',
                'Delete Philhealth Rate',
                'Philhealth Rate not found.'
            );

            return customResponse()
                ->message('Philhealth Rate not found.')
                ->success(404)
                ->generate();
        }

        // delete
        $philhealthRate->deleted_by = Auth::user()->user_id;
        $philhealthRate->save();

        $philhealthRate->delete();

        $this->logActivity(
            'Philhealth Rate API',
            'Delete Philhealth Rate',
            'Success in Deleting Philhealth Rate.'
        );

        return customResponse()
            ->message('Success in Deleting Philhealth Rate')
            ->success()
            ->generate();
    }

    // get philhealth rate
    public function show(Request $request, $phrId)
    {
        // resource validation
        $philhealthRate = PhilhealthRate::find($phrId);

        if ($philhealthRate === null) {
            $this->logActivity(
                'Philhealth Rate API',
                'Get Philhealth Rate',
                'Philhealth Rate not found.'
            );

            return customResponse()
                ->message('Philhealth Rate not found.')
                ->failed(404)
                ->generate();
        }

        // get

        $this->logActivity(
            'Philhealth Rate API',
            'Get Philhealth Rate',
            'Success in Getting Philhealth Rate.'
        );

        return customResponse()
            ->message('Success in Getting Philhealth Rate.')
            ->data($philhealthRate)
            ->success()
            ->generate();
    }

    // activate philhealth rate
    public function activate(Request $request, $phrId)
    {
        // resource validation
        $philhealthRate = PhilhealthRate::find($phrId);

        if ($philhealthRate === null) {
            $this->logActivity(
                'Philhealth Rate API',
                'Activate Philhealth Rate',
                'Philhealth Rate not found.'
            );

            return customResponse()
                ->message('Philhealth Rate not found.')
                ->success(404)
                ->generate();
        }

        // if activated, deactivate activated other than itself
        PhilhealthRate::where('activated', true)
            ->where('philhealth_rate_id', '!=', $phrId)
            ->update([
                'activated' => false,
                'updated_by' => Auth::user()->user_id
            ]);

        // activate
        $philhealthRate->update(['activated' => true]);

        $philhealthRate->updated_by = Auth::user()->user_id;
        $philhealthRate->save();

        $this->logActivity(
            'Philhealth Rate API',
            'Activate Philhealth Rate',
            'Success in Activating Philhealth Rate.'
        );

        return customResponse()
            ->message('Success in Activating Philhealth Rate.')
            ->success()
            ->generate();
    }
    
    // deactivate philhealth rate
    public function deactivate(Request $request, $phrId)
    {
        // resource validation
        $philhealthRate = PhilhealthRate::find($phrId);

        if ($philhealthRate === null) {
            $this->logActivity(
                'Philhealth Rate API',
                'Deactivate Philhealth Rate',
                'Philhealth Rate not found.'
            );

            return customResponse()
                ->message('Philhealth Rate not found.')
                ->success(404)
                ->generate();
        }

        // activate
        $philhealthRate->update(['activated' => false]);

        $philhealthRate->updated_by = Auth::user()->user_id;
        $philhealthRate->save();

        $this->logActivity(
            'Philhealth Rate API',
            'Deactivate Philhealth Rate',
            'Success in Deactivating Philhealth Rate.'
        );

        return customResponse()
            ->message('Success in Deactivating Philhealth Rate.')
            ->success()
            ->generate();
    }

    // get active philhealth rate
    public function getActive(Request $request)
    {
        // resource validation
        $philhealthRate = PhilhealthRate::where('activated', true)->first();

        if ($philhealthRate === null) {
            $this->logActivity(
                'Philhealth Rate API',
                'Get Active Philhealth Rate',
                'Philhealth Rate not found.'
            );

            return customResponse()
                ->message('Philhealth Rate not found.')
                ->success(404)
                ->generate();
        }
        
        // get

        $this->logActivity(
            'Philhealth Rate API',
            'Get Active Philhealth Rate',
            'Success in Getting Active Philhealth Rate.'
        );

        return customResponse()
            ->message('Success in Getting Active Philhealth Rate.')
            ->data($philhealthRate)
            ->success()
            ->generate();
    }
}
