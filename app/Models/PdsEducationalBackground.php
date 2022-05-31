<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PdsEducationalBackground extends Model
{
    use HasFactory;

    protected $table = 'tbl_pds_educational_backgrounds';

    protected $primaryKey = 'pds_educational_background_id';

    protected $guarded = [];

    public function refEducationLevel() {
        return $this->belongsTo(RefEducationLevel::class, 'education_level', 'educational_level_code')->withDefault();
    }
}
