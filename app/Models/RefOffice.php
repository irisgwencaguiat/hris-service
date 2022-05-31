<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefOffice extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'ref_offices';

    protected $primaryKey = 'office_id';

    protected $fillable = [
        'office_name',
        'office_code'
    ];
    
    protected $appends = [
        'no_of_total_plantillas',
        'no_of_taken_plantillas',
        'no_of_remaining_plantillas'
    ];

    public function getNoOfTotalPlantillasAttribute()
    {
        return RefPlantilla::where('office_id', $this->office_id)->count();
    }

    public function getNoOfTakenPlantillasAttribute()
    {
        return Employee::
            join('ref_plantillas', 'ref_plantillas.plantilla_id', 'tbl_employees.plantilla_id')
            ->where('ref_plantillas.office_id', $this->office_id)
            ->distinct('ref_plantillas.plantilla_id')
            ->count();
    }

    public function getNoOfRemainingPlantillasAttribute()
    {
        $totalPlantillas = RefPlantilla::where('office_id', $this->office_id)->count();
        $takenPlantillas =  Employee::
            join('ref_plantillas', 'ref_plantillas.plantilla_id', 'tbl_employees.plantilla_id')
            ->where('ref_plantillas.office_id', $this->office_id)
            ->distinct('ref_plantillas.plantilla_id')
            ->count();

        return $totalPlantillas - $takenPlantillas;
    }

    public function departments()
    {
        return $this->hasMany(RefDepartment::class, 'office_id', 'office_id');
    }
}
