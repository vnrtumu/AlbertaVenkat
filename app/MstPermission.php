<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MstPermission extends Model
{
    protected $connection = 'mysql_dynamic';
    protected $table = 'mst_permission';
}
