<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\ActorObserverTrait;

class UserTypePage extends BaseModel
{
    use 
        HasFactory, 
        SoftDeletes,
        ActorObserverTrait;
    
    protected $table = 'tbl_user_type_pages';
    
    protected $primaryKey = 'user_type_page_id';
    
    protected $fillable = [
        'user_type_id',
        'user_classification_id',
        'page_id',

        'order_no',
        'parent_user_type_page_id',
        'has_sub_pages',

        'is_activated'
    ];

    protected $appends = [
        'total_sub_pages',
    ];

    public function getTotalSubPagesAttribute()
    {
        return UserTypePage::where(
            'parent_user_type_page_id', 
            $this->user_type_page_id
        )->count();
    }


    // relationships
    public function page()
    {
        return $this->hasOne(
            Reference\Page::class,
            'page_id',
            'page_id'
        )->withDefault();
    }

    public function subPages()
    {
        return $this->hasMany(
            UserTypePage::class,
            'parent_user_type_page_id',
            'user_type_page_id'
        );
    }

    public function userType()
    {
        return $this->hasOne(
            UserType::class,
            'user_type_id',
            'user_type_id'
        )->withDefault();
    }

    public function userClassification()
    {
        return $this->hasOne(
            RefUserClassification::class,
            'user_classification_id',
            'user_classification_id'
        )->withDefault();
    }

    public function parentUserTypePage()
    {
        return $this->belongsTo(
            UserTypePage::class,
            'parent_user_type_page_id',
            'user_type_page_id'
        )->withDefault();
    }

    /**
     * Local Scopes
     */
    public function scopeOfUserType($query, $userTypeId)
    {
        return $query->where('user_type_id', $userTypeId);
    }

    public function scopeActivated($query)
    {
        return $query->where('is_activated', true);
    }

    public function scopeOfUserClassification($query, $userTypeId)
    {
        return $query->where('user_classification_id', $userTypeId);
    }
    
    public function scopeWithoutParentPage($query)
    {
        return $query->whereNull('parent_user_type_page_id');
    }
}
