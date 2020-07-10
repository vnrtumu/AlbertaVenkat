@extends('layouts.master')

@section('title')
    Departments
@stop


@section('main-content')

{{-- {{ dd($departments) }} --}}
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
          {{-- <h1>Users List</h1> --}}
          <ul class="breadcrumb">
            <li><a href="">Departments</a></li>
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
        @if (session()->has('message'))
            <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i>
                {{session()->get('message')}}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif
      <div class="panel panel-default">
        <div class="panel-heading head_title">
          <h3 class="panel-title"><i class="fa fa-list"></i> Department</h3>
        </div>
        <div class="panel-body">

          <div class="row" style="padding-bottom: 15px;float: right;">
            <div class="col-md-12">
              <div class="">
                <a id="save_button" class="btn btn-primary" title="Save"><i class="fa fa-save"></i>&nbsp;&nbsp;Save</a>
                <button type="button" id="addDepartment" data-toggle="tooltip" title="" class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add New</button>
                <button type="button" class="btn btn-danger" id="delete_department_btn" title="Delete" style="border-radius: 0px;"><i class="fa fa-trash"></i>&nbsp;&nbsp;Delete</button>
              </div>
            </div>
          </div>
          <div class="clearfix"></div>

        <form action="" method="post" id="form_department_search">
          <input type="hidden" name="searchbox" id="idepartmentid">
          <div class="row">
              <div class="col-md-12">
                  <input type="text" name="automplete-product" class="form-control" placeholder="Search Department..." id="automplete-product">
              </div>
          </div>
        </form>
         <br>

          <form action="" method="post" enctype="multipart/form-data" id="form-department">
            <input type="hidden" name="MenuId" value=""/>
            <div class="table-responsive">
              <table id="department" class="table table-bordered table-hover" style="width:100%;">

                <thead>
                  <tr>
                    <th style="width: 1px;color:black;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></th>
                    <th style="width:1px;" class="text-left">Department Code</th>
                    <th style="width:1px;" class="text-left">Department Name</th>
                    <th class="text-left">	Description</th>
                    <th class="text-left">Start Time</th>
                    <th class="text-left">End Time</th>
                    <th class="text-left">No of Categories</th>
                  </tr>
                </thead>
                <tbody>

                    @foreach ($departments as $department)
                    <tr id="department-row">
                        <td data-order="" class="text-center">
                            <span style="display:none;"></span>
                            <input type="checkbox" name="selected[]" id="department" value="{{ $department->vdepcode }}" />
                        </td>

                        <td class="text-left">
                            <span style="display:none;"></span>
                            <input type="text" maxlength="50" class="editable department_c" name="department[][vdepcode]" id="department" value="{{ $department->vdepcode }}" onclick='document.getElementById("").setAttribute("checked","checked");' />
                            <input type="hidden" name="department" value=""/>
                        </td>

                        <td class="text-left">
                            <span style="display:none;"></span>
                            <input type="text" maxlength="50" class="editable department_c" name="department" id="department" value="{{ $department->vdepartmentname }}" onclick='document.getElementById("").setAttribute("checked","checked");' />
                            <input type="hidden" name="department" value=""/>
                        </td>


                        <td class="text-left">
                          <textarea class="editable" maxlength="100" name="department" id="department" onclick='document.getElementById("").setAttribute("checked","checked");'> {{$department->vdescription}}</textarea>
                        </td>

                        <td class="text-left">

                            <?php
                                // dd($department->starttime);
                                // if(isset( $department->starttime )){
                                //     $starttime_string = explode(':', $department->starttime));

                                //     $start_hour = $starttime_string[0];
                                //     $start_minute = $starttime_string[1];
                                // } else {
                                //     $start_hour = '';
                                //     $start_minute = '';
                                // }

                                // if(isset($department->endtime) ){
                                //     $endtime_string = explode(':', $department->endtime);
                                //     $end_hour = $endtime_string[0];
                                //     $end_minute = $endtime_string[1];
                                // } else {
                                //     $end_hour = '';
                                //     $end_minute = '';
                                // }

                            ?>
                          <select class="form-control" name="department" style="width:45%;display:inline-block;">
                            <option value="">hour</option>
                            {{-- <?php if(isset($hours) && count($hours) > 0) {?>
                                <?php foreach($hours as $k => $hour) { ?>
                                <?php if($start_hour == $k){?>
                                    <option value="<?php echo $k;?>" selected="selected"><?php echo $hour;?></option>
                                <?php }else{ ?>
                                    <option value="<?php echo $k;?>"><?php echo $hour;?></option>
                                <?php } ?>
                                <?php } ?>
                            <?php } ?> --}}
                          </select>
                          <select class="form-control" name="department" style="width:45%;display:inline-block;">
                            <option value="">minute</option>
                                {{-- <?php for($m=0;$m<60;$m++) { ?>
                                    <?php if($start_minute == str_pad($m,2,"0",STR_PAD_LEFT)){ ?>
                                    <option selected="selected" value="<?php echo str_pad($m,2,"0",STR_PAD_LEFT);?>"><?php echo str_pad($m,2,"0",STR_PAD_LEFT);?></option>
                                    <?php }else{ ?>
                                    <option value="<?php echo str_pad($m,2,"0",STR_PAD_LEFT);?>"><?php echo str_pad($m,2,"0",STR_PAD_LEFT);?></option>
                                    <?php } ?>
                              <?php } ?> --}}
                          </select>
                        </td>

                        <td class="text-left">
                          <select class="form-control" name="department" style="width:45%;display:inline-block;">
                            <option value="">hour</option>
                                  <option value="" selected="selected"></option>
                                  <option value=""></option>
                          </select>
                          <select class="form-control" name="department" style="width:45%;display:inline-block;">
                            <option value="">minute</option>
                            <option value="" selected="selected"></option>
                            <option value=""></option>
                          </select>
                        </td>

                        <td><span class='view_categories' id=''>{{ $department->cat_count}}</span></td>

                        <td class="text-left" style="display:none;">
                            <input type="text" class="editable department_s" name="department" id="department" value="" onclick='document.getElementById("").setAttribute("checked","checked");' />
                        </td>

                      </tr>
                    @endforeach

                {{-- <tr>
                    <td colspan="7" class="text-center"></td>
                </tr> --}}

                </tbody>
              </table>
            </div>
          </form>

          <div class="row">
            <div class="col-sm-6 text-left"></div>
            <div class="col-sm-6 text-right"></div>
          </div>

        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')


