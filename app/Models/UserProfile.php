<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $table = 'tbl_user_profiles';
    protected $primaryKey = 'user_profile_id';
    protected $fillable = [
        'user_id',
        'last_name',
        'first_name',
        'middle_name',
        'suffix',
        'email',
        'user_type_id',
        'user_classification_id'
    ];

    public function userType()
    {
        return $this->hasOne(UserType::class, 'user_type_id', 'user_type_id')->withDefault();
    }

    public function userClassification()
    {
        return $this->hasOne(RefUserClassification::class, 'user_classification_id', 'user_classification_id')->withDefault();
    }

    
}
