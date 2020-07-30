<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class WebAdminSetting extends Model
{
    protected $connection = 'mysql_dynamic';
    protected $table = 'web_admin_settings';
    public $timestamps = false;

    protected $guarded = ['*'];

    protected $fillable = ['*'];
}
