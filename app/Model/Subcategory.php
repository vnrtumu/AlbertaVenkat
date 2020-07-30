<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    protected $connection = 'mysql';
    protected $table = 'mst_subcategory';

    protected $primaryKey = 'subcat_id';
    public $timestamps = false;
}
