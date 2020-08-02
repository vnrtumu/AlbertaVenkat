@extends('layouts.master')

@section('title')
    Customers
@stop

@section('main-content')
<div id="content">
    <div class="page-header">
      <div class="container-fluid">
        <ul class="breadcrumb">
          <li><a href="">Customers</a></li>
        </ul>
      </div>
    </div>
    <div class="container-fluid">
        @if (session()->has('message'))
            <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i>
                {{session()->get('message')}}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif
      <div class="panel panel-default">
        <div class="panel-heading head_title">
          <h3 class="panel-title"><i class="fa fa-list"></i> Customers</h3>
        </div>
        <div class="panel-body">

            <div class="row" style="padding-bottom: 9px;float: right;">
                <div class="col-md-12">
                  <div class="">
                        <a href="{{ route('customers.create') }}" title="" class="btn btn-primary add_new_btn_rotate"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add New</a>
                        <button type="button" class="btn btn-danger" id="customer_delete" title="Delete" style="border-radius: 0px;"><i class="fa fa-trash"></i>&nbsp;&nbsp;Delete</button>
                  </div>
                </div>
              </div>

              <form action="{{ route('customers') }}" method="post" id="form_vendor_search">
                @csrf
                <input type="hidden" name="searchbox" id="vendor_name">
                <div class="row">
                    <div class="col-md-11">
                        <input type="text" autocomplete="off" name="automplete-product" class="form-control" placeholder="Search Customer.." id="automplete-product">
                    </div>
                    <div class="col-md-1">
                        <input type="submit" class="btn btn-info add_new_btn_rotate" value="Search" style="">
                    </div>
                </div>
              </form>


              <br>

          <form action="{{ route('customers.remove') }}" method="post" enctype="multipart/form-data" id="form-customer">
            @csrf
            <input type="hidden" name="MenuId" value="$filter_menuid;"/>
            <div class="table-responsive">
              <table id="customer" class="table table-bordered table-hover">

                <thead>
                  <tr>
                    <th style="width: 1px; color:black;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></th>
                    <th class="text-left">Customer</th>
                    <th class="text-left">First Name</th>
                    <th class="text-left">Last Name</th>
                    <th class="text-right">Phone</th>
                    <th class="text-left">Account Number</th>
                    <th class="text-left">Status</th>
                    <th class="text-left">Action</th>
                  </tr>
                </thead>
                <tbody>
                @foreach($customers as $customer )
                    <tr id="customer-row">
                        <td data-order="" class="text-center">
                            <input type="checkbox" name="selected[]" id=""  value="{{ $customer->icustomerid }}" />
                        </td>

                        <td class="text-left">
                        <span>{{$customer->vcustomername}}</span>
                        </td>

                        <td class="text-left">
                        <span>{{$customer->vfname }}</span>
                        </td>

                        <td class="text-left">
                        <span>{{$customer->vlname}} </span>
                        </td>

                        <td class="text-right">
                        <span>{{$customer->vphone}}</span>
                        </td>

                        <td class="text-left">
                        <span>{{$customer->vaccountnumber}}</span>
                        </td>

                        <td class="text-left">
                        <span>{{$customer->estatus}}</span>
                        </td>

                        <td class="text-left">
                        <a href="{{ route('customers.edit', $customer->icustomerid) }}" data-toggle="tooltip" title="Edit" class="btn btn-sm btn-info edit_btn_rotate" ><i class="fa fa-pencil">&nbsp;&nbsp;Edit</i>
                        </a>
                        </td>
                    </tr>
                @endforeach




                </tbody>
              </table>
            </div>
          </form>
          <div class="row">
            <div class="col-sm-6 text-left">{{ $customers->links() }}</div>
            <div class="col-sm-6 text-right"></div>
          </div>
        </div>
      </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $('#customer_delete').click(function(){
      $('#form-customer').submit();
    })
</script>
@endsection
