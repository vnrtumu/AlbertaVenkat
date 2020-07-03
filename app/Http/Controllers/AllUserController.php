<?php

namespace App\Http\Controllers;

use App\MstUser;
use App\User;
use App\MstPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;
use App\MstUserpermission;
use Illuminate\Support\Facades\Auth;
use App\MstPermissiongroup;
use App\MstUserpermissiongroup;


class AllUserController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = MstUser::orderBy('iuserid', 'DESC')->paginate(20);

        return view('User.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = MstPermission::all();
        $mstPermissiongroup = MstPermissiongroup::all();
        return view('User.create', compact('permissions', 'mstPermissiongroup'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function encodePassword($pass_string)
    {
        $encdata = urlencode($pass_string);
        $en_pass = base64_encode($encdata);

        return $en_pass;
    }

    //user creation function
    public function store(Request $request)
    {
        $input = $request->all();
        $devices = $input['device'];
        $data = [];

        for ($i = 0; $i < count($devices); $i++) {
            if ($devices[$i] ==  'pos') {
                $data['pos'] = 'Y';
            }
            if ($devices[$i] ==  'web') {
                $data['web'] = 'Y';
            }
            if ($devices[$i] ==  'mob') {
                $data['mob'] = 'Y';
            }
        }
        if (isset($input['vuserid'])) {
            $duplicateUserid = MstUser::where('vuserid', '=', $input['vuserid'])->get();
        }
        if (isset($input['vemail'])) {
            $duplicateEmail = User::where([['vemail', '=', $input['vemail']], ['estatus', '=',  'Active']])->get();
            $duplicateMstuser = MstUser::where('vemail', '=', $input['vemail'])->get();
        }

        if (isset($duplicateUserid) && count($duplicateUserid) > 0) {
            return redirect('users/create')
                ->withErrors("Pos User id is already exists.")
                ->withInput();
        } elseif (isset($duplicateEmail) && count($duplicateEmail) > 0 && count($duplicateMstuser) > 0) {
            return redirect('users/create')
                ->withErrors("User Email is already exists.")
                ->withInput();
        } else {
            //checkwether pos user or (Mobile & Web) user
            if (isset($data['pos']) == 'Y' && isset($data['web']) == '' && isset($data['mob']) == '') {
                $encdoe_password = $this->encodePassword($input['vpassword']);
                $mst_user = MstUser::create([
                    'mob_user'  => isset($data['mob']) ? 'Y' : 'N',
                    'web_user'  => isset($data['web']) ? 'Y' : 'N',
                    'pos_user'  => isset($data['pos']) ? 'Y' : 'N',
                    'vfname'    => $input['vfname'],
                    'vlname'    => $input['vlname'],
                    'vaddress1' => $input['vaddress1'],
                    'vaddress2' => $input['vaddress2'],
                    'vcity'     => $input['vcity'],
                    'vstate'    => $input['vstate'],
                    'vzip'      => $input['vzip'],
                    'vcountry'  => $input['vcountry'],
                    'vphone'    => $input['vphone'],
                    'vuserid'   => $input['vuserid'],
                    'vpassword' => $encdoe_password,
                    'vusertype' => $input['vusertype'],
                    'vpasswordchange' => $input['vpasswordchange'],
                    'vuserbarcode' => $input['vuserbarcode'],
                    'estatus'   =>  $input['estatus'],
                    'SID'       => session()->get('sid'),
                ]);

                User::create([
                    'mob_user'  => isset($data['mob']) ? 'Y' : 'N',
                    'web_user'  => isset($data['web']) ? 'Y' : 'N',
                    'fname'     => $input['vfname'],
                    'lname'     => $input['vlname'],
                    'user_role' => $input['vusertype'],
                    'iuserid'   => $mst_user->id,
                    'estatus'   => $input['estatus'],
                    'SID'       => session()->get('sid'),
                ]);
            } else {
                $Email = User::where([['vemail', '=', $input['vemail']], ['estatus', '=',  'Inactive']])->get();

                if (count($Email) > 0) {
                    $encdoe_mwpassword = password_hash($input['mwpassword'], PASSWORD_BCRYPT);
                    $encdoe_password = $this->encodePassword($input['vpassword']);
                    $mst_user = MstUser::create([
                        'mob_user'  => isset($data['mob']) ? 'Y' : 'N',
                        'web_user'  => isset($data['web']) ? 'Y' : 'N',
                        'pos_user'  => isset($data['pos']) ? 'Y' : 'N',
                        'vfname'    => $input['vfname'],
                        'vlname'    => $input['vlname'],
                        'vaddress1' => $input['vaddress1'],
                        'vaddress2' => $input['vaddress2'],
                        'vcity'     => $input['vcity'],
                        'vstate'    => $input['vstate'],
                        'vzip'      => $input['vzip'],
                        'vcountry'  => $input['vcountry'],
                        'vphone'    => $input['vphone'],
                        'vuserid'   => $input['vuserid'],
                        'vpassword' => $encdoe_password,
                        'vusertype' => $input['vusertype'],
                        'vpasswordchange' => $input['vpasswordchange'],
                        'vuserbarcode' => $input['vuserbarcode'],
                        'estatus'   =>  $input['estatus'],
                        'SID'       => session()->get('sid'),
                        'mwpassword' => $encdoe_mwpassword,
                        'vemail' => $input['vemail'],
                    ]);
                    User::where([['vemail', '=', $input['vemail']], ['estatus', '=',  'Inactive']])->update([
                        'mob_user'  => isset($data['mob']) ? 'Y' : 'N',
                        'web_user'  => isset($data['web']) ? 'Y' : 'N',
                        'fname'     => $input['vfname'],
                        'lname'     => $input['vlname'],
                        'user_role' => $input['vusertype'],
                        'iuserid'   => $mst_user->id,
                        'estatus'   => 'Active',
                        'SID'       => session()->get('sid'),
                        'password' => $encdoe_mwpassword,
                        'vemail' => $input['vemail'],
                    ]);
                } else {
                    $encdoe_mwpassword = password_hash($input['mwpassword'], PASSWORD_BCRYPT);
                    $encdoe_password = $this->encodePassword($input['vpassword']);
                    $mst_user = MstUser::create([
                        'mob_user'  => isset($data['mob']) ? 'Y' : 'N',
                        'web_user'  => isset($data['web']) ? 'Y' : 'N',
                        'pos_user'  => isset($data['pos']) ? 'Y' : 'N',
                        'vfname'    => $input['vfname'],
                        'vlname'    => $input['vlname'],
                        'vaddress1' => $input['vaddress1'],
                        'vaddress2' => $input['vaddress2'],
                        'vcity'     => $input['vcity'],
                        'vstate'    => $input['vstate'],
                        'vzip'      => $input['vzip'],
                        'vcountry'  => $input['vcountry'],
                        'vphone'    => $input['vphone'],
                        'vuserid'   => $input['vuserid'],
                        'vpassword' => $encdoe_password,
                        'vusertype' => $input['vusertype'],
                        'vpasswordchange' => $input['vpasswordchange'],
                        'vuserbarcode' => $input['vuserbarcode'],
                        'estatus'   =>  $input['estatus'],
                        'SID'       => session()->get('sid'),
                        'mwpassword' => $encdoe_mwpassword,
                        'vemail' => $input['vemail'],
                    ]);
                    User::create([
                        'mob_user'  => isset($data['mob']) ? 'Y' : 'N',
                        'web_user'  => isset($data['web']) ? 'Y' : 'N',
                        'fname'     => $input['vfname'],
                        'lname'     => $input['vlname'],
                        'user_role' => $input['vusertype'],
                        'iuserid'   => $mst_user->id,
                        'estatus'   => $input['estatus'],
                        'SID'       => session()->get('sid'),
                        'password'  => $encdoe_mwpassword,
                        'vemail'    => $input['vemail'],
                    ]);
                }
            }
        }

        foreach ($input['permission'] as $permission) {
            MstUserpermission::create([
                'status'        => 'Active',
                'created_id'    => Auth::user()->iuserid,
                'permission_id' => $permission,
                'updated_id'    => Auth::user()->iuserid,
                'userid'        => $mst_user->id,
                'SID'           => session()->get('sid')
            ]);
        }
        $group = MstPermissiongroup::where('vgroupname', '=', $input['vusertype'])->get();
        if (count($group) > 0) {
            $ipermissiongroupid = $group[0]->ipermissiongroupid;
            $que = MstUserpermissiongroup::where('iuserid', '=', Auth::user()->iuserid)->get();
            if (count($que) > 0) {
                MstUserpermissiongroup::where('iuserid', '=', Auth::user()->iuserid)->update(['ipermissiongroupid' => $ipermissiongroupid]);
            } else {
                MstUserpermissiongroup::create(['iuserid' => Auth::user()->iuserid, 'ipermissiongroupid' => $ipermissiongroupid, 'SID' => session()->get('sid')]);
            }
        }
        return redirect('users')->with('message', 'User Created Successfully');
    }



    public function edit(MstUser $mstUser, $iuserid)
    {
        $permissions = MstPermission::all();
        $mstPermissiongroup = MstPermissiongroup::all();
        $user = MstUser::where('iuserid', '=', $iuserid)->get();
        $users = $user[0];
        $checkedPermission = MstUserpermission::where('userid', '=', $iuserid)->get()->toArray();

        for ($i = 0; $i < count($checkedPermission); $i++) {
            $dataPerCheck[] = $checkedPermission[$i]['permission_id'];
        }
        return view('User.edit', compact('permissions', 'mstPermissiongroup', 'users', 'dataPerCheck'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AllUser  $allUser
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MstUser $mstUser, $iuserid)
    {
        $input = $request->all();
        $devices = $input['device'];
        $data = [];
        for ($i = 0; $i < count($devices); $i++) {
            if ($devices[$i] ==  'pos') {
                $data['pos'] = 'Y';
            }
            if ($devices[$i] ==  'web') {
                $data['web'] = 'Y';
            }
            if ($devices[$i] ==  'mob') {
                $data['mob'] = 'Y';
            }
        }
        $que = MstUserpermission::where('userid', '=', $iuserid)->get()->toArray();
        for ($i = 0; $i < count($que); $i++) {
            $list_of_permissions[] = $que[$i]['permission_id'];
        }
        foreach ($input['permission'] as $permission) {
            if (in_array($permission, $list_of_permissions)) {
                $get_id = MstUserpermission::where([['permission_id', '=', $permission], ['userid', '=', $iuserid]])->get();
                $mst_perm_id = $get_id[0]->Id;
                MstUserpermission::where('Id', '=', $mst_perm_id)->update(['permission_id' => $permission, 'status' => 'Active']);
            } else {
                MstUserpermission::create([
                    'status'        => 'Active',
                    'created_id'    => Auth::user()->iuserid,
                    'permission_id' => $permission,
                    'updated_id'    => Auth::user()->iuserid,
                    'userid'        => $iuserid,
                    'SID'           => session()->get('sid')
                ]);
            }
            if (($k = array_search($permission, $list_of_permissions)) !== false) {
                unset($list_of_permissions[$k]);
            }
        }
        // for removing permissoion
        foreach ($list_of_permissions as $permission) {
            $get_id = MstUserpermission::where([['permission_id', '=', $permission], ['userid', '=', $iuserid]])->get();
            $mst_perm_id = $get_id[0]->Id;
            MstUserpermission::where('Id', '=', $mst_perm_id)->update(['status' => 'Inactive']);
        }
        $group = MstPermissiongroup::where('vgroupname', '=', $input['vusertype'])->get();
        if (count($group) > 0) {
            $ipermissiongroupid = $group[0]->ipermissiongroupid;
            $que = MstUserpermissiongroup::where('iuserid', '=', Auth::user()->iuserid)->get();
            if (count($que) > 0) {
                MstUserpermissiongroup::where('iuserid', '=', Auth::user()->iuserid)->update(['ipermissiongroupid' => $ipermissiongroupid]);
            } else {
                MstUserpermissiongroup::create(['iuserid' => Auth::user()->iuserid, 'ipermissiongroupid' => $ipermissiongroupid, 'SID' => session()->get('sid')]);
            }
        }
        $mst_user = MstUser::where('iuserid', '=', $iuserid)->first();
        // dd($mst_user->vemail);
        $store_mw_User = User::where('vemail', '=', $mst_user->vemail)->get();
        $db_mst_user = $mst_user->mwpassword;
        $db_store_mw_user = $store_mw_User[0]->password;
        $dbvemail = $mst_user->vemail;
        $check_pwd_entered = 0;

        if (strlen($input['mwpassword'] > 20)) {
            $encdoe_mwpassword = password_hash($input['mwpassword'], PASSWORD_DEFAULT);
            $check_pwd_entered = 1;
        } else {
            $encdoe_mwpassword = $db_store_mw_user;
        }

        //encode the POS password
        // $encdoe_password = $this->encodePassword($input['vpassword']);

        if (isset($input['vpassword'])) {
            $encdoe_password = $input['vpassword'];
        } else {
            $encdoe_password = $db_mst_user;
        }

        // check if the entered email is different from the one in db
        if (isset($input['vemail'])  && $mst_user->vemail != $input['vemail']) {
            $vemail = $input['vemail'];
            if (isset($input['vemail'])) {
                $duplicateEmail = User::where([['vemail', '=', $input['vemail']], ['estatus', '=',  'Active']])->get();
                $duplicateMstuser = MstUser::where('vemail', '=', $input['vemail'])->get();
            }
        } else {
            $vemail = $mst_user->vemail;
        }
        if (isset($input['vuserid']) && $mst_user->vuserid != $input['vuserid']) {
            $vuserid = $input['vuserid'];
            if (isset($input['vuserid'])) {
                $duplicateUserid = MstUser::where('vuserid', '=', $input['vuserid'])->get();
            }
        } else {
            $vuserid = $mst_user->vuserid;
        }



        if (isset($duplicateUserid) && count($duplicateUserid) > 0) {
            return redirect('users/edit', $iuserid)
                ->withErrors("Pos User id is already exists.")
                ->withInput();
        } elseif (isset($duplicateEmail) && count($duplicateEmail) > 0 && count($duplicateMstuser) > 0) {
            return redirect('users/edit', $iuserid)
                ->withErrors("User Email is already exists.")
                ->withInput();
        } else {
            MstUser::where('iuserid', '=', $iuserid)->update([
                'mob_user'  => isset($data['mob']) ? 'Y' : 'N',
                'web_user'  => isset($data['web']) ? 'Y' : 'N',
                'pos_user'  => isset($data['pos']) ? 'Y' : 'N',
                'vfname'    => $input['vfname'],
                'vlname'    => $input['vlname'],
                'vaddress1' => $input['vaddress1'],
                'vaddress2' => $input['vaddress2'],
                'vcity'     => $input['vcity'],
                'vstate'    => $input['vstate'],
                'vzip'      => $input['vzip'],
                'vcountry'  => $input['vcountry'],
                'vphone'    => $input['vphone'],
                'vuserid'   => $vuserid,
                'vpassword' => $input['vpassword'],
                'vusertype' => $input['vusertype'],
                'vpasswordchange' => $input['vpasswordchange'],
                'vuserbarcode' => $input['vuserbarcode'],
                'estatus'   =>  $input['estatus'],
                'SID'       => session()->get('sid'),
                'mwpassword' => $encdoe_mwpassword,
                'vemail' => $vemail,
            ]);

            User::where('vemail', '=', $vemail)->update([
                'mob_user'  => isset($data['mob']) ? 'Y' : 'N',
                'web_user'  => isset($data['web']) ? 'Y' : 'N',
                'fname'     => $input['vfname'],
                'lname'     => $input['vlname'],
                'user_role' => $input['vusertype'],
                'iuserid'   => $iuserid,
                'estatus'   => 'Active',
                'SID'       => session()->get('sid'),
                'password' => $encdoe_mwpassword,
                'vemail' => $vemail,
            ]);
        }
        return redirect('users')->with('message', 'User Updated Successfully');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AllUser  $allUser
     * @return \Illuminate\Http\Response
     */
    public function destroy(MstUser $mstUser)
    {
        //
    }
}
