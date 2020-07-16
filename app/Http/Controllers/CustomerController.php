<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\MstCustomer;
use Laravel\Ui\Presets\React;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = MstCustomer::orderBy('icustomerid', 'DESC')->paginate(20);
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $input = $request->all();

        $duplicateCust = MstCustomer::where('vphone', '=', $input['vphone'])->get();

        if(count($duplicateCust) > 0){
            return redirect('customers/create')
                        ->withErrors("Vendor id is already exists.")
                        ->withInput();
        }else {
            MstCustomer::create([
                'vcustomername' => $input['vcustomername'],
                'vaccountnumber' => $input['vaccountnumber'],
                'vfname' => $input['vfname'],
                'vlname' => $input['vlname'],
                'vaddress1' => $input['vaddress1'],
                'vcity' => $input['vcity'],
                'vstate' => $input['vstate'],
                'vphone' => $input['vphone'],
                'vzip' => $input['vzip'],
                'vcountry' => $input['vcountry'],
                'vemail' => $input['vemail'],
                'pricelevel' => $input['pricelevel'],
                'vtaxable' => $input['vtaxable'],
                'estatus' => $input['estatus'],
                'debitlimit' => $input['debitlimit'],
                'creditday' => $input['creditday'],
                'note' => $input['note'],
                'SID' => session()->get('sid')
            ]);

            return redirect('customers')->with('message', 'customers created Successfully');
        }
    }

    public function edit(Request $request, $icustomerid)
    {
        $customers = MstCustomer::where('icustomerid', '=', $icustomerid)->get();
        $customer = $customers[0];
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, MstCustomer $mstCustomer, $icustomerid)
    {
        $input = $request->all();

        MstCustomer::where('icustomerid', '=', $icustomerid)->update([
            'vcustomername' => $input['vcustomername'],
            'vaccountnumber' => $input['vaccountnumber'],
            'vfname' => $input['vfname'],
            'vlname' => $input['vlname'],
            'vaddress1' => $input['vaddress1'],
            'vcity' => $input['vcity'],
            'vstate' => $input['vstate'],
            'vphone' => $input['vphone'],
            'vzip' => $input['vzip'],
            'vcountry' => $input['vcountry'],
            'vemail' => $input['vemail'],
            'pricelevel' => $input['pricelevel'],
            'vtaxable' => $input['vtaxable'],
            'estatus' => $input['estatus'],
            'debitlimit' => $input['debitlimit'],
            'creditday' => $input['creditday'],
            'note' => $input['note'],
            'SID' => session()->get('sid')
        ]);
        return redirect('customers')->with('message', 'customers updated Successfully');

    }

    public function remove(Request $request)
    {
        $delId = $request->all();
        for($i = 0; $i < count($delId['selected']); $i++ ){
            MstCustomer::where('icustomerid', '=', $delId['selected'][$i] )->delete();
        }
        return redirect('customers')->with('message', 'customers Deleted Successfully');
    }
}
