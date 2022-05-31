<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    private $search_extras = [];

    /**
     * Scopes
     */
    public function scopeSearch($query, $search)
    {
        return $this->generateSearchQuery(
            $query, 
            $search, 
            $this->search_extras
        );
    }

    public function scopeFilters($query, $filters)
    {
        return $query->where($filters);
    }

    public function scopeSort($query, $sortBy)
    {
        return $query->when($sortBy, function ($query, $sortBy) {

            foreach($sortBy as $column => $order) {
                $query->orderBy($column, $order);
            }
        });
    }

    public function scopeUsingAPIParam($query, $apiParam)
    {
        // if there's search extra (joined tables for searching)
        $query->select("{$this->table}.*");

        return $query
            ->search($apiParam->search)
            ->filters($apiParam->filters)
            ->sort($apiParam->sort_by)
            ->paginate($apiParam->per_page);
    }

    public function scopeSearchExtras($query, $extras)
    {
        $this->search_extras = $extras;

        return $query;
    }

    /**
     * Methods
     */
    public function generateSearchQuery($query, $search, $searchExtras = [])
    {
        $searchable = [];

        // use filable when there's no searchable
        if (isset($this->searchable) && gettype($this->searchable) === 'array') {
            $searchable = $this->searchable;

        } else {
            $searchable = $this->fillable;
        }

        // add or where queries
        $searchQuery = $query->whereNested(function ($query) use ($search, $searchable, $searchExtras) {
            
            // add all searchable in model
            for ($i = 0; $i < count($searchable); $i++) {
                // use where in first element
                if ($i == 0) {
                    $query->where(
                        "{$this->table}.{$searchable[$i]}", 
                        'LIKE', 
                        $search
                    );

                    continue;
                }

                // use orWhere
                $query->orWhere(
                    "{$this->table}.{$searchable[$i]}", 
                    'LIKE', 
                    $search
                );
            }

            // add extras
            foreach ($searchExtras as $extra) {
                $query->orWhere($extra, 'LIKE', $search);
            }
        });

        return $searchQuery;
    }
}
