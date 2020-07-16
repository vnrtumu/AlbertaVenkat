<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MstUserpermissiongroup extends Model
{
    protected $connection = 'mysql_dynamic';
    protected $table = 'mst_userpermissiongroup';
    public $timestamps = false;
    protected $fillable = [
                            'iuserid',
                            'ipermissiongroupid',
                            'LastUpdate',
                            'SID',
                            'Id',
                        ];


}
