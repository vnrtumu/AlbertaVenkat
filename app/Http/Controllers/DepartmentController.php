<?php

namespace App\Http\Controllers;

use App\MstCategory;
use Illuminate\Http\Request;
use App\MstDepartment;
use Illuminate\Support\Facades\DB;
use LengthAwarePaginator;


class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $departments = DB::connection('mysql_dynamic')->select('SELECT * FROM mst_department d left join (
                                SELECT dept_code, count(*) as cat_count from mst_category group by dept_code
                                ) c on d.idepartmentid=c.dept_code ORDER BY d.vdepartmentname');


        return view('department.index', compact('departments'));
    }

    // public function arrayPaginator($array, $request)
    // {
    //     $page = $request->get('page', 1);
    //     $perPage = 10;
    //     $offset = ($page * $perPage) - $perPage;

    //     return new LengthAwarePaginator(array_slice($array, $offset, $perPage, true), count($array), $perPage, $page,
    //         ['path' => $request->url(), 'query' => $request->query()]);
    // }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        if($input['start_hour'] != '' && $input['start_minute'] != ''){
            $starttime = $input['start_hour'].':'.$input['start_minute'].':00';
        }else{
            $starttime = NULL;
        }

        if($input['end_hour'] != '' && $input['end_minute'] != ''){
            $endtime = $input['end_hour'].':'.$input['end_minute'].':00';
        }else{
            $endtime = NULL;
        }
        MstDepartment::create([
            'vdepartmentname' => $input['vdepartmentname'],
            'vdescription' => $input['vdescription'],
            'estatus' => 'Active',
            'isequence' => $input['isequence'],
            'SID'=> session()->get('sid'),
            'starttime' => $starttime,
            'endtime' => $endtime
        ]);
        return redirect('departments')->with('message', 'Department Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
