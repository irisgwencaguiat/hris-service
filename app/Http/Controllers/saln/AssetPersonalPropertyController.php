<?php

namespace App\Http\Controllers\saln;

use App\Http\Controllers\Controller;
use App\Models\SalnAssetPersonalProperty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AssetPersonalPropertyController extends Controller
{
    public function store(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'string|nullable',
            'acquisition_year' => 'string|nullable',
            'acquisition_cost' => 'numeric|nullable',
        ]);

        if ($validator->fails()) {
            return customResponse()
                ->data(null)
                ->message($validator->errors()->all()[0])
                ->failed()
                ->generate();
        }

        $personalProperty = SalnAssetPersonalProperty::create([
            'employee_id' => $id,
            'description' => $request->input('description'),
            'acquisition_year' => $request->input('acquisition_year'),
            'acquisition_cost' => $request->input('acquisition_cost'),
        ]);

        return customResponse()
            ->data(
                SalnAssetPersonalProperty::find(
                    $personalProperty->saln_asset_personal_property_id
                )
            )
            ->message('Saln Assets Personal Property has been created.')
            ->success()
            ->generate();
    }

    public function show($id)
    {
        $personalProperty = SalnAssetPersonalProperty::where('employee_id', $id)
            ->whereNull('deleted_at')
            ->get();

        return customResponse()
            ->data($personalProperty)
            ->message('Saln Assets Personal Properties successfully found.')
            ->success()
            ->generate();
    }

    public function update(Request $request, $id)
    {
        $personalProperty = SalnAssetPersonalProperty::updateOrCreate(
            ['saln_asset_personal_property_id' => $id],
            [
                'description' => $request->input('description'),
                'acquisition_year' => $request->input('acquisition_year'),
                'acquisition_cost' => $request->input('acquisition_cost'),
            ]
        );

        return customResponse()
            ->data(
                SalnAssetPersonalProperty::find(
                    $personalProperty->saln_asset_personal_property_id
                )
            )
            ->message('Saln Assets Personal Property has been updated.')
            ->success()
            ->generate();
    }

    public function destroy($id)
    {
        $personalProperty = SalnAssetPersonalProperty::find($id);
        if ($personalProperty) {
            $personalProperty->delete();
            return customResponse()
                ->data($personalProperty)
                ->message('Saln Assets Personal Property successfully deleted.')
                ->success()
                ->generate();
        }

        return customResponse()
            ->data(null)
            ->message('Saln Assets Personal Property not found.')
            ->notFound()
            ->generate();
    }
}
