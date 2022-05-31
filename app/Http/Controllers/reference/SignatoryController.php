<?php

namespace App\Http\Controllers\reference;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Log;
use Validator;
use DB;
use Auth;
use Illuminate\Database\QueryException;
use App\Models\References\Signatory;

class SignatoryController extends Controller
{
    // get signatories
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
        $signatories = Signatory::
            whereNested(function ($query) use ($search) {
                $query->where('employee_id', 'LIKE', $search);
            })
            ->when($sortBy, function ($query, $sortBy) {
                foreach($sortBy as $column => $order)
                    $query->orderBy($column, $order);
            })
            ->paginate($perPage);

        $this->logActivity(
            'Signatory Reference', 
            'View Signatories', 
            'Success in Getting Signatories.'
        );

        return customResponse()
            ->message('Success in Getting Signatories.')
            ->data($signatories)
            ->success()
            ->generate();
    }

    // create signatory
    public function store(Request $request)
    {
        // request validation
        $validator = Validator::make($request->all(), [
            'employee_id' => 'exists:tbl_employees,employee_id|
                unique:ref_signatories,employee_id|required',
            'e_signature' => 'image|mimes:jpeg,png,jpg|max:1024',
        ]);

        if ($validator->fails()) {
            $this->logActivity(
                'Signatory Reference', 
                'Create Signatory', 
                'Please fill out the fields properly.'
            );

            return customResponse()
                ->message('Please fill out the fields properly.')
                ->failed()
                ->errors($validator->errors())
                ->generate();
        }
        
        // save the e-signature
        $filePath = $this->saveESignature([
            'eSignature' => $request->file('e_signature')
        ]);

        // create
        $request->request->add(['e_signature_path' => $filePath]);
        $signatory = Signatory::create($request->all());

        $signatory->created_by = Auth::user()->user_id;
        $signatory->save();

        $this->logActivity(
            'Signatory Reference', 
            'Create Signatory', 
            'Success in Creating Signatory.'
        );

        return customResponse()
            ->message('Success in Creating Signatory.')
            ->success(201)
            ->generate();
    }

    // update signatory
    public function update(Request $request, $signatoryCode)
    {
        // resource validation
        $signatory = Signatory::find($signatoryCode);

        if ($signatory === null) {
            $this->logActivity(
                'Signatory Reference',
                'Update Signatory',
                'Signatory not found.'
            );

            return customResponse()
                ->message('Signatory not found.')
                ->success(404)
                ->generate();
        }

        // request validation   
        $validator = Validator::make($request->all(), [
            'employee_id' => "exists:tbl_employees,employee_id|
                unique:ref_signatories,employee_id,{$signatoryCode},signatory_id|
                required",
            'e_signature' => 'image|mimes:jpeg,png,jpg|max:1024',
        ]);

        if ($validator->fails()) {
            $this->logActivity(
                'Signatory Reference', 
                'Update Signatory', 
                'Please fill out the fields properly.'
            );

            return customResponse()
                ->message('Please fill out the fields properly.')
                ->failed()
                ->errors($validator->errors())
                ->generate();
        }

        // save the e-signature
        $filePath = $this->saveESignature([
            'eSignature' => $request->file('e_signature')
        ]);

        // Update
        $request->request->add(['e_signature_path' => $filePath]);
        $signatory->update($request->all());

        $signatory->updated_by = Auth::user()->user_id;
        $signatory->save();

        $this->logActivity(
            'Signatory Reference', 
            'Update Signatory', 
            'Success in Updating Signatory.'
        );

        return customResponse()
            ->message('Success in Updating Signatory.')
            ->success()
            ->generate();
    }

    // delete signatory
    public function destroy(Request $request, $signatoryCode)
    {
        // resource validation
        $signatory = Signatory::find($signatoryCode);

        if ($signatory === null) {
            $this->logActivity(
                'Signatory Reference',
                'Delete Signatory',
                'Signatory not found.'
            );

            return customResponse()
                ->message('Signatory not found.')
                ->success(404)
                ->generate();
        }

        // delete
        $signatory->deleted_by = Auth::user()->user_id;
        $signatory->save();

        $signatory->delete();

        $this->logActivity(
            'Signatory Reference',
            'Delete Signatory',
            'Success in Deleting Signatory.'
        );

        return customResponse()
            ->message('Success in Deleting Signatory')
            ->success()
            ->generate();
    }

    // get signatory
    public function show(Request $request, $signatoryCode)
    {
        // resource validation
        $signatory = Signatory::find($signatoryCode);

        if ($signatory === null) {
            $this->logActivity(
                'Signatory Reference',
                'Get Signatory',
                'Signatory not found.'
            );

            return customResponse()
                ->message('Signatory not found.')
                ->failed(404)
                ->generate();
        }

        // get

        $this->logActivity(
            'Signatory Reference',
            'Get Signatory',
            'Success in Getting Signatory.'
        );

        return customResponse()
            ->message('Success in Getting Signatory.')
            ->data($signatory)
            ->success()
            ->generate();
    }

    private function saveESignature(Array $vars)
    {
        if (count($vars) == 0) {
            return false;
        }

        extract($vars);

        $fileName = sprintf('%s%05d', 
            uniqid('ES-', true), 
            rand(1, 99999)
        );

        $directory = '/images/e-signatures/';

        $filePath = $fileName . '.' . $eSignature->getClientOriginalExtension();
        $eSignature->move(public_path('/images/e-signatures/'), $filePath);
        $filePath = $directory . $filePath;

        return $filePath;
    }
}
