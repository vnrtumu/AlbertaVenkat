<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $connection = 'mysql';
    protected $table = 'mst_department';

    protected $primaryKey = 'idepartmentid';
    public $timestamps = false;
}
