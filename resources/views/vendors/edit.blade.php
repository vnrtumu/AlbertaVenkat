@extends('layouts.master')

@section('title')
    Edit this vendor
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
                <button type="submit" id="form-vendor" data-toggle="tooltip" title="" class="btn btn-primary save_btn_rotate"><i class="fa fa-save"></i>&nbsp;&nbsp;Save</button>
                <a href="{{ route('vendors') }}" data-toggle="tooltip" title="" class="btn btn-default cancel_btn_rotate"><i class="fa fa-reply"></i>&nbsp;&nbsp;Cancel</a>
              </div>
            </div>
          </div>
          <div class="clearfix"></div>

          <form action="{{ route('vendors.update', $vendor->isupplierid) }}" method="post" enctype="multipart/form-data" id="form-vendor" class="form-horizontal">
            @csrf
            @method('PATCH')
            <input type="hidden" name="estatus" value="Active">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group required">
                  <label class="col-sm-4 control-label" for="input-vendor-name">Vendor Name</label>
                  <div class="col-sm-8">
                    <input type="text" name="vcompanyname" maxlength="50" value="{{ $vendor->vcompanyname }}" placeholder="vendor Name" id="input-vendor-name" class="form-control" />
                  </div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-sm-4 control-label" for="input-vendor-type">Vendor Type</label>
                  <div class="col-sm-8">
                    <select name="vvendortype" id="input-vendor-type" class="form-control">

                      <option value="vendor" {{ $vendor->vvendortype == "vendor" ? 'selected' : ''}}>Vendor</option>
                      <option value="other" {{ $vendor->vvendortype == "other" ? 'selected' : ''}} >Other</option>

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
                    <input type="text" name="vfnmae" maxlength="25" value="{{ $vendor->vfname }}" placeholder="" id="input-first-name" class="form-control" />
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-sm-4 control-label" for="input-last-name">Last Name</label>
                  <div class="col-sm-8">
                    <input type="text" name="vlname" maxlength="25" value="{{ $vendor->vlname }}" placeholder="" id="input-last-name" class="form-control" />
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-sm-4 control-label" for="input-vendor-code">Vendor Code</label>
                  <div class="col-sm-8">
                    <input type="text" name="vcode" maxlength="20" value="{{ $vendor->vcode }}" placeholder="" id="input-last-name" class="form-control" />
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-sm-4 control-label" for="input-address">Address</label>
                  <div class="col-sm-8">
                    <input type="text" name="vaddress1" maxlength="100" value="{{ $vendor->vaddress1 }}" placeholder="" id="input-address" class="form-control" />
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-sm-4 control-label" for="input-city">City</label>
                  <div class="col-sm-8">
                    <input type="text" name="vcity" maxlength="20" value="{{ $vendor->vcity }}" placeholder="" id="input-city" class="form-control" />
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-sm-4 control-label" for="input-state">State</label>
                  <div class="col-sm-8">
                    <input type="text" name="vstate" maxlength="20" value="{{ $vendor->vstate }}" placeholder="" id="input-state" class="form-control" />
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-sm-4 control-label" for="input-phone">Phone</label>
                  <div class="col-sm-8">
                    <input type="text" name="vphone" maxlength="20" value="{{ $vendor->vphone }}" placeholder="" id="input-phone" class="form-control" />
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-sm-4 control-label" for="input-zip">Zip</label>
                  <div class="col-sm-8">
                    <input type="text" name="vzip" maxlength="10" value="{{ $vendor->vzip }}" placeholder="" id="input-zip" class="form-control" />
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
                    <input type="email" name="vemail" maxlength="100" value="{{ $vendor->vemail }}" placeholder=""  class="form-control" />
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
                        <option value="None" {{ $vendor->plcbtype == "None" ? 'selected' : '' }}>None</option>
                        <option value="Schedule A" {{ $vendor->plcbtype == "Schedule A" ? 'selected' : '' }}>Schedule A</option>
                        <option value="Schedule B" {{ $vendor->plcbtype == "Schedule B" ? 'selected' : '' }}>Schedule B</option>
                        <option value="Schedule C" {{ $vendor->plcbtype == "Schedule C" ? 'selected' : '' }}>Schedule C</option>

                    </select>
                  </div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-sm-4 control-label" for="input-country">EDI</label>
                  <div class="col-sm-8">
                    <select name="edi" class="form-control">
                          <option value="1" {{ $vendor->edi == 1 ? 'selected' : '' }} >Yes</option>
                          <option value="0" {{ $vendor->edi == 0 ? 'selected' : '' }}>No</option>
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
