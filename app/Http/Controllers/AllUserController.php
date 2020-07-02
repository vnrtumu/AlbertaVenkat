<?php

namespace App\Http\Controllers;

use App\MstUser;
Use App\MstPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


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
        $users = MstUser::orderBy('iuserid','DESC')->get();

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

        return view('User.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        dd($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\AllUser  $allUser
     * @return \Illuminate\Http\Response
     */
    public function show(AllUser $allUser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\AllUser  $allUser
     * @return \Illuminate\Http\Response
     */
    public function edit(AllUser $allUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AllUser  $allUser
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AllUser $allUser)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AllUser  $allUser
     * @return \Illuminate\Http\Response
     */
    public function destroy(AllUser $allUser)
    {
        //
    }
}
