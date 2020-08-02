@extends('layouts.master')

@section('title')
    Vendors
@stop

@section('main-content')
<div id="content">
    <div class="page-header">
      <div class="container-fluid">
        <ul class="breadcrumb">
          <li><a href="">Vendors</a></li>

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
          <h3 class="panel-title"><i class="fa fa-list"></i> Vendors</h3>

        </div>
        <div class="panel-body">

          <div class="row" style="padding-bottom: 9px;float: right;">
            <div class="col-md-12">
              <div class="">
                <a id="save_button" class="btn btn-primary" title="Save"><i class="fa fa-save"></i>&nbsp;&nbsp;Save</a>
                <a href="{{ route('vendors.create') }}" title="" class="btn btn-primary add_new_btn_rotate"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add New</a>
               <button type="button" class="btn btn-danger" id="vender_delete" onclick="myFunction()" title="Delete" style="border-radius: 0px;"><i class="fa fa-trash"></i>&nbsp;&nbsp;Delete</button>
              </div>
            </div>
          </div>

        <form action="{{ route('vendors') }}" method="post" id="form_vendor_search">
            @csrf
          <input type="hidden" name="searchbox" id="vendor_name">
          <div class="row">
              <div class="col-md-12">
                  <input type="text" autocomplete="off" name="automplete-product" class="form-control" placeholder="Search Vendor..." id="automplete-product">
              </div>
          </div>
        </form>
         <br>
          <form action="{{ route('vendors.remove') }}" method="post" enctype="multipart/form-data" id="form-vendor">
            @csrf
            <input type="hidden" name="MenuId" value=""/>
            <div class="table-responsive">
              <table id="vendor" class="table table-bordered table-hover">

                <thead>
                  <tr>
                    <th style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></th>
                    <th class="text-left">Supplier Code</th>
                    <th class="text-left">Vendor Code</th>
                    <th class="text-left">Vendor Name</th>
                    <th class="text-right">Phone</th>
                    <th class="text-left">Email</th>
                    <th class="text-left">Status</th>
                    <th class="text-left">Action</th>
                  </tr>
                </thead>
                <tbody>

                    <?php $vendor_row = 1;$i=0; $selected = array(); ?>
                    @foreach ($vendors as $vendor)
                    <tr id="vendor-row{{ $vendor_row }}">
                        <td data-order="<?php echo $vendor->isupplierid; ?>" class="text-center">
                        <span style="display:none;"><?php echo $vendor->isupplierid; ?></span>
                        <?php if (in_array($vendor->isupplierid, $selected)) { ?>
                          <input type="checkbox" name="selected[]" id="vendor[<?php echo $vendor_row; ?>][select]" value="<?php echo $vendor->isupplierid; ?>" checked="checked" />
                          <?php } else { ?>
                          <input type="checkbox" name="selected[]" id="vendor[<?php echo $vendor_row; ?>][select]"  value="<?php echo $vendor->isupplierid; ?>" />
                        <?php } ?></td>


                        <td class="text-left">
                            <span style=""><?php echo $vendor->vsuppliercode; ?></span>
                            <input type="hidden" name="vendor[<?php echo $i; ?>][vsuppliercode]" value="<?php echo $vendor->vsuppliercode; ?>"/>
                            <input type="hidden" name="vendor[<?php echo $i; ?>][isupplierid]" value="<?php echo $vendor->isupplierid; ?>"/>
                        </td>

                        <td class="text-left">
                            <span style=""><?php echo $vendor->vcode; ?></span>
                            <input type="hidden" name="vendor[<?php echo $i; ?>][vcode]" value="<?php echo $vendor->vcode; ?>"/>
                            <input type="hidden" name="vendor[<?php echo $i; ?>][isupplierid]" value="<?php echo $vendor->isupplierid; ?>"/>
                        </td>

                        <td class="text-left">
                        <span style="display:none;"><?php echo $vendor->vcompanyname; ?></span>
                          <input type="text" class="editable vendors_c" maxlength="50" name="vendor[<?php echo $i; ?>][vcompanyname]" id="vendor[<?php echo $i; ?>][vcompanyname]" value="<?php echo $vendor->vcompanyname; ?>" onclick='document.getElementById("vendor[<?php echo $vendor_row; ?>][select]").setAttribute("checked","checked");' />
                        </td>

                        <td class="text-right">
                          <input type="text" class="editable vendors_phone" maxlength="20" name="vendor[<?php echo $i; ?>][vphone]" id="vendor[<?php echo $i; ?>][vphone]" value="<?php echo $vendor->vphone; ?>" onclick='document.getElementById("vendor[<?php echo $vendor_row; ?>][select]").setAttribute("checked","checked");' style="text-align: right;"/>
                        </td>

                        <td class="text-left">
                        <span style="display:none;"><?php echo $vendor->vemail; ?></span>
                          <input type="email" class="editable vendors_email" maxlength="100" name="vendor[<?php echo $i; ?>][vemail]" id="vendor[<?php echo $i; ?>][vemail]" value="<?php echo $vendor->vemail; ?>" onclick='document.getElementById("vendor[<?php echo $vendor_row; ?>][select]").setAttribute("checked","checked");' />
                        </td>

                        <td class="text-left">
                            <span>{{ $vendor->estatus }}</span>
                        </td>

                        <td class="text-left">
                          <a href="{{ route('vendors.edit', $vendor->isupplierid ) }}" data-toggle="tooltip" title="Edit" class="btn btn-sm btn-info edit_btn_rotate" ><i class="fa fa-pencil"></i>&nbsp;&nbsp;Edit</a>
                        </td>
                      </tr>
                      <?php $vendor_row++; $i++; ?>

                    @endforeach

                </tbody>
              </table>
            </div>
          </form>
          <div class="row">
            <div class="col-sm-6 text-left">{{ $vendors->links() }}</div>
            <div class="col-sm-6 text-right"></div>
          </div>
        </div>
      </div>
    </div>
</div>
@endsection

@section('scripts')
  <script>
      $('#vender_delete').click(function(){
        $('#form-vendor').submit();
      })
  </script>

<script type="text/javascript">
    $(document).on('click', '#save_button', function(event) {
      event.preventDefault();
      var edit_url = '<?php echo $data['edit_list']; ?>';

      edit_url = edit_url.replace(/&amp;/g, '&');

      var all_vendor = true;

      $('.vendors_c').each(function(){
        if($(this).val() == ''){
          alert('Please Enter Vendor Name');
          all_vendor = false;
          return false;
        }else{
          all_vendor = true;
        }
      });

    //   var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;

    //   $('.vendors_email').each(function(){
    //     if($(this).val() != ''){
    //       if (!emailReg.test($(this).val())) {
    //         alert('Please Enter Valid Email');
    //         all_vendor = false;
    //         return false;
    //       }else{
    //         all_vendor = true;
    //       }
    //     }
    //   });

      if(all_vendor == true){
        $('#form-vendor').attr('action', edit_url);
        $('#form-vendor').submit();
        $("div#divLoading").addClass('show');
      }
    });
  </script>

@endsection
