<?php

namespace App\Http\Controllers\pds;

use App\Http\Controllers\Controller;
use App\Models\PdsAssociationMembership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AssociationMembershipController extends Controller
{
    public function store(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'association' => 'required|string',
        ]);

        if ($validator->fails()) {
            return customResponse()
                ->data(null)
                ->message($validator->errors()->all()[0])
                ->failed()
                ->generate();
        }

        $pdsAssociationMembership = PdsAssociationMembership::create([
            'employee_id' => $id,
            'association' => $request->input('association'),
        ]);

        return customResponse()
            ->data(PdsAssociationMembership::find($pdsAssociationMembership->pds_association_membership_id))
            ->message('PDS Association Membership has been created.')
            ->success()
            ->generate();
    }


    public function show($id)
    {
        $pdsAssociationMembership = PdsAssociationMembership::where('employee_id', $id)->whereNull('deleted_at')->get();

        return customResponse()
            ->data($pdsAssociationMembership)
            ->message('PDS Association Membership successfully found.')
            ->success()
            ->generate();
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'association' => 'required|string',
        ]);

        if ($validator->fails()) {
            return customResponse()
                ->data(null)
                ->message($validator->errors()->all()[0])
                ->failed()
                ->generate();
        }

        $pdsAssociationMembership = PdsAssociationMembership::updateOrCreate(
            ['pds_association_membership_id' => $id],
            [
                'association' => $request->input('association'),
            ]);


        return customResponse()
            ->data(PdsAssociationMembership::find($pdsAssociationMembership->pds_association_membership_id))
            ->message('PDS Association Membership has been updated.')
            ->success()
            ->generate();
    }

    public function destroy($id)
    {
        $pdsAssociationMembership = PdsAssociationMembership::find($id);
        if ($pdsAssociationMembership) {
            $pdsAssociationMembership->delete();
            return customResponse()
                ->data($pdsAssociationMembership)
                ->message('PDS Association Membership successfully deleted.')
                ->success()
                ->generate();
        }

        return customResponse()
            ->data(null)
            ->message('PDS Association Membership not found.')
            ->notFound()
            ->generate();
    }
}
