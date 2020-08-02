<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Model\Userpermission;
use App\Model\Permission;
use App\Model\UserDynamic;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

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
            // dd($user_id);
            $userPermsData = DB::connection('mysql_dynamic')->select("SELECT  mp.vpermissioncode FROM mst_permission mp join mst_userpermissions mup on mp.vpermissioncode = mup.permission_id where mp.vpermissiontype = 'WEB' ");
            // dd($userPermsData);
        }else{
            $user_id = Auth::user()->iuserid;
            $userPermsData = DB::connection('mysql_dynamic')->select("SELECT  mp.vpermissioncode FROM mst_permission mp join mst_userpermissions mup on mp.vpermissioncode = mup.permission_id where mp.vpermissiontype = 'WEB' and mup.status = 'Active' and mup.userid = ".$user_id);
            // dd($userPermsData);
        }
        $permsData = array();
        for($i = 0; $i < count($userPermsData); $i++ ){
            $permsData[] = $userPermsData[$i]->vpermissioncode;
        }
        // dd($permsData);

        session()->put('userPermsData', $permsData);

        session()->put('dbhost',  $data[0]->db_hostname);
        session()->put('dbname',  $data[0]->db_name);
        session()->put('dbuser',  $data[0]->db_username);
        session()->put('dbpassword',  $data[0]->db_password);

        session()->put('sid', $data[0]->id);
        session()->put('storeName', $data[0]->name);
        session()->put('stores', $data);
        // session()->put('permissionCode', $permissionCodes);

        $check_table_query = "SELECT * FROM information_schema.tables WHERE table_schema = '{$data[0]->db_name}' AND table_name = 'mst_version' LIMIT 1";

        $check_table = DB::connection('mysql_dynamic')->select($check_table_query);


        if(count($check_table) === 0){

            session()->put('new_database', false);

        } else {

            //========check store version =========
            $result = DB::connection('mysql_dynamic')->table('mst_version')->orderBy('LastUpdate', 'desc')->first();

            if($result->ver_no >= 3){
                session()->put('new_database', true);
            }else{
                session()->put('new_database', false);
            }

        }

        $date = date('Y-m-d');
        $fdate = date("Y-m-d", (strtotime($date)) - (7 * 24 * 60 * 60));
        $tdate = date("Y-m-d", (strtotime($date)) - (24 * 60 * 60));

        $ydate = date("m-d-Y", (strtotime($date)) - (24 * 60 * 60));


        $sdate = date("m-d-Y", (strtotime($date)) - (7 * 24 * 60 * 60));
        $date2 = date('m-d-Y');
        $edate = $date2 . ' 23:59:59';
        // get data for sales
        $salesData = DB::connection('mysql_dynamic')->select("SELECT count(isalesid) AS total FROM trn_sales WHERE (date_format(dtrandate,'%Y-%m-%d %H:%i:%s') BETWEEN '" . $date . " 00:00:00' AND '" . $date . " 23:59:59') AND SID='" . (int) $data[0]->id . "'");
        $sales = $salesData[0]->total;
        if ($sales > 0) {
            $output['today'] = $sales;
        } else {
            $output['today'] = 0;
        }

        $salesYesterday = DB::connection('mysql_dynamic')->select("SELECT count(isalesid) AS total FROM trn_sales WHERE (date_format(dtrandate,'%Y-%m-%d %H:%i:%s') BETWEEN '" . $tdate . " 00:00:00' AND '" . $tdate . " 23:59:59') AND SID='" . (int) $data[0]->id . "'");

        $salesYes = $salesYesterday[0]->total;

        if ($salesYes > 0) {
            $output['yesterday'] = $salesYes;
        } else {
            $output['yesterday'] = 0;
        }

        $fdate1 = $fdate . ' 00:00:00';
        $date1 = $date . ' 23:59:59';

        $salesWeek = DB::connection('mysql_dynamic')->select("SELECT count(isalesid) AS total FROM trn_sales WHERE date_format(dtrandate,'%Y-%m-%d %H:%i:%s') >= '" . $fdate1 . "' and date_format(dtrandate,'%Y-%m-%d %H:%i:%s') <= '" . $date1 . "' AND SID='" . (int) $data[0]->id . "'");
        $salesWee = $salesWeek[0]->total;
        if ($salesWee > 0) {
            $output['week'] = $salesWee;
        } else {
            $output['week'] = 0;
        }

        return view('dashboard', compact('output'));
    }

    public function dashContent(Request $request)
    {
        $input = $request->all();
        $sid  = (int) $input['sid'];

        if (Auth::user()->user_role == "SuperAdmin") {
            $data = DB::table('store_mw_users')
                ->join('user_stores', 'store_mw_users.iuserid', '=', 'user_stores.user_id')
                ->join('stores', 'user_stores.store_id', '=', 'stores.id')
                ->select('stores.id', 'stores.name', 'stores.db_name', 'stores.db_username', 'stores.db_password', 'stores.db_hostname')
                ->get();
        } else {
            $data = DB::table('store_mw_users')
                ->join('user_stores', 'store_mw_users.iuserid', '=', 'user_stores.user_id')
                ->join('stores', 'user_stores.store_id', '=', 'stores.id')
                ->select('stores.id', 'stores.name', 'stores.db_name', 'stores.db_username', 'stores.db_password', 'stores.db_hostname')
                ->where('store_mw_users.iuserid', '=', Auth::user()->iuserid)
                ->get();
        }

        for ($i = 0; $i < count($data); $i++) {

            if ((int) $data[$i]->id == $sid) {
                // echo $i. "<br />". $data[$i]->id ;
                // dd($data[$i]->id,$sid);
                config(['database.connections.mysql_dynamic' => [
                    'driver'    => 'mysql',
                    'host'      =>  $data[$i]->db_hostname,
                    'database'  =>  $data[$i]->db_name,
                    'username'  =>  $data[$i]->db_username,
                    'password'  =>  $data[$i]->db_password,
                    'charset'   => 'utf8',
                    'collation' => 'utf8_unicode_ci',
                    'prefix'    => '',
                    'strict'    => false,
                ]]);

                session()->put('dbhost',  $data[$i]->db_hostname);
                session()->put('dbname',  $data[$i]->db_name);
                session()->put('dbuser',  $data[$i]->db_username);
                session()->put('dbpassword',  $data[$i]->db_password);
                session()->put('sid', $data[$i]->id);
                session()->put('storeName', $data[$i]->name);

                //========check store version =========
                $result = DB::connection('mysql_dynamic')->table('mst_version')->orderBy('LastUpdate', 'desc')->first();

                if($result->ver_no >= 3){
                    session()->put('new_database', true);
                }else{
                    session()->put('new_database', false);
                }
            }
        }
        session()->put('stores', $data);

        $date = date('Y-m-d');
        $fdate = date("Y-m-d", (strtotime($date)) - (7 * 24 * 60 * 60));
        $tdate = date("Y-m-d", (strtotime($date)) - (24 * 60 * 60));

        $ydate = date("m-d-Y", (strtotime($date)) - (24 * 60 * 60));


        $sdate = date("m-d-Y", (strtotime($date)) - (7 * 24 * 60 * 60));
        $date2 = date('m-d-Y');
        $edate = $date2 . ' 23:59:59';
        // get data for sales
        $salesData = DB::connection('mysql_dynamic')->select("SELECT count(isalesid) AS total FROM trn_sales WHERE (date_format(dtrandate,'%Y-%m-%d %H:%i:%s') BETWEEN '" . $date . " 00:00:00' AND '" . $date . " 23:59:59') AND SID='" . (int) $sid . "'");
        $sales = $salesData[0]->total;
        if ($sales > 0) {
            $output['today'] = $sales;
        } else {
            $output['today'] = 0;
        }

        $salesYesterday = DB::connection('mysql_dynamic')->select("SELECT count(isalesid) AS total FROM trn_sales WHERE (date_format(dtrandate,'%Y-%m-%d %H:%i:%s') BETWEEN '" . $tdate . " 00:00:00' AND '" . $tdate . " 23:59:59') AND SID='" . (int) $sid . "'");

        $salesYes = $salesYesterday[0]->total;

        if ($salesYes > 0) {
            $output['yesterday'] = $salesYes;
        } else {
            $output['yesterday'] = 0;
        }

        $fdate1 = $fdate . ' 00:00:00';
        $date1 = $date . ' 23:59:59';

        $salesWeek = DB::connection('mysql_dynamic')->select("SELECT count(isalesid) AS total FROM trn_sales WHERE date_format(dtrandate,'%Y-%m-%d %H:%i:%s') >= '" . $fdate1 . "' and date_format(dtrandate,'%Y-%m-%d %H:%i:%s') <= '" . $date1 . "' AND SID='" . (int) $sid . "'");
        $salesWee = $salesWeek[0]->total;
        if ($salesWee > 0) {
            $output['week'] = $salesWee;
        } else {
            $output['week'] = 0;
        }

        return view('dashboard', compact('output'));
    }
}
