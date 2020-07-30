<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PhysicalInventory extends Model
{
    protected $connection = 'mysql_dynamic';
    protected $table = 'trn_physicalinventory';
    public $timestamps = false;
    protected $fillable = [
        'ipiid',
        'vpinvtnumber',
        'vrefnumber',
        'nnettotal',
        'ntaxtotal',
        'dcreatedate',
        'estatus',
        'vordertitle',
        'vnotes',
        'dlastupdate',
        'vtype',
        'ilocid',
        'dcalculatedate',
        'dclosedate',
        'LastUpdate',
        'SID'
    ];
}

