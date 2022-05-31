<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrainLawTax extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tbl_train_law_taxes';

    protected $primaryKey = 'train_law_tax_id';

    protected $fillable = [
        'train_law_id',
        'annual_income_upper_boundary',
        'annual_income_lower_boundary',
        'lower_boundary_and_below',
        'upper_boundary_and_above',
        'tax_rate',
        'additional_tax_amount'
    ];
}
