<?php

namespace App\Models\References;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Signatory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ref_signatories';

    protected $primaryKey = 'signatory_id';

    protected $fillable = [
        'employee_id',
        'e_signature_path'
    ];
}
