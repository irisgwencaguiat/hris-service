<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use Validator;
use DB;
use Auth;
use Illuminate\Database\QueryException;
use App\Models\TrainLaw;

class TrainLawController extends Controller
{
    // get train laws
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
        $trainLaws = TrainLaw::
            with(
                'taxes',
                'affecteds',
                'affecteds.employmentStatus',
                'affecteds.position'
            )
            ->whereNested(function ($query) use ($search) {
                $query->where('train_law_desc', 'LIKE', $search)
                    ->orWhere('year_start', 'LIKE', $search)
                    ->orWhere('year_end', 'LIKE', $search);
            })
            ->when($sortBy, function ($query, $sortBy) {
                foreach($sortBy as $column => $order)
                    $query->orderBy($column, $order);
            })
            ->paginate($perPage);

        $this->logActivity(
            'Train Law API', 
            'View Train Laws', 
            'Success in Getting Train Laws.'
        );

        return customResponse()
            ->message('Success in Getting Train Laws.')
            ->data($trainLaws)
            ->success()
            ->generate();
    }

    // create train law
    public function store(Request $request)
    {
        // request validation
        $validator = Validator::make($request->all(), [
            'train_law_desc' => 'string|unique:tbl_train_laws,train_law_desc|required',
            'year_start' => 'integer|required',
            'year_end' => 'integer|required',
            'activated' => 'boolean|required'
        ]);

        if ($validator->fails()) {
            $this->logActivity(
                'Train Law API', 
                'Create Train Law', 
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
            TrainLaw::where('activated', true)->update([
                'activated' => false,
                'updated_by' => Auth::user()->user_id
            ]);
        }
        
        // create
        $trainLaw = TrainLaw::create($request->all());

        $trainLaw->created_by = Auth::user()->user_id;
        $trainLaw->save();

        $this->logActivity(
            'Train Law API', 
            'Create Train Law', 
            'Success in Creating Train Law.'
        );

        return customResponse()
            ->message('Success in Creating Train Law.')
            ->success(201)
            ->generate();
    }

    // update train law
    public function update(Request $request, $trainLawId)
    {
        // resource validation
        $trainLaw = TrainLaw::find($trainLawId);

        if ($trainLaw === null) {
            $this->logActivity(
                'Train Law API',
                'Update Train Law',
                'Train Law not found.'
            );

            return customResponse()
                ->message('Train Law not found.')
                ->success(404)
                ->generate();
        }

        // request validation   
        $validator = Validator::make($request->all(), [
            'train_law_desc' => "string|unique:tbl_train_laws,train_law_desc,{$trainLawId},train_law_id|
                required",
            'year_start' => 'integer|required',
            'year_end' => 'integer|required',
            'activated' => 'boolean|required'
        ]);

        if ($validator->fails()) {
            $this->logActivity(
                'Train Law API', 
                'Update Train Law', 
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
            TrainLaw::where('activated', true)
                ->where('train_law_id', '!=', $trainLawId)
                ->update([
                    'activated' => false,
                    'updated_by' => Auth::user()->user_id
                ]);
        }

        // Update
        $trainLaw->update($request->all());

        $trainLaw->updated_by = Auth::user()->user_id;
        $trainLaw->save();

        $this->logActivity(
            'Train Law API', 
            'Update Train Law', 
            'Success in Updating Train Law.'
        );

        return customResponse()
            ->message('Success in Updating Train Law.')
            ->success()
            ->generate();
    }

    // delete train law
    public function destroy(Request $request, $trainLawId)
    {
        // resource validation
        $trainLaw = TrainLaw::find($trainLawId);

        if ($trainLaw === null) {
            $this->logActivity(
                'Train Law API',
                'Delete Train Law',
                'Train Law not found.'
            );

            return customResponse()
                ->message('Train Law not found.')
                ->success(404)
                ->generate();
        }

        // delete
        $trainLaw->deleted_by = Auth::user()->user_id;
        $trainLaw->save();

        $trainLaw->delete();

        $this->logActivity(
            'Train Law API',
            'Delete Train Law',
            'Success in Deleting Train Law.'
        );

        return customResponse()
            ->message('Success in Deleting Train Law')
            ->success()
            ->generate();
    }

    // get train law
    public function show(Request $request, $trainLawId)
    {
        // resource validation
        $trainLaw = TrainLaw::find($trainLawId);

        if ($trainLaw === null) {
            $this->logActivity(
                'Train Law API',
                'Get Train Law',
                'Train Law not found.'
            );

            return customResponse()
                ->message('Train Law not found.')
                ->failed(404)
                ->generate();
        }

        // get
        $trainLaw = TrainLaw::with(
            'taxes',
            'affecteds',
            'affecteds.employmentStatus',
            'affecteds.position'
        )->find($trainLawId);

        $this->logActivity(
            'Train Law API',
            'Get Train Law',
            'Success in Getting Train Law.'
        );

        return customResponse()
            ->message('Success in Getting Train Law.')
            ->data($trainLaw)
            ->success()
            ->generate();
    }

    // activate train law
    public function activate(Request $request, $trainLawId)
    {
        // resource validation
        $trainLaw = TrainLaw::find($trainLawId);

        if ($trainLaw === null) {
            $this->logActivity(
                'Train Law API',
                'Activate Train Law',
                'Train Law not found.'
            );

            return customResponse()
                ->message('Train Law not found.')
                ->success(404)
                ->generate();
        }

        // if activated, deactivate activated other than itself
        TrainLaw::where('activated', true)
            ->where('train_law_id', '!=', $trainLawId)
            ->update([
                'activated' => false,
                'updated_by' => Auth::user()->user_id
            ]);

        // activate
        $trainLaw->update(['activated' => true]);

        $trainLaw->updated_by = Auth::user()->user_id;
        $trainLaw->save();

        $this->logActivity(
            'Train Law API',
            'Activate Train Law',
            'Success in Activating Train Law.'
        );

        return customResponse()
            ->message('Success in Activating Train Law.')
            ->success()
            ->generate();
    }
    
    // deactivate train law
    public function deactivate(Request $request, $trainLawId)
    {
        // resource validation
        $trainLaw = TrainLaw::find($trainLawId);

        if ($trainLaw === null) {
            $this->logActivity(
                'Train Law API',
                'Deactivate Train Law',
                'Train Law not found.'
            );

            return customResponse()
                ->message('Train Law not found.')
                ->success(404)
                ->generate();
        }

        // activate
        $trainLaw->update(['activated' => false]);

        $trainLaw->updated_by = Auth::user()->user_id;
        $trainLaw->save();

        $this->logActivity(
            'Train Law API',
            'Deactivate Train Law',
            'Success in Deactivating Train Law.'
        );

        return customResponse()
            ->message('Success in Deactivating Train Law.')
            ->success()
            ->generate();
    }

    // get active train law
    public function getActive(Request $request)
    {
        // resource validation
        $trainLaw = TrainLaw::where('activated', true)->first();

        if ($trainLaw === null) {
            $this->logActivity(
                'Train Law API',
                'Get Active Train Law',
                'Train Law not found.'
            );

            return customResponse()
                ->message('Train Law not found.')
                ->success(404)
                ->generate();
        }
        
        // get
        $trainLaw = TrainLaw::with(
            'taxes',
            'affecteds',
            'affecteds.employmentStatus',
            'affecteds.position'
        )->where('activated', true)->first();

        $this->logActivity(
            'Train Law API',
            'Get Active Train Law',
            'Success in Getting Active Train Law.'
        );

        return customResponse()
            ->message('Success in Getting Active Train Law.')
            ->data($trainLaw)
            ->success()
            ->generate();
    }
}
