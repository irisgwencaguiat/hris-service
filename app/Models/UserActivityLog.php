<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserActivityLog extends Model
{
    use HasFactory;

    protected $table = 'tbl_user_activity_logs';
    protected $primaryKey = 'user_activity_log_id';
    protected $fillable = [
        'user_id',
        'type',
        'action',
        'remarks'
    ];
    
    public $timestamps = false;
    protected $appends = [
        'username'
    ];

    public function getUsernameAttribute()
    {
        return User::find($this->user_id)->username;
    }
}
