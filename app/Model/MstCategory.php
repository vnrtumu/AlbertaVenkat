<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MstCategory extends Model
{
    protected $connection = 'mysql_dynamic';
    protected $table = 'mst_category';
    public $timestamps = false;
}
