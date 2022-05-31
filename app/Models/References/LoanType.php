<?php

namespace App\Models\References;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoanType extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ref_loan_types';

    protected $primaryKey = 'loan_type_id';

    protected $fillable = [
        'loan_type_name',
        'loan_type_desc'
    ];
}
