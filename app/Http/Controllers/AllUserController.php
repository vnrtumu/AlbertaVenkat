<?php

namespace App\Http\Controllers;

use App\Model\UserDynamic;
use App\User;
use App\Model\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;
use App\Model\Userpermission;
use Illuminate\Support\Facades\Auth;
use App\Model\Permissiongroup;
use App\Model\Userpermissiongroup;
use Illuminate\Auth\Access\Gate;


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
        $users = UserDynamic::orderBy('iuserid', 'DESC')->paginate(20);
        return view('User.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::all();
        $mstPermissiongroup = Permissiongroup::all();
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
        // dd($input);
        // $devices = $input['device'];
        // $data = [];

        if (isset($input['vuserid'])) {
            $duplicateUserid = UserDynamic::where('vuserid', '=', $input['vuserid'])->get();
        }
        if (isset($input['vemail'])) {
            $duplicateEmail = User::where([['vemail', '=', $input['vemail']], ['estatus', '=',  'Active']])->get();
            $duplicateMstuser = UserDynamic::where('vemail', '=', $input['vemail'])->get();
        }

        if (isset($duplicateUserid) && count($duplicateUserid) > 0) {
            return redirect('users/create')
                ->withErrors("Pos User id is already exists.")
                ->withInput($input);
        } elseif (isset($duplicateEmail) && count($duplicateEmail) > 0 && count($duplicateMstuser) > 0) {
            return redirect('users/create')
                ->withErrors("User Email is already exists.")
                ->withInput($input);

        } else {
            //checkwether pos user or (Mobile & Web) user
            if (isset($input['pos']) == 'Y' && isset($input['web']) == '' && isset($input['mob']) == '') {
                $encdoe_password = $this->encodePassword($input['vpassword']);
                $mst_user = UserDynamic::create([
                    'mob_user'  => isset($input['mob']) ? 'Y' : 'N',
                    'web_user'  => isset($input['web']) ? 'Y' : 'N',
                    'pos_user'  => isset($input['pos']) ? 'Y' : 'N',
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
                    $mst_user = UserDynamic::create([
                        'mob_user'  => isset($input['mob']) ? 'Y' : 'N',
                        'web_user'  => isset($input['web']) ? 'Y' : 'N',
                        'pos_user'  => isset($input['pos']) ? 'Y' : 'N',
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
                        'mob_user'  => isset($input['mob']) ? 'Y' : 'N',
                        'web_user'  => isset($input['web']) ? 'Y' : 'N',
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
                    $mst_user = UserDynamic::create([
                        'mob_user'  => isset($input['mob']) ? 'Y' : 'N',
                        'web_user'  => isset($input['web']) ? 'Y' : 'N',
                        'pos_user'  => isset($input['pos']) ? 'Y' : 'N',
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
                        'mob_user'  => isset($input['mob']) ? 'Y' : 'N',
                        'web_user'  => isset($input['web']) ? 'Y' : 'N',
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
            Userpermission::create([
                'status'        => 'Active',
                'created_id'    => Auth::user()->iuserid,
                'permission_id' => $permission,
                'updated_id'    => Auth::user()->iuserid,
                'userid'        => $mst_user->id,
                'SID'           => session()->get('sid')
            ]);
        }
        $group = Permissiongroup::where('vgroupname', '=', $input['vusertype'])->get();
        if (count($group) > 0) {
            $ipermissiongroupid = $group[0]->ipermissiongroupid;
            $que = Userpermissiongroup::where('iuserid', '=', Auth::user()->iuserid)->get();
            if (count($que) > 0) {
                Userpermissiongroup::where('iuserid', '=', Auth::user()->iuserid)->update(['ipermissiongroupid' => $ipermissiongroupid]);
            } else {
                Userpermissiongroup::create(['iuserid' => Auth::user()->iuserid, 'ipermissiongroupid' => $ipermissiongroupid, 'SID' => session()->get('sid')]);
            }
        }
        return redirect('users')->with('message', 'User Created Successfully');
    }



    public function edit(UserDynamic $userDynamic, $iuserid)
    {
        $permissions = Permission::all();
        $mstPermissiongroup = Permissiongroup::all();
        $user = UserDynamic::where('iuserid', '=', $iuserid)->get();
        $users = $user[0];
        $checkedPermission = Userpermission::where('userid', '=', $iuserid)->get()->toArray();
        $dataPerCheck = array();
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
    public function update(Request $request, UserDynamic $userDynamic, $iuserid)
    {
        $input = $request->all();

        $que = Userpermission::where('userid', '=', $iuserid)->get()->toArray();
        for ($i = 0; $i < count($que); $i++) {
            $list_of_permissions[] = $que[$i]['permission_id'];
        }

        foreach ($input['permission'] as $permission) {
            if (in_array($permission, $list_of_permissions)) {
                $get_id = Userpermission::where([['permission_id', '=', $permission], ['userid', '=', $iuserid]])->get();
                $mst_perm_id = $get_id[0]->Id;
                Userpermission::where('Id', '=', $mst_perm_id)->update(['permission_id' => $permission, 'status' => 'Active']);
            } else {
                Userpermission::create([
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
            $get_id = Userpermission::where([['permission_id', '=', $permission], ['userid', '=', $iuserid]])->get();

            $mst_perm_id = $get_id[0]->Id;
            Userpermission::where('Id', '=', $mst_perm_id)->update(['status' => 'Inactive']);
        }
        $group = Permissiongroup::where('vgroupname', '=', $input['vusertype'])->get();
        if (count($group) > 0) {
            $ipermissiongroupid = $group[0]->ipermissiongroupid;
            $que = Userpermissiongroup::where('iuserid', '=', Auth::user()->iuserid)->get();
            if (count($que) > 0) {
                Userpermissiongroup::where('iuserid', '=', Auth::user()->iuserid)->update(['ipermissiongroupid' => $ipermissiongroupid]);
            } else {
                Userpermissiongroup::create(['iuserid' => Auth::user()->iuserid, 'ipermissiongroupid' => $ipermissiongroupid, 'SID' => session()->get('sid')]);
            }
        }
        $mst_user = UserDynamic::where('iuserid', '=', $iuserid)->first();
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
        $user_id = $mst_user->iuserid;
        // check if the entered email is different from the one in db
        if (isset($input['vemail'])  && $mst_user->vemail != $input['vemail']) {
            $vemail = $input['vemail'];
            if (isset($input['vemail'])) {
                $duplicateEmail = User::where([['vemail', '=', $input['vemail']], ['estatus', '=',  'Active']])->get();
                $duplicateMstuser = UserDynamic::where('vemail', '=', $input['vemail'])->get();
            }
        } else {

            $vemail = $mst_user->vemail;
        }
        if (isset($input['vuserid']) && $mst_user->vuserid != $input['vuserid']) {
            $vuserid = $input['vuserid'];
            if (isset($input['vuserid'])) {
                $duplicateUserid = UserDynamic::where('vuserid', '=', $input['vuserid'])->get();
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

            // if (isset($input['pos']) == 'Y' && isset($input['web']) == '' && isset($input['mob']) == '') {
                UserDynamic::where('iuserid', '=', $iuserid)->update([
                    'mob_user'  => isset($input['mob']) ? 'Y' : 'N',
                    'web_user'  => isset($input['web']) ? 'Y' : 'N',
                    'pos_user'  => isset($input['pos']) ? 'Y' : 'N',
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

               User::where('iuserid', '=', $user_id)->update([
                    'mob_user'  => isset($input['mob']) ? 'Y' : 'N',
                    'web_user'  => isset($input['web']) ? 'Y' : 'N',
                    'fname'     => $input['vfname'],
                    'lname'     => $input['vlname'],
                    'user_role' => $input['vusertype'],
                    'iuserid'   => $iuserid,
                    'estatus'   => 'Active',
                    'SID'       => session()->get('sid'),
                    'password' => $encdoe_mwpassword,
                    'vemail' => $vemail,
                ]);


            // } else {
            //     UserDynamic::where('iuserid', '=', $iuserid)->update([
            //         'mob_user'  => isset($input['mob']) ? 'Y' : 'N',
            //         'web_user'  => isset($input['web']) ? 'Y' : 'N',
            //         'pos_user'  => isset($input['pos']) ? 'Y' : 'N',
            //         'vfname'    => $input['vfname'],
            //         'vlname'    => $input['vlname'],
            //         'vaddress1' => $input['vaddress1'],
            //         'vaddress2' => $input['vaddress2'],
            //         'vcity'     => $input['vcity'],
            //         'vstate'    => $input['vstate'],
            //         'vzip'      => $input['vzip'],
            //         'vcountry'  => $input['vcountry'],
            //         'vphone'    => $input['vphone'],
            //         'vuserid'   => $vuserid,
            //         'vpassword' => $input['vpassword'],
            //         'vusertype' => $input['vusertype'],
            //         'vpasswordchange' => $input['vpasswordchange'],
            //         'vuserbarcode' => $input['vuserbarcode'],
            //         'estatus'   =>  $input['estatus'],
            //         'SID'       => session()->get('sid'),
            //         'mwpassword' => $encdoe_mwpassword,
            //         'vemail' => $vemail,
            //     ]);

            //     User::where('vemail', '=', $vemail)->update([
            //         'mob_user'  => isset($input['mob']) ? 'Y' : 'N',
            //         'web_user'  => isset($input['web']) ? 'Y' : 'N',
            //         'fname'     => $input['vfname'],
            //         'lname'     => $input['vlname'],
            //         'user_role' => $input['vusertype'],
            //         'iuserid'   => $iuserid,
            //         'estatus'   => 'Active',
            //         'SID'       => session()->get('sid'),
            //         'password' => $encdoe_mwpassword,
            //         'vemail' => $vemail,
            //     ]);
            // }

        }
        return redirect('users')->with('message', 'User Updated Successfully');
    }



    public function remove(Request $request)
    {
        $delId = $request->all();
        for($i = 0; $i < count($delId['selected']); $i++ ){
            UserDynamic::where('iuserid', '=', $delId['selected'][$i] )->delete();
            User::where('iuserid', '=', $delId['selected'][$i])->update(['estatus' => 'Inactive']);
        }
        return redirect('users')->with('message', 'User Deleted Successfully');
    }
}
