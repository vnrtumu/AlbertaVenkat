@extends('layouts.master')

@section('title')
    Physical Inventory
@stop

@section('main-content')
<div id="content">
    <style>

    table#item_listing tr th{
        table-layout: fixed !important;
    }

     span.select2-container{
        width: 90% !important;
        min-width:100px; !important;
    }
    #items_status + span.select2-container{
        max-width: 20%;
    }
    thead input {
        width: 100%;
    }

     .table.table-bordered.table-striped.table-hover thead > tr{
     	background: #03a9f4 none repeat scroll 0 0 !important;
     }

     table tbody tr:nth-child(even) td{
    	background-color: #f05a2814;
    }

    .select2-search input {
        color:black;
    }
    .select2-selection__choice {
        color:black;
    }

</style>
  <div class="page-header">
    <div class="container-fluid">
      <ul class="breadcrumb">
        <li><a href=""></a></li>
      </ul>
    </div>
  </div>
  <div class="container-fluid">


    <div class="panel panel-default itemsData">
      <div class="panel-heading head_title">
        <h3 class="panel-title"><i class="fa fa-list"></i> Items</h3>
      </div>
      <div class="panel-body">
        <div class="row" style="padding-bottom: 15px; ">
            <div class="col-md-12">
                <button type="button" class="btn btn-primary" id="scanned_items_model">View All Scanned Data</button> &nbsp;&nbsp;
                <div class="btn-group pull-right">
                    <a href="" class="btn btn-primary" style="margin:1px;"><i class="fa fa-angle-left"></i>&nbsp;&nbsp;Cancel</a>&nbsp;&nbsp;
                    <button class="btn btn-primary" id="next_btn" style="margin:1px;"><i class="fa fa-plus"></i>&nbsp;&nbsp;Next</button>&nbsp;&nbsp;
                </div>
            </div>
        </div>
        <div class="form-group">
            <input type="checkbox" id="for_scanning" >
            <label class="control-label" for="input-template">Add Data One By One Using Scanner </label>
        </div>
        <div class="box-body table-responsive">
            <div class="listed_item_table" id="listed_item_table">
                <form method="post" action="" id="itemlistform">
                    <input type="hidden" name="conditions" id="conditions" >
		            <table id="item_listing" class="table table-bordered table-striped table-hover" style="table-layout:fixed; font-size:10px;">
                        <?php $dynamic_data = [];?>
                        <thead>
                            <tr>
                                <th style="width: 20px;color:black;" class="text-center">
                                    <input type="checkbox" name="selected" id="parent_selected" onchange="$('input[name*=\'selected\']').prop('checked', this.checked);" checked />
                                </th>
                                <th class="text-left text-uppercase no-sort" style="width:60px;">ITEM NAME</th>
                                <th class="text-left text-uppercase no-sort" style="width:60px;">SKU</th>
                                <th class="text-left text-uppercase" style=" width:100px;">PRICE</th>
                                <th class="text-left text-uppercase" style="width:38px;" >COST</th>
                                <th class="text-left text-uppercase no-sort" style="width:72px !important">DEPT.</th>
                                <th class="text-left text-uppercase no-sort" style="width:65px";>CATEGORY</th>
                                <th class="text-right text-uppercase no-sort" style="width:65px";>SUPPLIER</th>
                                <th class="text-left text-uppercase" style="width:65px";>SUB CATEGORY</th>
                                <th class="text-right text-uppercase no-sort" style="width:30px";>QTY. ON HAND</th>
                            </tr>
                            <tr>
                                <th></th>
                                <th><input type="text" style='width:70px;color:black;border-radius: 4px;height:28px;' ></th>
                                <th><input type="text" style='width:70px;color:black;border-radius: 4px;height:28px;' ></th>
                                <th>
                                    <select id='price_select_by' name='price_select_by' style='width:70px;color:black;border-radius: 4px;height:28px;'>
                                        <option value="">Select</option>
                                        <option value="">Greater Than</option>
                                        <option value="">Less Than</option>
                                        <option value="">Equal To</option>
                                        <option value="">Between</option>

                                    </select>
                                    <input type="text" style='width:70px;color:black;border-radius: 4px;height:28px;' >
                                </th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>
                                    <select class='form-control' multiple='true' name='supplier_code[]' id='supplier_code' style='width: 100px;'>
                                        <option value='all'>All</option>

                                            @foreach($vendors as $vendor){
                                               <option value="">{{ $vendor->vcompanyname }}</option>
                                            @endforeach

                                    </select>
                                </th>
                                <th></th>
                                <th></th>

                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($items as $item)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="selected" id="parent_selected" onchange="$('input[name*=\'selected\']').prop('checked', this.checked);" checked />
                                    </td>
                                    <td>{{ $item->vitemname }}</td>
                                    <td>{{ $item->vbarcode }}</td>
                                    <td>{{ $item->dunitprice }}</td>
                                    <td>{{ $item->dcostprice }}</td>
                                    <td>{{ $item->vdepartmentname }}</td>
                                    <td>{{ $item->vcategoryname }}</td>
                                    <td>{{ $item->vcompanyname }}</td>
                                    <td>{{ $item->subcat_name }}</td>
                                    <td>{{ $item->iqtyonhand }}</td>
                                </tr>
                            @endforeach

                        </tbody>
		            </table>
		        </form>
		    </div>
		</div>
      </div>
    </div>
  </div>
</div>

@endsection


@section('scripts')
    <link href = "https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel = "stylesheet">
    <script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>

    <link type="text/css" href="view/stylesheet/select2/css/select2.min.css" rel="stylesheet" />


    <!-- DataTables -->
    <script src="/view/javascript/jquery.dataTables.min.js"></script>

    <script src="/view/javascript/dataTables.bootstrap.min.js"></script>
    <script src="view/javascript/select2/js/select2.min.js"></script>
    <script src="view/javascript/bootbox.min.js" defer></script>

@endsection
