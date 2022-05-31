<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use Validator;
use DB;
use Auth;
use Illuminate\Database\QueryException;
use App\Models\TrainLawAffected;


class TrainLawAffectedController extends Controller
{
    // get train law affecteds
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
        $trainLawAffecteds = TrainLawAffected::
            with(
                'employmentStatus',
                'position'
            )
            ->whereNested(function ($query) use ($search) {
                $query->where('employment_status_id', 'LIKE', $search)
                    ->orWhere('position_id', 'LIKE', $search);
            })
            ->when($sortBy, function ($query, $sortBy) {
                foreach($sortBy as $column => $order)
                    $query->orderBy($column, $order);
            })
            ->paginate($perPage);

        $this->logActivity(
            'Train Law Affected API', 
            'View Train Law Affected Taxes', 
            'Success in Getting Train Law Affecteds.'
        );

        return customResponse()
            ->message('Success in Getting Train Law Affecteds.')
            ->data($trainLawAffecteds)
            ->success()
            ->generate();
    }

    // create train law affected
    public function store(Request $request)
    {
        // request validation
        $validator = Validator::make($request->all(), [
            'train_law_id' => 'exists:tbl_train_laws,train_law_id,deleted_at,NULL|required',
            'employment_status_id' => 'exists:ref_employment_statuses,employment_status_id,deleted_at,NULL|nullable',
            'position_id' => 'exists:ref_positions,position_id,deleted_at,NULL|nullable'
        ]);

        if ($validator->fails()) {
            $this->logActivity(
                'Train Law Affected API', 
                'Create Train Law Affected', 
                'Please fill out the fields properly.'
            );

            return customResponse()
                ->message('Please fill out the fields properly.')
                ->failed()
                ->errors($validator->errors())
                ->generate();
        }

        // create
        $trainLawAffected = TrainLawAffected::create($request->all());

        $trainLawAffected->created_by = Auth::user()->user_id;
        $trainLawAffected->save();

        $this->logActivity(
            'Train Law Affected API', 
            'Create Train Law Affected', 
            'Success in Creating Train Law Affected.'
        );

        return customResponse()
            ->message('Success in Creating Train Law Affected.')
            ->success(201)
            ->generate();
    }

    // update train law affected
    public function update(Request $request, $trainLawAffectedId)
    {
        // resource validation
        $trainLawAffected = TrainLawAffected::find($trainLawAffectedId);

        if ($trainLawAffected === null) {
            $this->logActivity(
                'Train Law Affected API',
                'Update Train Law Affected',
                'Train Law Affected not found.'
            );

            return customResponse()
                ->message('Train Law Affected not found.')
                ->success(404)
                ->generate();
        }

        // request validation   
        $validator = Validator::make($request->all(), [
            'train_law_id' => 'exists:tbl_train_laws,train_law_id,deleted_at,NULL|required',
            'employment_status_id' => 'exists:ref_employment_statuses,employment_status_id,deleted_at,NULL|nullable',
            'position_id' => 'exists:ref_positions,position_id,deleted_at,NULL|nullable'
        ]);

        if ($validator->fails()) {
            $this->logActivity(
                'Train Law Affected API', 
                'Update Train Law Affected', 
                'Please fill out the fields properly.'
            );

            return customResponse()
                ->message('Please fill out the fields properly.')
                ->failed()
                ->errors($validator->errors())
                ->generate();
        }

        // Update
        $trainLawAffected->update($request->all());

        $trainLawAffected->updated_by = Auth::user()->user_id;
        $trainLawAffected->save();

        $this->logActivity(
            'Train Law Affected API', 
            'Update Train Law Affected', 
            'Success in Updating Train Law Affected.'
        );

        return customResponse()
            ->message('Success in Updating Train Law Affected.')
            ->success()
            ->generate();
    }

    // delete train law affected
    public function destroy(Request $request, $trainLawAffectedId)
    {
        // resource validation
        $trainLawAffected = TrainLawAffected::find($trainLawAffectedId);

        if ($trainLawAffected === null) {
            $this->logActivity(
                'Train Law Affected API',
                'Delete Train Law Affected',
                'Train Law Affected not found.'
            );

            return customResponse()
                ->message('Train Law Affected not found.')
                ->success(404)
                ->generate();
        }

        // delete
        $trainLawAffected->deleted_by = Auth::user()->user_id;
        $trainLawAffected->save();

        $trainLawAffected->delete();

        $this->logActivity(
            'Train Law Affected API',
            'Delete Train Law Affected',
            'Success in Deleting Train Law Affected.'
        );

        return customResponse()
            ->message('Success in Deleting Train Law Affected')
            ->success()
            ->generate();
    }

    // get train law affected
    public function show(Request $request, $trainLawAffectedId)
    {
        // resource validation
        $trainLawAffected = TrainLawAffected::find($trainLawAffectedId);

        if ($trainLawAffected === null) {
            $this->logActivity(
                'Train Law Affected API',
                'Get Train Law Affected',
                'Train Law Affected not found.'
            );

            return customResponse()
                ->message('Train Law Affected not found.')
                ->failed(404)
                ->generate();
        }

        // get
        $trainLawAffected = TrainLawAffected::with(
            'employmentStatus',
            'position'
        )->find($trainLawAffectedId);

        $this->logActivity(
            'Train Law Affected API',
            'Get Train Law Affected',
            'Success in Getting Train Law Affected.'
        );

        return customResponse()
            ->message('Success in Getting Train Law Affected.')
            ->data($trainLawAffected)
            ->success()
            ->generate();
    }
}
