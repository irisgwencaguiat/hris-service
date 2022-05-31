<?php

namespace App\Http\Controllers\reference;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Log;
use Validator;
use DB;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use App\Models\References\StepIncrement;

class StepIncrementController extends Controller
{
    // get step increments
    public function index(Request $request)
    {
        $perPage = apiPerPage(intval($request->input('per_page', 5)), 5, 100);

        $search = ($request->has('search')) ? "%{$request->input('search')}%" : '%';

        $sortBy = apiSortBy($request->input('sort_by'), 
            $request->input('sort_desc'), 
            [], 
            ['CONVERT(step_increment_name, SIGNED)' => 'ASC']
        );
        
        // get data
        $stepIncrements = StepIncrement::
            whereNested(function ($query) use ($search) {
                $query->where('step_increment_name', 'LIKE', $search);
            })
            ->when($sortBy, function ($query, $sortBy) {
                foreach($sortBy as $column => $order)
                    $query->orderBy(DB::raw($column), $order);
            })
            ->paginate($perPage);
        
        $this->logActivity(
            'Step Increment Reference', 
            'View Step Increments', 
            'Success in Getting Step Increments.'
        );

        return customResponse()
            ->message('Success in Getting Step Increments.')
            ->data($stepIncrements)
            ->success()
            ->generate();
    }

    // create step increment
    public function store(Request $request)
    {
        // request validation
        $validator = Validator::make($request->all(), [
            'step_increment_name' => 'unique:ref_step_increments,step_increment_name|required'
        ]);

        if ($validator->fails()) {
            $this->logActivity(
                'Step Increment Reference', 
                'Create Step Increment', 
                'Please fill out the fields properly.'
            );

            return customResponse()
                ->message('Please fill out the fields properly.')
                ->failed()
                ->errors($validator->errors())
                ->generate();
        }
        
        // create
        StepIncrement::create($request->all());

        $this->logActivity(
            'Step Increment Reference', 
            'Create Step Increment', 
            'Success in Creating Step Increment.'
        );

        return customResponse()
            ->message('Success in Creating Step Increment.')
            ->success(201)
            ->generate();
    }

    // update step increment
    public function update(Request $request, $stepIncrementId)
    {
        // resource validation
        $stepIncrement = StepIncrement::find($stepIncrementId);

        if ($stepIncrement === null) {
            $this->logActivity(
                'Step Increment Reference',
                'Update Step Increment',
                'Step Increment not found.'
            );

            return customResponse()
                ->message('Step Increment not found.')
                ->success(404)
                ->generate();
        }

        // request validation   
        $validator = Validator::make($request->all(), [
            'step_increment_name' => 'unique:ref_step_increments,step_increment_name|required'
        ]);

        if ($validator->fails()) {
            $this->logActivity(
                'Step Increment Reference', 
                'Update Step Increment', 
                'Please fill out the fields properly.'
            );

            return customResponse()
                ->message('Please fill out the fields properly.')
                ->failed()
                ->errors($validator->errors())
                ->generate();
        }
        
        // Update
        $stepIncrement->update($request->all());

        $stepIncrement->updated_by = Auth::user()->user_id;
        $stepIncrement->save();

        $this->logActivity(
            'Step Increment Reference', 
            'Update Step Increment', 
            'Success in Updating Step Increment.
        ');

        return customResponse()
            ->message('Success in Updating Step Increment.')
            ->success()
            ->generate();
    }

    // delete step increment
    public function delete(Request $request, $stepIncrementId)
    {
        // resource validation
        $stepIncrement = StepIncrement::find($stepIncrementId);

        if ($stepIncrement === null) {
            $this->logActivity(
                'Step Increment Reference',
                'Delete Step Increment',
                'Step Increment not found.'
            );

            return customResponse()
                ->message('Step Increment not found.')
                ->success(404)
                ->generate();
        }

        // delete
        $stepIncrement->deleted_by = Auth::user()->user_id;
        $stepIncrement->save();

        $stepIncrement->delete();

        $this->logActivity(
            'Step Increment Reference',
            'Delete Step Increment',
            'Success in Deleting Step Increment.'
        );

        return customResponse()
            ->message('Success in Deleting Step Increment')
            ->success()
            ->generate();
    }

    // get step increment
    public function show(Request $request, $stepIncrementId)
    {
        // resource validation
        $stepIncrement = StepIncrement::find($stepIncrementId);

        if ($stepIncrement === null) {
            $this->logActivity(
                'Step Increment Reference',
                'Get Step Increment',
                'Step Increment not found.'
            );

            return customResponse()
                ->message('Step Increment not found.')
                ->failed(404)
                ->generate();
        }

        // return
        $this->logActivity(
            'Step Increment Reference',
            'Get Step Increment',
            'Success in Getting Step Increment.'
        );

        return customResponse()
            ->message('Success in Getting Step Increment.')
            ->data($stepIncrement)
            ->success()
            ->generate();
    }
}
