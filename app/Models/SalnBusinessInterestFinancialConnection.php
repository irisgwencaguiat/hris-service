<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalnBusinessInterestFinancialConnection extends Model
{
    use HasFactory;

    protected $table = 'tbl_saln_business_interest_financial_connections';

    protected $primaryKey = 'saln_business_interest_financial_connection_id';

    protected $guarded = [];
}
