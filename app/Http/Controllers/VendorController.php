<?php

namespace App\Http\Controllers;

use App\Model\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vendors = Vendor::orderBy('isupplierid', 'DESC')->paginate(20);
        return view('vendors.index', compact('vendors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('vendors.create');
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
        $data = Vendor::where('vcompanyname', '=', $input['vcompanyname'])->get();

        if(count($data) > 0){

            return redirect('vendors/create')
                        ->withErrors("Vendor id is already exists.")
                        ->withInput();

        }else {
           $vendor =  Vendor::create([
                        'vcompanyname' => $input['vcompanyname'],
                        'vvendortype' => $input['vvendortype'],
                        'vfnmae' => $input['vfnmae'],
                        'vlname' => $input['vlname'],
                        'vcode' => $input['vcode'],
                        'vaddress1' => $input['vaddress1'],
                        'vcity' => $input['vcity'],
                        'vstate' => $input['vstate'],
                        'vphone' => $input['vphone'],
                        'vzip' => $input['vzip'],
                        'vcountry' => $input['vcountry'],
                        'vemail' => $input['vemail'],
                        'plcbtype' => $input['plcbtype'],
                        'edi' => $input['edi'],
                        'estatus' => $input['estatus'],
                        'SID' => session()->get('sid'),
                    ]);


            $isuppliercode = $vendor->id;
            // dd( $isuppliercode);
            Vendor::where('isupplierid', '=', $isuppliercode  )->update(['vsuppliercode' => $isuppliercode]);

            return redirect('vendors')->with('message', 'Vendor created successfully');
        }

    }

    public function edit(Vendor $vendor, $isupplierid)
    {
        $vendors = Vendor::where('isupplierid', '=', $isupplierid)->get();
        $vendor = $vendors[0];
        return view('vendors.edit', compact('vendor'));
    }


    public function update(Request $request, Vendor $vendor, $isupplierid)
    {
        $input = $request->all();

        $vendor =  Vendor::where('isupplierid', '=', $isupplierid )->update([
                                'vcompanyname' => $input['vcompanyname'],
                                'vvendortype' => $input['vvendortype'],
                                'vfnmae' => $input['vfnmae'],
                                'vlname' => $input['vlname'],
                                'vcode' => $input['vcode'],
                                'vaddress1' => $input['vaddress1'],
                                'vcity' => $input['vcity'],
                                'vstate' => $input['vstate'],
                                'vphone' => $input['vphone'],
                                'vzip' => $input['vzip'],
                                'vcountry' => $input['vcountry'],
                                'vemail' => $input['vemail'],
                                'plcbtype' => $input['plcbtype'],
                                'edi' => $input['edi'],
                                'estatus' => $input['estatus'],
                                'SID' => session()->get('sid'),
                            ]);
        return redirect('vendors')->with('message', 'Vendor Updated Successfully');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function remove(Request $request)
    {
        $delId = $request->all();
        for($i = 0; $i < count($delId['selected']); $i++ ){
            Vendor::where('isupplierid', '=', $delId['selected'][$i] )->delete();
        }
        return redirect('vendors')->with('message', 'Vendor Deleted Successfully');
    }
}
