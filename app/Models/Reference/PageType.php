<?php

namespace App\Models\Reference;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\ActorObserverTrait;

class PageType extends BaseModel
{
    use HasFactory, 
        SoftDeletes,
        ActorObserverTrait;
    
    protected $table = 'ref_page_types';
    
    protected $primaryKey = 'page_type_id';
    
    protected $fillable = [
        'page_type_name',
    ];
}
