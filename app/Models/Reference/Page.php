<?php

namespace App\Models\Reference;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\ActorObserverTrait;
use App\Models\UserTypePage;

class Page extends BaseModel
{
    use 
        HasFactory, 
        SoftDeletes,
        ActorObserverTrait;
    
    protected $table = 'ref_pages';
    
    protected $primaryKey = 'page_id';
    
    protected $fillable = [
        'page_name',
        'page_type_id',
        'route_name',
        'page_icon'
    ];


    /**
     * Appends
     */
    protected $appends = [
        'no_of_assigned_pages'
    ];

    public function getNoOfAssignedPagesAttribute()
    {
        return UserTypePage::
            where('page_id', $this->page_id)
            ->count();
    }


    /**
     * Relationships
     */
    public function type()
    {
        return $this->hasOne(
            PageType::class,
            'page_type_id',
            'page_type_id'
        )->withDefault();
    }
}
