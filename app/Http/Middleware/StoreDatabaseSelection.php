<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Config;
use Session;

class StoreDatabaseSelection
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // dd(Session::all());
        Config::set('database.connections.mysql_dynamic', array(

            'driver' => 'mysql',
            'host' =>  Session::get('dbhost'),
            'port' => '3306',
            'database' => Session::get('dbname'),
            'username' => Session::get('dbuser'),
            'password' => Session::get('dbpassword'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,

        ));
        return $next($request);

    }
}
