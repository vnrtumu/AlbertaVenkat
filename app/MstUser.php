<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class MstUser extends Model
{
    protected $connection = 'mysql_dynamic';
    protected $table = 'mst_user';
    //protected $fillable = [];
}
