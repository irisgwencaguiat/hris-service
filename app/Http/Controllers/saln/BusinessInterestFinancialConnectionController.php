<?php

namespace App\Http\Controllers\saln;

use App\Http\Controllers\Controller;
use App\Models\SalnBusinessInterestFinancialConnection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BusinessInterestFinancialConnectionController extends Controller
{
    public function store(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'entity_business_enterprise_name' => 'string|nullable',
            'business_address' => 'string|nullable',
            'business_interest_financial_connection_nature' =>
                'string|nullable',
            'acquisition_of_interest_connection_date' => 'string|nullable',
        ]);

        if ($validator->fails()) {
            return customResponse()
                ->data(null)
                ->message($validator->errors()->all()[0])
                ->failed()
                ->generate();
        }

        $businessInterest = SalnBusinessInterestFinancialConnection::create([
            'employee_id' => $id,
            'entity_business_enterprise_name' => $request->input(
                'entity_business_enterprise_name'
            ),
            'business_address' => $request->input('business_address'),
            'business_interest_financial_connection_nature' => $request->input(
                'business_interest_financial_connection_nature'
            ),
            'acquisition_of_interest_connection_date' => $request->input(
                'acquisition_of_interest_connection_date'
            ),
        ]);

        return customResponse()
            ->data(
                SalnBusinessInterestFinancialConnection::find(
                    $businessInterest->saln_business_interest_financial_connection_id
                )
            )
            ->message(
                'Saln Business Interest and Financial Connection has been created.'
            )
            ->success()
            ->generate();
    }

    public function show($id)
    {
        $businessInterest = SalnBusinessInterestFinancialConnection::where(
            'employee_id',
            $id
        )
            ->whereNull('deleted_at')
            ->get();

        return customResponse()
            ->data($businessInterest)
            ->message(
                'Saln Business Interest and Financial Connections successfully found.'
            )
            ->success()
            ->generate();
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'entity_business_enterprise_name' => 'string|nullable',
            'business_address' => 'string|nullable',
            'business_interest_financial_connection_nature' =>
                'string|nullable',
            'acquisition_of_interest_connection_date' => 'string|nullable',
        ]);

        if ($validator->fails()) {
            return customResponse()
                ->data(null)
                ->message($validator->errors()->all()[0])
                ->failed()
                ->generate();
        }

        $businessInterest = SalnBusinessInterestFinancialConnection::updateOrCreate(
            ['saln_business_interest_financial_connection_id' => $id],
            [
                'entity_business_enterprise_name' => $request->input(
                    'entity_business_enterprise_name'
                ),
                'business_address' => $request->input('business_address'),
                'business_interest_financial_connection_nature' => $request->input(
                    'business_interest_financial_connection_nature'
                ),
                'acquisition_of_interest_connection_date' => $request->input(
                    'acquisition_of_interest_connection_date'
                ),
            ]
        );

        return customResponse()
            ->data(
                SalnBusinessInterestFinancialConnection::find(
                    $businessInterest->saln_business_interest_financial_connection_id
                )
            )
            ->message(
                'Saln Business Interest and Financial Connection has been updated.'
            )
            ->success()
            ->generate();
    }

    public function destroy($id)
    {
        $businessInterest = SalnBusinessInterestFinancialConnection::find($id);
        if ($businessInterest) {
            $businessInterest->delete();
            return customResponse()
                ->data($businessInterest)
                ->message(
                    'Saln Business Interest and Financial Connection successfully deleted.'
                )
                ->success()
                ->generate();
        }

        return customResponse()
            ->data(null)
            ->message(
                'Saln Business Interest and Financial Connection not found.'
            )
            ->notFound()
            ->generate();
    }
}
