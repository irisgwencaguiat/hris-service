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
use App\Models\RefPlantilla;
use Illuminate\Validation\Rule;

class PlantillaController extends Controller
{
    // get plantillas
    public function index(Request $request)
    {
        $perPage = apiPerPage(intval($request->input('per_page', 5)), 5, 100);

        $search = ($request->has('search')) ? "%{$request->input('search')}%" : '%';

        $sortBy = apiSortBy(
            $request->input('sort_by'), 
            $request->input('sort_desc'), 
            [], 
            ['created_by' => 'ASC']
        );

        $filters = apiFilters(
            $request,
            [
                'position_id' => 'position_id', 
                'office_id' => 'office_id',
                'exclude_taken' => 'exclude_taken'
            ],
            [],
            'STRICT'
        );

        // special cases
        $specialCases = [];
        
        if (array_key_exists('exclude_taken', $filters)) {
            if ($filters['exclude_taken']) {
                array_push($specialCases, 'exclude_taken');
            }

            unset($filters['exclude_taken']);
        }
        
        // get data
        $plantillas = RefPlantilla::
            with(
                'office', 
                'position',
                'holders',
            );

        // special cases
        if (in_array('exclude_taken', $specialCases)) {
            $plantillas = $plantillas->doesntHave('holders');
        }

        // get data (continueation)
        $plantillas = $plantillas
            ->where($filters)
            ->whereNested(function ($query) use ($search) {
                $query->where('plantilla_no', 'LIKE', $search);
            })
            // ->orWhereHas('office', function ($query) use ($search) {
            //     $query->where('office_code', 'LIKE', $search)
            //         ->orWhere('office_name', 'LIKE', $search);
            // })
            // ->orWhereHas('position', function ($query) use ($search) {
            //     $query->where('position_name', 'LIKE', $search);
            // })
            // ->orWhereHas('holders', function ($query) use ($search) {
            //     $query->where('first_name', 'LIKE', $search)
            //         ->orWhere('middle_name', 'LIKE', $search)
            //         ->orWhere('last_name', 'LIKE', $search);
            // })
            ->when($sortBy, function ($query, $sortBy) {
                foreach($sortBy as $column => $order)
                    $query->orderBy($column, $order);
            })
            ->paginate($perPage);
        
        $this->logActivity(
            'Plantilla Reference', 
            'View Plantillas', 
            'Success in Getting Plantillas.'
        );

        return customResponse()
            ->message('Success in Getting Plantillas.')
            ->data($plantillas)
            ->success()
            ->generate();
    }

    // create plantilla
    public function store(Request $request)
    {
        // request validation
        $messages = [
            'plantilla_no.unique' => 'The plantilla no and office have already been taken.'
        ];
        $validator = Validator::make($request->all(), [
            'plantilla_no' => [
                'string',
                Rule::unique('ref_plantillas')->where(function ($query) use ($request) {
                    return $query->where('plantilla_no', $request->input('plantilla_no'))
                        ->where('office_id', $request->input('office_id'));
                }),
                'required'
            ],
            'position_id' => 'exists:ref_positions,position_id,deleted_at,NULL|required',
            'salary_grade_id' => 'exists:ref_salary_grades,salary_grade_id,deleted_at,NULL|required',
            'step_increment_id' => 'exists:ref_step_increments,step_increment_id,deleted_at,NULL|required',
            'office_id' => 'exists:ref_offices,office_id,deleted_at,NULL|nullable',
        ], $messages);

        if ($validator->fails()) {
            $this->logActivity(
                'Plantilla Reference', 
                'Create Plantilla', 
                'Please fill out the fields properly.'
            );

            return customResponse()
                ->message('Please fill out the fields properly.')
                ->failed()
                ->errors($validator->errors())
                ->generate();
        }
        
        // create
        RefPlantilla::create($request->all());

        $this->logActivity(
            'Plantilla Reference', 
            'Create Plantilla', 
            'Success in Creating Plantilla.'
        );

        return customResponse()
            ->message('Success in Creating Plantilla.')
            ->success(201)
            ->generate();
    }

