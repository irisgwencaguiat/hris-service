<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use DB;
use Validator;
use Log;

class ValidatorServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // validator for unique with multiple columns
        Validator::extend('uniqueOfMultiple', function ($attribute, $value, $parameters, $validator) {

            $whereColumns = [
                [$attribute, $value]
            ];

            // get the table name in first elem of params
            $tableName = array_shift($parameters);

            // add the other columns
            $exceptionColumn = '';
            $exceptionFlag = false;

            foreach ($parameters as $parameter) {

                // trigger except flag
                if ($parameter === 'except') {
                    $exceptionFlag = true;
                    continue;
                }

                // if exception and no assigned column
                if ($exceptionFlag && !$exceptionColumn) {
                    $exceptionColumn = $parameter;
                    continue;
                }

                // if exception and there's assigned column
                if ($exceptionFlag && $exceptionColumn) {

                    array_push(
                        $whereColumns, 
                        [$exceptionColumn, '!=', $parameter]
                    );

                    $exceptionFlag = false;
                    continue;
                }

                // if normal, then add to where and its request data
                array_push($whereColumns, [
                    $parameter,
                    ($validator->getData())[$parameter]
                ]);
            }
            
            return DB::table($tableName)
                ->where($whereColumns)
                ->doesntExist();
        });
    }
}
