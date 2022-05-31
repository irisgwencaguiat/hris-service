<?php

namespace App\Http\Controllers\saln;

use App\Http\Controllers\Controller;
use App\Models\SalnLiability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LiabilityController extends Controller
{
    public function store(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nature' => 'string|nullable',
            'creditor_name' => 'string|nullable',
            'outstanding_balance' => 'numeric|nullable',
        ]);

        if ($validator->fails()) {
            return customResponse()
                ->data(null)
                ->message($validator->errors()->all()[0])
                ->failed()
                ->generate();
        }

        $liability = SalnLiability::create([
            'employee_id' => $id,
            'nature' => $request->input('nature'),
            'creditor_name' => $request->input('creditor_name'),
            'outstanding_balance' => $request->input('outstanding_balance'),
        ]);

        return customResponse()
            ->data(SalnLiability::find($liability->saln_liability_id))
            ->message('Saln Liability has been created.')
            ->success()
            ->generate();
    }

    public function show($id)
    {
        $liability = SalnLiability::where('employee_id', $id)
            ->whereNull('deleted_at')
            ->get();

        return customResponse()
            ->data($liability)
            ->message('Saln Liabilities successfully found.')
            ->success()
            ->generate();
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nature' => 'string|nullable',
            'creditor_name' => 'string|nullable',
            'outstanding_balance' => 'numeric|nullable',
        ]);

        if ($validator->fails()) {
            return customResponse()
                ->data(null)
                ->message($validator->errors()->all()[0])
                ->failed()
                ->generate();
        }

        $liability = SalnLiability::updateOrCreate(
            ['saln_liability_id' => $id],
            [
                'nature' => $request->input('nature'),
                'creditor_name' => $request->input('creditor_name'),
                'outstanding_balance' => $request->input('outstanding_balance'),
            ]
        );

        return customResponse()
            ->data(SalnLiability::find($liability->saln_liability_id))
            ->message('Saln Liability has been updated.')
            ->success()
            ->generate();
    }

    public function destroy($id)
    {
        $liability = SalnLiability::find($id);
        if ($liability) {
            $liability->delete();
            return customResponse()
                ->data($liability)
                ->message('Saln Liability successfully deleted.')
                ->success()
                ->generate();
        }

        return customResponse()
            ->data(null)
            ->message('Saln Liability not found.')
            ->notFound()
            ->generate();
    }
}
