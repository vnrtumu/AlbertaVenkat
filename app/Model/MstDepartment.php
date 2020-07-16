<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MstDepartment extends Model
{
    protected $connection = 'mysql_dynamic';
    protected $table = 'mst_department';
    public $timestamps = false;
    protected $fillable = [
                            'idepartmentid',
                            'vdepcode',
                            'vdepartmentname',
                            'vdescription',
                            'isequence',
                            'estatus',
                            'ionupload',
                            'etransferstatus',
                            'LastUpdate',
                            'SID',
                            'starttime',
                            'endtime'
                        ];



}
