@extends('layouts.master')

@section('title')
    Create a vendor
@stop

@section('main-content')
<div id="content">
    <div class="page-header">
      <div class="container-fluid">
        <ul class="breadcrumb">
          <li><a href="">Vendor Form</a></li>
        </ul>
      </div>
    </div>
    <div class="container-fluid">
        @if ($errors->any())
        <div class="alert alert-danger">
          <ul>
            @foreach ($errors->all() as $error)
              <li>{{$error}}</li>
            @endforeach
          </ul>
          <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        @endif

      <div class="panel panel-default">
        <div class="panel-heading head_title">
          <h3 class="panel-title"><i class="fa fa-pencil"></i>Vendor Form</h3>
        </div>
        <div class="panel-body">
          <div class="row" style="padding-bottom: 9px;float: right;">
            <div class="col-md-12">
              <div class="">
                <button type="submit" id="form-vendor"  title="" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp;&nbsp;Save</button>
                <a href="{{ route('vendors') }}" data-toggle="tooltip" title="" class="btn btn-default cancel_btn_rotate"><i class="fa fa-reply"></i>&nbsp;&nbsp;Cancel</a>
              </div>
            </div>
          </div>
          <div class="clearfix"></div>

          <form action="{{ route('vendors.store') }}" method="post" enctype="multipart/form-data" id="vendorForm" class="form-horizontal">
            @csrf
            <input type="hidden" name="estatus" value="Active">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group required">
                  <label class="col-sm-4 control-label" for="input-vendor-name">Vendor Name</label>
                  <div class="col-sm-8">
                    <input type="text" name="vcompanyname" maxlength="50" value="{{ old('vcompanyname') }}" placeholder="vendor Name" id="input-vendor-name" class="form-control" required />

                    <span id="error"></span>
                  </div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-sm-4 control-label" for="input-vendor-type">Vendor Type</label>
                  <div class="col-sm-8">
                    <select name="vvendortype" id="input-vendor-type" class="form-control" >

                      <option value="vendor" selected="selected">Vendor</option>
                      <option value="other" >Other</option>

                    </select>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="col-sm-4 control-label" for="input-first-name">First Name</label>
                    <div class="col-sm-8">
                      <input type="text" name="vfnmae" maxlength="25" value="{{ old('vfname') }}" placeholder="" id="input-first-name" class="form-control" onkeypress="return (event.charCode > 64 &&
                                          event.charCode < 91) || (event.charCode > 96 && event.charCode < 123)" />
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="col-sm-4 control-label" for="input-last-name">Last Name</label>
                    <div class="col-sm-8">
                      <input type="text" name="vlname" maxlength="25" value="{{ old('vlname') }}" placeholder="" id="input-last-name" class="form-control" onkeypress="return (event.charCode > 64 &&
                                          event.charCode < 91) || (event.charCode > 96 && event.charCode < 123)" />
                    </div>
                  </div>
                </div>
              </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-sm-4 control-label" for="input-vendor-code">Vendor Code</label>
                  <div class="col-sm-8">
                    <input type="text" name="vcode" maxlength="20" value="{{ old('vcode') }}" placeholder="" id="input-last-name" class="form-control" />
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-sm-4 control-label" for="input-address">Address</label>
                  <div class="col-sm-8">
                    <input type="text" name="vaddress1" maxlength="100" value="{{ old('vaddress1') }}" placeholder="" id="input-address" class="form-control" />
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="col-sm-4 control-label" for="input-city">City</label>
                    <div class="col-sm-8">
                      <input type="text" name="vcity" maxlength="20" value="{{ old('vcity') }}" placeholder="" id="input-city" class="form-control" onkeypress="return (event.charCode > 64 &&
                                          event.charCode < 91) || (event.charCode > 96 && event.charCode < 123)" />
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="col-sm-4 control-label" for="input-state">State</label>
                    <div class="col-sm-8">
                      <input type="text" name="vstate" maxlength="20" value="{{ old('vstate') }}" placeholder="" id="input-state" class="form-control" onkeypress="return (event.charCode > 64 &&
                                          event.charCode < 91) || (event.charCode > 96 && event.charCode < 123)" />
                    </div>
                  </div>
                </div>
              </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-sm-4 control-label" for="input-phone">Phone</label>
                  <div class="col-sm-8">
                    <input type="text" name="vphone" maxlength="20"  onkeyup="this.value=this.value.replace(/[^\d]/,'')" value="{{ old('vphone') }}" placeholder="" id="input-phone" class="form-control" />
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-sm-4 control-label" for="input-zip">Zip</label>
                  <div class="col-sm-8">
                    <input type="text" name="vzip" maxlength="10" value="{{ old('vzip') }}" placeholder="" id="input-zip" class="form-control" />
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-sm-4 control-label" for="input-country">Country</label>
                  <div class="col-sm-8">
                    <input type="text" name="vcountry" maxlength="20" value="USA" class="form-control" readonly />
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-sm-4 control-label" for="input-email">Email</label>
                  <div class="col-sm-8">
                    <input type="email" name="vemail" maxlength="100" value="{{ old('vemail') }}" placeholder=""  class="form-control" />
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-sm-4 control-label" for="input-country">PLCB Type</label>
                  <div class="col-sm-8">
                    <select name="plcbtype" class="form-control">
                        <option value="None">None</option>
                        <option value="Schedule A">Schedule A</option>
                        <option value="Schedule B">Schedule B</option>
                        <option value="Schedule C">Schedule C</option>

                    </select>
                  </div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-sm-4 control-label" for="input-country">EDI</label>
                  <div class="col-sm-8">
                    <select name="edi" class="form-control">
                          <option value="1" >Yes</option>
                          <option value="0">No</option>
                    </select>
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
    <script>
        $(document).ready(function () {
            $("#error").removeClass("text-danger");
            $("#form-vendor").click(function(){
                var vcompanyname = $("#input-vendor-name").val();
                if(vcompanyname === ''){
                    $("#error").val() = "Vendor Name is required!";
                }else {
                    $("#vendorForm").submit();
                }
            })
        })
    </script>
@endsection
