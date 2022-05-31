<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PdsAssociationMembership extends Model
{
    use HasFactory;

    protected $table = 'tbl_pds_association_memberships';

    protected $primaryKey = 'pds_association_membership_id';

    protected $guarded = [];
}
