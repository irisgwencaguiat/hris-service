<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalnAssetRealProperty extends Model
{
    use HasFactory;

    protected $table = 'tbl_saln_asset_real_properties';

    protected $primaryKey = 'saln_asset_real_property_id';

    protected $guarded = [];
}
