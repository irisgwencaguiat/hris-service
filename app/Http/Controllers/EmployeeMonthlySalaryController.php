<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use Validator;
use DB;
use Auth;
use Illuminate\Database\QueryException;
use App\Models\EmployeeMonthlySalary;

class EmployeeMonthlySalaryController extends Controller
{
    // get employee monthly salaries
    public function index(Request $request)
    {
        $perPage = apiPerPage(intval($request->input('per_page', 5)), 5, 100);

        $search = ($request->has('search')) ? "%{$request->input('search')}%" : '%';

        $sortBy = apiSortBy($request->input('sort_by'), 
            $request->input('sort_desc'), 
            [], 
            ['created_at' => 'ASC']
        );

        $filters = apiFilters($request, [
            'employee_id' => 'employee_id',
            'year' => 'year',
            'month' => 'month'
        ], [], 'STRICT');
        
        // get data
        $empMonthlySalaries = EmployeeMonthlySalary::
            where($filters)
            ->whereNested(function ($query) use ($search) {
                $query->where('year', 'LIKE', $search)
                    ->orWhere('month', 'LIKE', $search);
            })
            ->when($sortBy, function ($query, $sortBy) {
                foreach($sortBy as $column => $order)
                    $query->orderBy($column, $order);
            })
            ->paginate($perPage);

        $this->logActivity(
            'Employee Monthly Salary API', 
            'View Employee Monthly Salary Taxes', 
            'Success in Getting Employee Monthly Salaries.'
        );

        return customResponse()
            ->message('Success in Getting Employee Monthly Salaries.')
            ->data($empMonthlySalaries)
            ->success()
            ->generate();
    }
}
