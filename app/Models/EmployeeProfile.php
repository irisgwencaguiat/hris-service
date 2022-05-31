<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeProfile extends Model
{
    use HasFactory;

    protected $table = 'tbl_employee_profiles';

    protected $guarded = [];

    protected $primaryKey = 'employee_id';

    protected $casts = [
        'date_of_birth'  => 'date:Y-m-d',
    ];

    /**
     * Additional Attr
     */
    protected $appends = [
        'complete_residential_address'
    ];
    
    public function getCompleteResidentialAddressAttribute()
    {
        $residentialAddress = '';

        if ($this->residential_address_line_1) {
            $residentialAddress .= $this->residential_address_line_1 . ' ';
        }

        if ($this->residential_street) {
            $residentialAddress .= $this->residential_street;
        }

        if ($this->residential_village) {
            $residentialAddress .= ', ' . $this->residential_village;
        }

        if ($this->residential_barangay) {
            $bgyName = RefBarangay::
                find(
                    str_pad($this->residential_barangay, 9, '0', STR_PAD_LEFT)
                )->bgy_name;

            $residentialAddress .= ', ' . $bgyName;
        }

        if ($this->residential_city) {
            $cityName = RefCity::
                find(
                    str_pad($this->residential_city, 6, '0', STR_PAD_LEFT)
                )->city_name;

            $residentialAddress .= ', ' . $cityName;
        }

        if ($this->residential_zip_code) {
            $residentialAddress .= ', ' . $this->residential_zip_code;
        }

        return $residentialAddress;
    }
}
