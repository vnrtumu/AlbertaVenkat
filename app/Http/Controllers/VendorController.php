<?php

namespace App\Http\Controllers;

use App\Model\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Laravel\Ui\Presets\React;

class VendorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $vendors = Vendor::orderBy('isupplierid', 'DESC')->paginate(20);
        $data['edit_list'] = url('vendors/edit_list');
        return view('vendors.index', compact('vendors', 'data'));
    }

    public function edit_list(Request $request)
    {
        $input = $request->all();
        $vendor = $input['vendor'];
        $i = 0;
        foreach($vendor as $ven){
            $vendor =  Vendor::where('isupplierid', '=', $ven['isupplierid'])->update([
                'vcompanyname'  =>$ven['vcompanyname'],
                'vcode'         =>$ven['vcode'],
                'vphone'        =>$ven['vphone'],
                'vemail'        =>$ven['vemail'],
                'vsuppliercode' =>$ven['vemail'],
            ]);
            $i++;
        }
        $data['edit_list'] = url('vendors/edit_list');
        $vendors = Vendor::orderBy('isupplierid', 'DESC')->paginate(20);
        return view('vendors.index', compact('vendors', 'data'));
    }


    public function search(Request $request)
    {
        $input = $request->all();
        $vendors = Vendor::where('vcompanyname', 'LIKE', '%' . $input['automplete-product'] . '%')->orderBy('isupplierid', 'DESC')->paginate(20);
        return view('vendors.index', compact('vendors'));
    }

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

        if (count($data) > 0) {

            return redirect('vendors/create')
                ->withErrors("Vendor id is already exists.")
                ->withInput();
        } else {
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
            Vendor::where('isupplierid', '=', $isuppliercode)->update(['vsuppliercode' => $isuppliercode]);

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

        $vendor =  Vendor::where('isupplierid', '=', $isupplierid)->update([
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
        for ($i = 0; $i < count($delId['selected']); $i++) {
            Vendor::where('isupplierid', '=', $delId['selected'][$i])->delete();
        }
        return redirect('vendors')->with('message', 'Vendor Deleted Successfully');
    }
}
