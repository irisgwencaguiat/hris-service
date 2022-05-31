<?php

namespace App\Http\Controllers\saln;

use App\Http\Controllers\Controller;
use App\Models\SalnAssetRealProperty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AssetRealPropertyController extends Controller
{
    public function store(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'assessed_value' => 'numeric|nullable',
            'current_fair_market_value' => 'numeric|nullable',
            'acquisition_cost' => 'numeric|nullable',
        ]);

        if ($validator->fails()) {
            return customResponse()
                ->data(null)
                ->message($validator->errors()->all()[0])
                ->failed()
                ->generate();
        }

        $realProperty = SalnAssetRealProperty::create([
            'employee_id' => $id,
            'description' => $request->input('description'),
            'kind' => $request->input('kind'),
            'exact_location' => $request->input('exact_location'),
            'assessed_value' => $request->input('assessed_value'),
            'current_fair_market_value' => $request->input(
                'current_fair_market_value'
            ),
            'acquisition_year' => $request->input('acquisition_year'),
            'acquisition_mode' => $request->input('acquisition_mode'),
            'acquisition_cost' => $request->input('acquisition_cost'),
        ]);

        return customResponse()
            ->data(
                SalnAssetRealProperty::find(
                    $realProperty->saln_asset_real_property_id
                )
            )
            ->message('Saln Assets Real Property has been created.')
            ->success()
            ->generate();
    }

    public function show($id)
    {
        $realProperty = SalnAssetRealProperty::where('employee_id', $id)
            ->whereNull('deleted_at')
            ->get();

        return customResponse()
            ->data($realProperty)
            ->message('Saln Assets Real Properties successfully found.')
            ->success()
            ->generate();
    }

    public function update(Request $request, $id)
    {
        $realProperty = SalnAssetRealProperty::updateOrCreate(
            ['saln_asset_real_property_id' => $id],
            [
                'description' => $request->input('description'),
                'kind' => $request->input('kind'),
                'exact_location' => $request->input('exact_location'),
                'assessed_value' => $request->input('assessed_value'),
                'current_fair_market_value' => $request->input(
                    'current_fair_market_value'
                ),
                'acquisition_year' => $request->input('acquisition_year'),
                'acquisition_mode' => $request->input('acquisition_mode'),
                'acquisition_cost' => $request->input('acquisition_cost'),
            ]
        );

        return customResponse()
            ->data(
                SalnAssetRealProperty::find(
                    $realProperty->saln_asset_real_property_id
                )
            )
            ->message('Saln Assets Real Property has been updated.')
            ->success()
            ->generate();
    }

    public function destroy($id)
    {
        $realProperty = SalnAssetRealProperty::find($id);
        if ($realProperty) {
            $realProperty->delete();
            return customResponse()
                ->data($realProperty)
                ->message('Saln Assets Real Property successfully deleted.')
                ->success()
                ->generate();
        }

        return customResponse()
            ->data(null)
            ->message('Saln Assets Real Property not found.')
            ->notFound()
            ->generate();
    }
}
