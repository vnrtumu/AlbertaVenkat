<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $connection = 'mysql';
    protected $table = 'mst_category';

    protected $primaryKey = 'icategoryid';
    public $timestamps = false;
}
