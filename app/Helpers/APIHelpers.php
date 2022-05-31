<?php

if(!function_exists('apiFilters'))
{
    /**
     * Generate filters from needed in api
     * It returns array of column => value for query builder's 'where' condition
     * 
     * @param array $request
     * @param array $filterables (where ['request attribute name' => 'query column name'] as pair)
     * @return array
     */
    function apiFilters($request = [], $filterables = [], $params = [], $mode = 'NOT STRICT')
    {
        // Get the filterable data in request only
        $filteredRequest = array_intersect_key($request->input('filters', []), $filterables);

        // Get the filterable data in params
        $filteredParams = array_intersect_key($params, $filterables);
        $filtered = array_merge($filteredRequest, $filteredParams);

        // Assign the associated query column for the filters
        $filters = [];
        foreach($filtered as $key => $value) {
            // If strict mode, then null values will not be included
            if ($mode == 'STRICT' && $value == null) continue;

            // Add to filters
            $filters[$filterables[$key]] = ($value === 'undefined') ? null : $value;
        }

        return $filters;
    }
}

if(!function_exists('apiPerPage'))
{
    /**
     * Generate per_page from the request
     * 
     * @param int $requestPerPage
     * @param int $minPerPage
     * @param int $maxPerPage
     * @return int
     */
    function apiPerPage($requestPerPage, $minPerPage, $maxPerPage)
    {
        if ($requestPerPage == -1) return $maxPerPage;
        
        return ($requestPerPage >= $minPerPage && $requestPerPage <= $maxPerPage) ? $requestPerPage : $minPerPage;
    }
}

if(!function_exists('apiSortBy'))
{
    /**
     * Generate sortBys from sortBy and sortDesc in api
     * It returns array of column => value for query builder's 'orderBy'
     * 
     * @param array $sortBy
     * @param array $sortDesc
     * @param array $conditions where element is 'column_name' => [equivalent database column names]
     * @param array $default
     * @return array
     */
    function apiSortBy ($sortBy = [], $sortDesc = [], $conditions = [], $default = [])
    {
        // If sortBy is not empty, generate the sort
        // else assign the default to sort
        if ($sortBy != []) {
            $sort = [];
            $tempOrder = 'ASC';

            // traverse through sortBy columns
            for ($i = 0; $i < count($sortBy); $i++) {
                $tempOrder = (filter_var($sortDesc[$i], FILTER_VALIDATE_BOOLEAN)) ? 
                    'DESC' : 'ASC';
                
                // If column name exists in conditions, then set the equivalent column names
                if (array_key_exists($sortBy[$i], $conditions))
                    foreach ($conditions[$sortBy[$i]] as $condition)
                        $sort[$condition] = $tempOrder;
                else
                    $sort[$sortBy[$i]] = $tempOrder;
            }
        }
        else {
            $sort = $default;
        }

        return $sort;
    }
}

if (!function_exists('apiParam')) {
    function apiParam($request = null)
    {
        return new \App\Helpers\APIParam($request);
    }
}