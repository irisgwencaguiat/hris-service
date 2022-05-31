<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saln extends Model
{
    use HasFactory;

    protected $table = 'tbl_salns';

    protected $primaryKey = 'saln_id';

    protected $guarded = [];

    public function salnVersion()
    {
        return $this->hasOne(
            SalnVersions::class,
            'saln_version_id',
            'saln_version_id'
        )->withDefault();
    }
}