    // update plantilla
    public function update(Request $request, $plantillaId)
    {
        // resource validation
        $plantilla = RefPlantilla::find($plantillaId);

        if ($plantilla === null) {
            $this->logActivity(
                'Plantilla Reference',
                'Update Plantilla',
                'Plantilla not found.'
            );

            return customResponse()
                ->message('Plantilla not found.')
                ->success(404)
                ->generate();
        }

        // if plantilla is already assigned, then no update
        if ($plantilla->holders()->count() >= 1) {
            $this->logActivity(
                'Plantilla Reference', 
                'Update Plantilla', 
                'Plantilla is already assigned and can\'t be updated.'
            );

            return customResponse()
                ->message('Plantilla is already assigned and can\'t be updated.')
                ->failed()
                ->generate();
        }

        // request validation   
        $messages = [
            'plantilla_no.unique' => 'The plantilla no and office have already been taken.'
        ];

        $validator = Validator::make($request->all(), [

            'plantilla_no' => [
                'string',
                Rule::unique('ref_plantillas')
                    ->where(function ($query) use ($request, $plantillaId) {

                        return $query
                            ->where('plantilla_no', $request->input('plantilla_no'))
                            ->where('office_id', $request->input('office_id'))
                            ->where('plantilla_id', '!=', $plantillaId);
                    }),
                'required'
            ],

            'position_id' => 'exists:ref_positions,position_id,deleted_at,NULL|required',
            'salary_grade_id' => 'exists:ref_salary_grades,salary_grade_id,deleted_at,NULL|required',
            'step_increment_id' => 'exists:ref_step_increments,step_increment_id,deleted_at,NULL|required',
            'office_id' => 'exists:ref_offices,office_id,deleted_at,NULL|nullable',

        ], $messages);

        if ($validator->fails()) {
            $this->logActivity(
                'Plantilla Reference', 
                'Update Plantilla', 
                'Please fill out the fields properly.'
            );

            return customResponse()
                ->message('Please fill out the fields properly.')
                ->failed()
                ->errors($validator->errors())
                ->generate();
        }
        
        // Update
        $plantilla->update($request->all());

        $plantilla->updated_by = Auth::user()->user_id;
        $plantilla->save();

        $this->logActivity(
            'Plantilla Reference', 
            'Update Plantilla', 
            'Success in Updating Plantilla.
        ');

        return customResponse()
            ->message('Success in Updating Plantilla.')
            ->success()
            ->generate();
    }

    // delete plantilla
    public function delete(Request $request, $plantillaId)
    {
        // resource validation
        $plantilla = RefPlantilla::find($plantillaId);

        if ($plantilla === null) {
            $this->logActivity(
                'Plantilla Reference',
                'Delete Plantilla',
                'Plantilla not found.'
            );

            return customResponse()
                ->message('Plantilla not found.')
                ->success(404)
                ->generate();
        }

        // if plantilla is already assigned, then no delete
        if ($plantilla->holders()->count() >= 1) {
            $this->logActivity(
                'Plantilla Reference', 
                'Update Plantilla', 
                'Plantilla is already assigned and can\'t be deleted.'
            );

            return customResponse()
                ->message('Plantilla is already assigned and can\'t be deleted.')
                ->failed()
                ->generate();
        }

        // delete
        $plantilla->deleted_by = Auth::user()->user_id;
        $plantilla->save();

        $plantilla->delete();

        $this->logActivity(
            'Plantilla Reference',
            'Delete Plantilla',
            'Success in Deleting Plantilla.'
        );

        return customResponse()
            ->message('Success in Deleting Plantilla')
            ->success()
            ->generate();
    }

    // get plantilla
    public function show(Request $request, $plantillaId)
    {
        // resource validation
        $plantilla = RefPlantilla::with(
            'office', 
            'position',
            'holders'
        )->find($plantillaId);

        if ($plantilla === null) {
            $this->logActivity(
                'Plantilla Reference',
                'Get Plantilla',
                'Plantilla not found.'
            );

            return customResponse()
                ->message('Plantilla not found.')
                ->failed(404)
                ->generate();
        }

        $this->logActivity(
            'Plantilla Reference',
            'Get Plantilla',
            'Success in Getting Plantilla.'
        );

        return customResponse()
            ->message('Success in Getting Plantilla.')
            ->data($plantilla)
            ->success()
            ->generate();
    }
}
