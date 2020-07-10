@extends('layouts.master')

@section('title')
    Customers
@stop

@section('main-content')
<div id="content">
    <div class="page-header">
      <div class="container-fluid">
        <ul class="breadcrumb">
          <li><a href="">Create Customer</a></li>

        </ul>
      </div>
    </div>
    <div class="container-fluid">

      <div class="panel panel-default">
        <div class="panel-heading head_title">
          <h3 class="panel-title"><i class="fa fa-pencil"></i> Add New Customer</h3>

        </div>
        <div class="panel-body">

          <div class="row" style="padding-bottom: 9px;float: right;">
            <div class="col-md-12">
              <div class="">
                <button type="submit" form="form-customer" data-toggle="tooltip"  class="btn btn-primary save_btn_rotate"><i class="fa fa-save"></i>&nbsp;&nbsp;Save</button>
                <a href="{{ route('customers') }}" data-toggle="tooltip" class="btn btn-default cancel_btn_rotate"><i class="fa fa-reply"></i>&nbsp;&nbsp;Cancel</a>
              </div>
            </div>
          </div>
          <div class="clearfix"></div>

          <form action="{{ route('customers.update', $customer->icustomerid) }}" method="post" enctype="multipart/form-data" id="form-customer" class="form-horizontal">
            @csrf
            @method('PATCH')
            <input type="hidden" name="estatus" value="Active">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group required">
                  <label class="col-sm-4 control-label" for="input-customer">Customer</label>
                  <div class="col-sm-8">
                    <input type="text" name="vcustomername" maxlength="50" value="{{ $customer->vcustomername }}" placeholder="" id="input-customer" class="form-control" />
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-sm-4 control-label" for="input-account-number">Account Number</label>
                  <div class="col-sm-8">
                    <input type="text" name="vaccountnumber" maxlength="50" value="{{ $customer->vaccountnumber }}" placeholder="" id="input-account-number" class="form-control" readonly/>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-sm-4 control-label" for="input-first-name">First Name</label>
                  <div class="col-sm-8">
                    <input type="text" name="vfname" maxlength="25" value="{{ $customer->vfname }}" placeholder="" id="input-first-name" class="form-control" />
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-sm-4 control-label" for="input-last-name">Last Name</label>
                  <div class="col-sm-8">
                    <input type="text" name="vlname" maxlength="25" value="{{ $customer->vlname }}" placeholder="" id="input-last-name" class="form-control" />
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-sm-4 control-label" for="input-address">Address</label>
                  <div class="col-sm-8">
                    <input type="text" name="vaddress1" maxlength="100" value="{{ $customer->vaddress1 }}" placeholder="" id="input-address" class="form-control" />
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-sm-4 control-label" for="input-city">City</label>
                  <div class="col-sm-8">
                    <input type="text" name="vcity" maxlength="20" value="{{ $customer->vcity }}" placeholder="" id="input-city" class="form-control" />
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-sm-4 control-label" for="input-state">State</label>
                  <div class="col-sm-8">
                    <input type="text" name="vstate" maxlength="20" value="{{ $customer->vstate }}" placeholder="" id="input-state" class="form-control" />
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-sm-4 control-label" for="input-zip">Zip</label>
                  <div class="col-sm-8">
                    <input type="text" name="vzip" maxlength="10" value="{{ $customer->vzip }}" placeholder="" id="input-zip" class="form-control" />
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-sm-4 control-label" for="input-country">Country</label>
                  <div class="col-sm-8">
                    <input type="text" maxlength="20" name="vcountry" value="USA" class="form-control" readonly />
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group required">
                  <label class="col-sm-4 control-label" for="input-phone">Phone</label>
                  <div class="col-sm-8">
                    <input type="text" name="vphone" maxlength="20" value="{{ $customer->vphone }}" placeholder="" id="input-phone" class="form-control" />
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-sm-4 control-label" for="input-email">Email</label>
                  <div class="col-sm-8">
                    <input type="email" name="vemail" maxlength="100" value="{{ $customer->vemail }}" placeholder="" id="input-email" class="form-control" />


                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-sm-4 control-label" for="input-email">Price Level</label>
                  <div class="col-sm-8">
                    <select name="pricelevel" class="form-control">
                        <option value="0" {{ $customer->pricelevel == "0" ? 'selected' : '' }}>0</option>
                        <option value="2" {{ $customer->pricelevel == "2" ? 'selected' : '' }}>Level 2</option>
                        <option value="3" {{ $customer->pricelevel == "3" ? 'selected' : '' }}>Level 3</option>
                        <option value="4" {{ $customer->pricelevel == "4" ? 'selected' : '' }}>Level 4</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-sm-4 control-label" for="input-state">Debit Limit</label>
                  <div class="col-sm-8">
                    <input type="text" name="debitlimit" value="{{ $customer->debitlimit }}" placeholder="" id="input-debitlimit" class="form-control" />
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-sm-4 control-label" for="input-zip">Credit Day</label>
                  <div class="col-sm-8">
                    <input type="text" name="creditday" maxlength="11" value="{{ $customer->creditday }}" placeholder="" id="input-creditday" class="form-control" />
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                  <div class="form-group">
                          <label class="col-sm-4 control-label" for="input-zip">Status</label>
                          <div class="col-sm-8">
                              <select name="estatus" class="form-control">
                                  <option value="Active" {{ $customer->estatus == 'Active' ? 'selected' : '' }} >Active</option>
                                  <option value="Deactive" {{ $customer->estatus == 'Deactive' ? 'selected' : '' }}>Deactive</option>
                              </select>
                          </div>
                      </div>

              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-sm-4 control-label" for="input-zip">Note</label>
                  <div class="col-sm-8">
                    <textarea name="note" class="form-control">{{ $customer->note }}</textarea>
                  </div>
                </div>
              </div>
            </div>

              <div class="row">
                  <div class="col-md-6">
                      <div class="form-group">
                          <label class="col-sm-2 control-label" for="input-taxable">&nbsp;</label>
                          <div class="col-sm-10">
                              @if ( $customer->vtaxable == 'Yes')
                                    <input type='radio' name='vtaxable' value='Yes' checked="checked" >&nbsp;&nbsp; Taxable &nbsp;&nbsp;
                                    <input type='radio' name='vtaxable' value='No' >&nbsp;&nbsp;Non Taxable
                              @else
                                    <input type='radio' name='vtaxable' value='Yes'  >&nbsp;&nbsp; Taxable &nbsp;&nbsp;
                                    <input type='radio' name='vtaxable' value='No' checked="checked" >&nbsp;&nbsp;Non Taxable
                              @endif
                          </div>
                      </div>
                  </div>
              </div>

          </form>
        </div>
      </div>
    </div>

  </div>
@endsection

@section('scripts')
<script type="text/javascript">
    $(document).on('change', 'input[name="vcustomername"]', function(event) {
      event.preventDefault();
      var name = $(this).val().toUpperCase();
      var new_name = name.substring(0, 3);
      var number = Math.floor(Math.random()*90000) + 10000;

      var ac_number = new_name+''+number;

      $('input[name="vaccountnumber"]').val(ac_number);
    });
  </script>
@endsection
