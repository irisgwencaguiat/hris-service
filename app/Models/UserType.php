<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserType extends Model
{
    use HasFactory;

    protected $table = 'tbl_user_types';

    protected $primaryKey = 'user_type_id';

    protected $guarded = [];

    protected $fillable = [
        'user_type_name',
        'deleted_by',
        'created_by',
        'updated_by'
    ];

    public function user() {
        return $this->hasMany(User::class, 'user_id');
    }

    public function deleter() {
        return $this->belongsTo(User::class, 'deleted_by')->withDefault();
    }

    public function creator() {
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }

    public function updater() {
        return $this->belongsTo(User::class, 'updated_by')->withDefault();
    }
}
