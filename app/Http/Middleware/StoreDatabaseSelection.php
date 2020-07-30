<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Config;
use Session;
use Illuminate\Support\Facades\DB;

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
        if(Session::has('dbhost') && Session::has('dbname') && Session::has('dbuser') && Session::has('dbpassword')){
            //if the store credentials are there in session
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
        } elseif(isset(Auth::user()->id)) {

            //if the store credentials are not there in session
            if (Auth::user()->user_role == "SuperAdmin") {
                $data = DB::table('store_mw_users')
                    ->join('user_stores', 'store_mw_users.iuserid', '=', 'user_stores.user_id')
                    ->join('stores', 'user_stores.store_id', '=', 'stores.id')
                    ->select('stores.id', 'stores.name', 'stores.db_name', 'stores.db_username', 'stores.db_password', 'stores.db_hostname')
                    ->get();
            } elseif( Auth::user()->sid == 0) {
                $data = DB::table('store_mw_users')
                    ->join('user_stores', 'store_mw_users.iuserid', '=', 'user_stores.user_id')
                    ->join('stores', 'user_stores.store_id', '=', 'stores.id')
                    ->select('stores.id', 'stores.name', 'stores.db_name', 'stores.db_username', 'stores.db_password', 'stores.db_hostname')
                    ->where('store_mw_users.iuserid', '=', Auth::user()->iuserid)
                    ->get();
            }else {
                $data = DB::table('store_mw_users')
                ->join('stores', 'store_mw_users.sid', '=', 'stores.id')
                ->select('stores.id', 'stores.name', 'stores.db_name', 'stores.db_username', 'stores.db_password', 'stores.db_hostname')
                ->get();
            }

            if(isset($data[0]) && count((array)$data[0]) > 0){
                config(['database.connections.mysql_dynamic' => [
                    'driver'    => 'mysql',
                    'host'      =>  $data[0]->db_hostname,
                    'database'  =>  $data[0]->db_name,
                    'username'  =>  $data[0]->db_username,
                    'password'  =>  $data[0]->db_password,
                    'charset'   => 'utf8',
                    'collation' => 'utf8_unicode_ci',
                    'prefix'    => '',
                    'strict'    => false,
                ]]);


                if(Auth::user()->user_role && Auth::user()->sid == 0){
                    $user_id = Auth::user()->iuserid;
                    $userPermsData = DB::connection('mysql_dynamic')->select("SELECT  mp.vpermissioncode FROM mst_permission mp join mst_userpermissions mup on mp.vpermissioncode = mup.permission_id where mp.vpermissiontype = 'WEB' and mup.status = 'Active' ");
                }else{
                    $user_id = Auth::user()->iuserid;
                    $userPermsData = DB::connection('mysql_dynamic')->select("SELECT  mp.vpermissioncode FROM mst_permission mp join mst_userpermissions mup on mp.vpermissioncode = mup.permission_id where mp.vpermissiontype = 'WEB' and mup.status = 'Active' and mup.userid = ".$user_id);
                }
                // dd($userPermsData);
                $permsData = array();
                for($i = 0; $i < count($userPermsData); $i++ ){
                    $permsData = $userPermsData[$i]->vpermissioncode;
                }


                session()->put('userPermsData', $permsData);


                session()->put('dbhost',  $data[0]->db_hostname);
                session()->put('dbname',  $data[0]->db_name);
                session()->put('dbuser',  $data[0]->db_username);
                session()->put('dbpassword',  $data[0]->db_password);

                session()->put('sid', $data[0]->id);
                session()->put('storeName', $data[0]->name);
                session()->put('stores', $data);
            } else {
                return route('/');
            }

        } else {
            return route('/');
        }

        return $next($request);

    }
}
