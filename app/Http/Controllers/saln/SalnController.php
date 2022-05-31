<?php

namespace App\Http\Controllers\saln;

use App\Http\Controllers\Controller;
use App\Models\Saln;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SalnController extends Controller
{
    public function store(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'saln_version_id' => 'required',
        ]);

        if ($validator->fails()) {
            return customResponse()
                ->data(null)
                ->message($validator->errors()->all()[0])
                ->failed()
                ->generate();
        }

        $saln = Saln::create([
            'employee_id' => $id,
            'saln_version_id' => $request->input('saln_version_id'),
        ]);

        return customResponse()
            ->data(Saln::with(['salnVersion'])->find($saln->saln_id))
            ->message('Saln has been created.')
            ->success()
            ->generate();
    }

    public function index($id)
    {
        $saln = Saln::with(['salnVersion'])
            ->where('employee_id', $id)
            ->whereNull('deleted_at')
            ->get();

        return customResponse()
            ->data($saln)
            ->message('Saln successfully found.')
            ->success()
            ->generate();
    }
    public function show($id)
    {
        $saln = Saln::with(['salnVersion'])
            ->where('saln_id', $id)
            ->whereNull('deleted_at')
            ->get()
            ->first();

        return customResponse()
            ->data($saln)
            ->message('Saln successfully found.')
            ->success()
            ->generate();
    }

    public function update(Request $request, $id)
    {
        $saln = Saln::updateOrCreate(
            ['saln_id' => $id],
            [
                'is_printed' => filter_var(
                    $request->input('is_printed'),
                    FILTER_VALIDATE_BOOLEAN
                ),
            ]
        );

        return customResponse()
            ->data(Saln::with(['salnVersion'])->find($saln->saln_id))
            ->message('Saln has been updated.')
            ->success()
            ->generate();
    }

    public function destroy($id)
    {
        $saln = Saln::with(['salnVersion'])->find($id);
        if ($saln) {
            $saln->delete();
            return customResponse()
                ->data($saln)
                ->message('Saln successfully deleted.')
                ->success()
                ->generate();
        }

        return customResponse()
            ->data(null)
            ->message('Saln not found.')
            ->notFound()
            ->generate();
    }
}