<script type="text/javascript">


$("#addDepartment").click(function(){
  $('#addModal').modal('show');

});
</script>


<!-- Modal Add -->
  <div class="modal fade" id="addModal" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add New Department</h4>
        </div>
        <div class="modal-body">
          <form action="{{ route('departments.store') }}" method="post" id="add_new_form">
            @csrf
            <input type="hidden" name="isequence" value="0">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <div class="col-md-2">
                    <label>Name</label>
                  </div>
                  <div class="col-md-10">
                    <input type="text" maxlength="50" name="vdepartmentname" id="add_vdepartmentname" class="form-control">
                  </div>
                </div>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <div class="col-md-2">
                    <label>Description</label>
                  </div>
                  <div class="col-md-10">
                    <textarea maxlength="100" name="vdescription" id="add_vdescription" class="form-control"></textarea>
                  </div>
                </div>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <div class="col-md-2">
                    <label>Start Time</label>
                  </div>
                  <div class="col-md-10">
                    <select class="form-control" name="start_hour" style="width:45%;display:inline-block;">
                      <option value="">hour</option>
                      <?php if(isset($hours) && count($hours) > 0) {?>
                        <?php foreach($hours as $k => $hour) { ?>
                        <option value="<?php echo $k;?>"><?php echo $hour;?></option>
                        <?php } ?>
                      <?php } ?>
                    </select>
                    <select class="form-control" name="start_minute" style="width:45%;display:inline-block;">
                      <option value="">minute</option>
                      <?php for($m=0;$m<60;$m++) { ?>
                      <option value="<?php echo str_pad($m,2,"0",STR_PAD_LEFT);?>"><?php echo str_pad($m,2,"0",STR_PAD_LEFT);?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <div class="col-md-2">
                    <label>End Time</label>
                  </div>
                  <div class="col-md-10">
                    <select class="form-control" name="end_hour" style="width:45%;display:inline-block;">
                      <option value="">hour</option>
                      <?php if(isset($hours) && count($hours) > 0) {?>
                        <?php foreach($hours as $k => $hour) { ?>
                        <option value="<?php echo $k;?>"><?php echo $hour;?></option>
                        <?php } ?>
                      <?php } ?>
                    </select>
                    <select class="form-control" name="end_minute" style="width:45%;display:inline-block;">
                      <option value="">minute</option>
                      <?php for($m=0;$m<60;$m++) { ?>
                      <option value="<?php echo str_pad($m,2,"0",STR_PAD_LEFT);?>"><?php echo str_pad($m,2,"0",STR_PAD_LEFT);?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-12 text-center">
                <input class="btn btn-success" id="addDepSave" type="submit" value="Save">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
              </div>
            </div>
          </form>
        </div>
      </div>

    </div>
  </div>
<!-- Modal Add-->

<script>

    $('#addDepSave').click(function(){
        $('#add_new_form').submit();
    })
</script>








<div class="modal fade" id="successModal" role="dialog">
    <div class="modal-dialog modal-sm">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="border-bottom:none;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="alert alert-success text-center">
            <p id="success_msg"></p>
          </div>
        </div>
      </div>

    </div>
</div>
<div class="modal fade" id="errorModal" role="dialog" style="z-index: 9999;">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="border-bottom:none;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="alert alert-danger text-center">
            <p id="error_msg"></p>
          </div>
        </div>
        <div class="modal-footer" style="border-top: none;">
        <button type="button" class="btn btn-info" data-dismiss="modal">OK</button>
      </div>
      </div>

    </div>
</div>

@endsection



