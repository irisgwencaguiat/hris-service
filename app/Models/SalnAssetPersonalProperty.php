<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalnAssetPersonalProperty extends Model
{
    use HasFactory;

    protected $table = 'tbl_saln_asset_personal_properties';

    protected $primaryKey = 'saln_asset_personal_property_id';

    protected $guarded = [];
}
