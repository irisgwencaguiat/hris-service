<?php

namespace App\Helpers;

use Illuminate\Http\Request;

class APIParam
{
    protected $request = null;

    protected $min_per_page = 5;

    protected $max_per_page = 1000;

    protected $sort_by_default = [];

    protected $sort_by_conditions = [];

    protected $filterables = [];

    protected $filter_columns = [];

    protected $accept_null_filter = false;

    public function __construct($request = null)
    {
        if (is_null($request) === false) {
            $this->request($null);
        }

        // default sort by
        $this->sort_by_default['created_at'] = 'ASC';
    }
    
    // set request
    public function request(Request $request): APIParam
    {
        $this->request = $request;

        return $this;
    }

    // set min_per_page
    public function minPerPage(int $minPerPage): APIParam
    {
        if ($minPerPage > 0) {
            $this->min_per_page = $minPerPage;
        }

        return $this;
    }

    // set max_per_page
    public function maxPerPage($maxPerPage): APIParam
    {
        
        $this->max_per_page = $maxPerPage;

        return $this;
    }

    // generate per_page
    public function generatePerPage(): int
    {
        if ($this->request === null) {
            return $this->min_per_page;
        }

        $requestedPerPage = $this->request->input(
            'per_page', 
            $this->min_per_page
        );

        // all
        if (
            $requestedPerPage === 'all' ||
            $requestedPerPage == -1
        ) {
            return 0;
        }

        $requestedPerPage = intval($requestedPerPage);

        // max
        if ($requestedPerPage >= $this->max_per_page) {
            return $this->max_per_page;
        }

        // min
        if ($requestedPerPage <= $this->min_per_page) {
            return $this->min_per_page;
        }

        return $requestedPerPage;
    }

    // sort by default
    public function sortByDefault(Array $sortByDefault = array()): APIParam
    {
        $this->sort_by_default = $sortByDefault;

        return $this;
    }

    // sort by conditions
    public function sortByConditions(Array $sortByConditions = array()): APIParam
    {
        $this->sort_by_conditions = $sortByConditions;

        return $this;
    }

    // generate sort by
    public function generateSortBy(): Array
    {
        if ($this->request === null) {
            return $this->sort_by_default;
        }

        if ($this->request->input('sort_by') == []) {
            return $this->sort_by_default;
        }
            
        $sortBy = $this->request->input('sort_by');
        $sortDesc = $this->request->input('sort_desc');
        $finalSortBy = [];
        $order = 'ASC';

        // traverse through request sort by
        for ($i = 0; $i < count($sortBy); $i++) {

            $order = (filter_var($sortDesc[$i], FILTER_VALIDATE_BOOLEAN)) ?
                'DESC' : 'ASC';

            // if column exists in conditions, then set the equivalent
            if (array_key_exists($sortBy[$i], $this->sort_by_conditions)) {
                
                foreach ($this->sort_by_conditions[$sortBy[$i]] as $condition) {
                    $finalSortBy[$condition] = $order;
                }

            } else {
                $finalSortBy[$sortBy[$i]] = $order;
            }
        }
        
        return $finalSortBy;
    }

    // set filterables
    public function filterables(Array $filterables = array()): APIParam
    {
        $this->filterables = $filterables;

        return $this;
    }

    // set filter_columns
    public function filterColumns(Array $filterColumns = array()): APIParam
    {
        $this->filter_columns = $filterColumns;

        return $this;
    }

    // accept null filters
    public function acceptNullFilter(): APIPAram
    {
        $this->accept_null_filter = true;

        return $this;
    }

    // dont accept null filters
    public function dontAcceptNullFilter(): APIParam
    {
        $this->accept_null_filter = false;

        return $this;
    }

    // generate filters
    public function generateFilters(): Array
    {
        if ($this->request === null) {
            return [];
        }

        // get filters in request
        $requestFilters = array_intersect_key(
            ($this->request->has('filters')) ?
                $this->request->input('filters') :
                [],
            array_flip($this->filterables)
        );

        // set the associated filter column
        foreach ($this->filter_columns as $key => $column) {

            if (array_key_exists($key, $requestFilters)) {
                $requestFilters[$column] = $requestFilters[$key];

                unset($requestFilters[$key]);
            }
        }

        // filter requested filters
        $filters = [];

        foreach ($requestFilters as $key => $value) {

            if ($this->accept_null_filter === true && $value == null) {
                continue;
            }

            // add to filters
            $filters[$key] = ($value === 'undefined') ? 
                null : $value;
        }

        return $filters;
    }

    // generate search
    public function generateSearch(): String
    {
        if ($this->request === null) {
            return '%';
        }

        return ($this->request->has('search')) ? 
            "%{$this->request->input('search')}%" : '%';
    }

    public function generate()
    {
        return $this->generateParam();
    }

    protected function generateParam()
    {
        return (object) [
            'search' => $this->generateSearch(),
            'per_page' => $this->generatePerPage(),
            'sort_by' => $this->generateSortBy(),
            'filters' => $this->generateFilters()
        ];
    }
}
