<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $table = 'tbl_logs';
    protected $primaryKey = 'log_id';

    protected $fillable = [
        'user_id',
        'action_taken',
        'status'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }
}
