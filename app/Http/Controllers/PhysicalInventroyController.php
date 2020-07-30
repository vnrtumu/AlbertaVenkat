<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\PhysicalInventory;
use App\Model\Category;
use App\Model\Department;
use App\Model\Vendor;
use App\Model\Subcategory;
use Illuminate\Support\Facades\DB;
use App\Model\WebAdminSetting;


class PhysicalInventroyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $physicalInventrorylists = PhysicalInventory::orderBy('ipiid', 'ASC')->paginate(25);
        return view('physicalInventroy.index', compact('physicalInventrorylists'));
    }



    public function create()
    {
        $items = DB::connection('mysql_dynamic')->select('SELECT mst_item.vitemname, mst_item.vbarcode, mst_item.dunitprice, mst_item.dcostprice, mst_department.vdepartmentname, mst_category.vcategoryname, mst_supplier.vcompanyname,mst_subcategory.subcat_name, mst_item.iqtyonhand FROM mst_item join mst_department on mst_item.vdepcode = mst_department.idepartmentid join mst_category on mst_item.vcategorycode = mst_category.icategoryid join mst_supplier on mst_item.vsuppliercode = mst_supplier.isupplierid join mst_subcategory on mst_item.subcat_id = mst_subcategory.subcat_id');

        $departments = Department::orderBy('vdepartmentname', 'ASC')->get();
        $categories = Category::orderBy('vcategoryname', 'ASC')->get();
        $subcategories = Subcategory::orderBy('subcat_name', 'ASC')->get();
        $vendors = Vendor::orderBy('vcompanyname', 'ASC')->get();

        // dd($vendors);

        return view('physicalInventroy.create', compact('items', 'departments', 'categories', 'subcategories', 'vendors' ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
