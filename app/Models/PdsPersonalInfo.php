<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Log;

class PdsPersonalInfo extends Model
{
    use HasFactory;

    protected $table = 'tbl_pds_personal_infos';

    protected $primaryKey = 'pds_personal_info_id';

    protected $guarded = [];
    
    /**
     * Relationships
     */
    public function resBgy()
    {
        return $this->hasOne(
            RefBarangay::class,
            'bgy_code',
            'residential_barangay'
        )->withDefault();
    }

    public function resCity()
    {
        return $this->hasOne(
            RefCity::class,
            'city_code',
            'residential_city'
        )->withDefault();
    }
}
