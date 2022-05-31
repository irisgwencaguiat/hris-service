<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PdsSkillsAndHobby extends Model
{
    use HasFactory;

    protected $table = 'tbl_pds_skills_and_hobbies';

    protected $primaryKey = 'pds_skills_and_hobby_id';

    protected $guarded = [];
}
