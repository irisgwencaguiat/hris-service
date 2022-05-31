<?php

namespace App\Http\Controllers\saln;

use App\Http\Controllers\Controller;
use App\Models\Saln;
use App\Models\SalnVersions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VersionController extends Controller
{
    public function index(Request $request)
    {
        $version = SalnVersions::whereNull('deleted_at')
            ->orderBy('saln_version_id', 'DESC')
            ->paginate(
                (int) $request->get('per_page', 10),
                ['*'],
                'page',
                (int) $request->get('page', 1)
            );

        return customResponse()
            ->data($version)
            ->message('Saln Liabilities successfully found.')
            ->success()
            ->generate();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date_covered' => 'required|date',
            'deadline' => 'required|date',
        ]);

        if ($validator->fails()) {
            return customResponse()
                ->data(null)
                ->message($validator->errors()->all()[0])
                ->failed()
                ->generate();
        }

        $version = SalnVersions::create([
            'date_covered' => $request->input('date_covered'),
            'deadline' => $request->input('deadline'),
        ]);

        return customResponse()
            ->data(SalnVersions::find($version->saln_version_id))
            ->message('Saln Version has been created.')
            ->success()
            ->generate();
    }

    public function show($id)
    {
        $version = SalnVersions::find($id);

        return customResponse()
            ->data($version)
            ->message('Saln Version successfully found.')
            ->success()
            ->generate();
    }

    public function showActive()
    {
        $version = SalnVersions::where(
            'is_active',
            filter_var(true, FILTER_VALIDATE_BOOLEAN)
        )
            ->latest()
            ->get();

        return customResponse()
            ->data($version)
            ->message('Saln Active Versions successfully found.')
            ->success()
            ->generate();
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'date_covered' => 'date|nullable',
            'deadline' => 'date|nullable',
            'is_active' => 'boolean|nullable',
        ]);

        if ($validator->fails()) {
            return customResponse()
                ->data(null)
                ->message($validator->errors()->all()[0])
                ->failed()
                ->generate();
        }

        $version = SalnVersions::updateOrCreate(
            ['saln_version_id' => $id],
            [
                'date_covered' => $request->input('date_covered'),
                'deadline' => $request->input('deadline'),
                'is_active' => filter_var(
                    $request->input('is_active'),
                    FILTER_VALIDATE_BOOLEAN
                ),
            ]
        );

        return customResponse()
            ->data(SalnVersions::find($version->saln_version_id))
            ->message('Saln Version has been updated.')
            ->success()
            ->generate();
    }

    public function destroy($id)
    {
        $version = SalnVersions::find($id);
        if ($version) {
            $version->delete();
            return customResponse()
                ->data($version)
                ->message('Saln Version successfully deleted.')
                ->success()
                ->generate();
        }

        return customResponse()
            ->data(null)
            ->message('Saln Version not found.')
            ->notFound()
            ->generate();
    }
}
