@extends('layouts.master')

@section('main-content')
{{-- {{ dd($users) }} --}}
<div id="content">
    <div class="page-header">
      <div class="container-fluid">
        {{-- <h1>Users List</h1> --}}
        <ul class="breadcrumb">

          <li><a href="">users</a></li>

        </ul>
      </div>
    </div>
    <div class="container-fluid">

      {{-- <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>


      <div class="alert alert-success"><i class="fa fa-check-circle"></i>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>
       --}}
      <div class="panel panel-default">
        <div class="panel-heading head_title">
          <h3 class="panel-title"> Users<i class="fa fa-list"></i> </h3>

        </div>
        <div class="panel-body">
          <div class="row" style="padding-bottom: 9px;float: right;">
            <div class="col-md-12">
              <div class="">
                <a href="{{ route('users.create') }}" class="btn btn-primary add_new_btn_rotate"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add New</a>
                <button type="button" class="btn btn-danger" id="user_delete" onclick="myFunction()" title="Delete" style="border-radius: 0px;"><i class="fa fa-trash"></i>&nbsp;&nbsp;Delete</button>
              </div>
            </div>
          </div>

        <form action="" method="post" id="form_user_search">
          <div class="row">
              <input type="hidden" name="searchbox" id="iuserid">
              <div class="col-md-12">
                  <input name="automplete-product" type="text" class="form-control" placeholder="Search User..." id="automplete-product" required>
              </div>
          </div>
        </form>
         <br>

          <form action="" method="post" enctype="multipart/form-data" id="form-users">
            <input type="hidden" name="MenuId" value=""/>
            <div class="table-responsive">
              <table id="users" class="table table-bordered table-hover" style="">

                <thead>
                  <tr>
                    <th style="width: 1px;color:black;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></th>
                    <th class="text-left">First Name</th>
                    <th class="text-left">Last Name</th>
                    <th class="text-right">Phone</th>
                    <th class="text-left">Email</th>
                    <th class="text-right">User Id</th>
                    <th class="text-left">User Type</th>
                    <th class="text-left">Print Barcode</th>
                    <th class="text-left">Status</th>
                    <th class="text-left">Action</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr id="users-row">
                            <td data-order="" class="text-center">
                                <span style="display:none;"></span>
                                {{-- <input type="checkbox" name="selected[]" id="user[][select]" value="" checked="checked" /> --}}
                                <input type="checkbox" name="selected[]" id="user[][select]"  value="" />
                            </td>

                            <td class="text-left">
                                <span>{{ $user->vfname }}</span>
                            </td>

                            <td class="text-left">
                                <span>{{ $user->vlname }}</span>
                            </td>

                            <td class="text-right">
                                <span>{{ $user->vphone }}</span>
                            </td>

                            <td class="text-left">
                                <span>{{ $user->vemail }}</span>
                            </td>

                            <td class="text-right">
                                <span>{{ $user->iuserid }}</span>
                            </td>

                            <td class="text-left">
                                <span>{{ $user->vusertype }}</span>
                            </td>

                            <td class="text-left">
                                <span>{{ $user->vuserbarcode }}</span>
                            </td>

                            <td class="text-left">
                                {{ $user->estatus }}
                            </td>

                            <td class="text-left">
                                <a href="data-toggle="tooltip" title="Edit" class="btn btn-sm btn-info edit_btn_rotate" ><i class="fa fa-pencil">&nbsp;&nbsp;Edit</i>
                                </a>
                            </td>
                        </tr>
                    @endforeach

                  </tbody>
              </table>
            </div>
          </form>

          {{-- <div class="row">
            <div class="col-sm-6 text-left">></div>
            <div class="col-sm-6 text-right"></div>
          </div> --}}

        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
<script type="text/javascript">
    $(window).load(function() {
        $("div#divLoading").removeClass('show');
    });
</script>
@endsection



