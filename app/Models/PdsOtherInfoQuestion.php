<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PdsOtherInfoQuestion extends Model
{
    use HasFactory;

    protected $table = 'tbl_pds_other_info_questions';

    protected $primaryKey = 'pds_other_info_question_id';

    protected $guarded = [];

    protected $casts = [
        'question_34a' => 'boolean',
        'question_34b' => 'boolean',
        'question_35a' => 'boolean',
        'question_35b' => 'boolean',
        'question_36' => 'boolean',
        'question_37' => 'boolean',
        'question_38a' => 'boolean',
        'question_38b' => 'boolean',
        'question_39' => 'boolean',
        'question_40a' => 'boolean',
        'question_40b' => 'boolean',
        'question_40c' => 'boolean'
    ];
}
