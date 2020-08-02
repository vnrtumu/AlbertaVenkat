@extends('layouts.master')

@section('title')
    Physical Inventory
@stop

@section('styles')

@endsection

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
    @if ($data['error_warning'])
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> {{ $data['error_warning'] }}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif
    @if ($data['success'])
        <div class="alert alert-success"><i class="fa fa-check-circle"></i> {{ $data['success'] }}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif
    <div class="panel panel-default itemsData">
      <div class="panel-heading head_title">
        <h3 class="panel-title"><i class="fa fa-list"></i> Items</h3>

      </div>
      <div class="panel-body">

        <div class="row" style="padding-bottom: 15px; ">
            <div class="col-md-12">

                <button type="button" class="btn btn-primary" id="scanned_items_model">View All Scanned Data</button> &nbsp;&nbsp;
                <div class="btn-group pull-right">
                    <a href="{{ $data['cancel'] }}" class="btn btn-primary" style="margin:1px;"><i class="fa fa-angle-left"></i>&nbsp;&nbsp;Cancel</a>&nbsp;&nbsp;
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
                <form method="post" action="{{ $data['url_next'] }}" id="itemlistform">
                    <input type="hidden" name="conditions" id="conditions" >
		            <table id="item_listing" class="table table-bordered table-striped table-hover" style="table-layout:fixed; font-size:10px;">
		                <thead>
                            <?php $dynamic_data = [];?>
                            <tr>
                              <th style="width: 20px;color:black;" class="text-center"><input type="checkbox" name="selected" id="parent_selected" onchange="$('input[name*=\'selected\']').prop('checked', this.checked);" checked /></th>
                              <th class="text-left text-uppercase no-sort" style="width:60px;">ITEM NAME</th>
                              <?php $dynamic_data[] = "vitemname";?>
                              <th class="text-left text-uppercase no-sort" style="width:60px;">SKU</th>
                              <?php $dynamic_data[] = "vbarcode";?>
                              <th class="text-left text-uppercase" style=" width:100px;">PRICE</th>
                              <?php $dynamic_data[] = "dunitprice";?>
                              <th class="text-left text-uppercase" style="width:38px;" >COST</th>
                              <?php $dynamic_data[] = "unitcost";?>
                              <?php if(isset($data['itemListings']) && count($data['itemListings'])){ ?>
                                <?php foreach($data['itemListings'] as $m => $itemListing){ ?>
                                   <th class="text-left text-uppercase no-sort" style="width:72px !important">{{  $data['title_arr'][$m] }}</th>
                                   <?php if($m == 'vcategorycode'){
                                        $dynamic_data[] = "vcategoryname";
                                        }else if($m ==  'vdepcode'){
                                            $dynamic_data[] = "vdepartmentname";
                                        }else if($m ==  'subcat_id'){
                                            $dynamic_data[] = "subcat_name";
                                        }else if($m ==  'vsuppliercode'){
                                            $dynamic_data[] = "vcompanyname";
                                        }else{
                                            $dynamic_data[] = $m;
                                        } ?>
                                  <?php } ?>
                              <?php } else { ?>
                                <th class="text-left text-uppercase no-sort" style="width:65px";><?php echo $column_deptcode; ?></th>
                                <?php $dynamic_data[] = "vdepartmentname";?>
                                <th class="text-left text-uppercase" style="width:65px";><?php echo $column_categorycode; ?></th>
                                <?php $dynamic_data[] = "vcategoryname";?>
                                <th class="text-right text-uppercase no-sort" style="width:65px";><?php echo $column_price; ?></th>
                                <?php $dynamic_data[] = "subcat_name";?>
                              <?php } ?>
                              <th class="text-right text-uppercase no-sort" style="width:30px";>QTY. ON HAND</th>
                                <?php $dynamic_data[] = "iqtyonhand";?>
                            </tr>
                        </thead>
		            </table>
		        </form>
		    </div>
		</div>  <!-- /.box-body -->
      </div>
    </div>
  </div>
</div>
@endsection

@section('script_files')
    <link href = "https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel = "stylesheet">
    <script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
    <link type="text/css" href="{{ asset('stylesheet/select2/css/select2.min.css') }}" rel="stylesheet" />
    <!-- DataTables -->
    <script src="{{ asset('javascript/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('javascript/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('javascript/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('javascript/bootbox.min.js') }}" defer></script>
    <!----  Session ----->
    <script src="{{ asset('javascript/jquery/jquery.session.js') }}"></script>
@endsection

@section('scripts')
    <script>
        $(document).on('change', '#price_select_by', function(){
            var select_by = $(this).val();
            var html='';
            if(select_by === 'between'){
                $('#price_select_by').css('width', '55px');
                html = '<input type="text" autocomplete="off" name="select_by_value_1" id="select_by_value_1" class="search_text_box1" placeholder="Enter Amt" style="width:35px;color:black;border-radius: 4px;height:28px;padding-left: 1px;padding-right: 1px;margin-left:5px;" value=""/>';
                html += '<input type="text" autocomplete="off" name="select_by_value_2" id="select_by_value_2" class="search_text_box1" placeholder="Enter Amt" style="width:35px;color:black;border-radius: 4px;height:28px;padding-left: 1px;padding-right: 1px;margin-left:5px;" value="" required/>'
            } else {
                $('#price_select_by').css('width', '70px');
                html = '<input type="text" autocomplete="off" name="select_by_value_1" id="select_by_value_1" class="search_text_box1" placeholder="Enter Amt" style="width:60px;color:black;border-radius: 4px;height:28px;margin-left:5px;" value=""/>'
                // $('#selectByValuesSpan').html('not between');
            }
            $('#selectByValuesSpan').html(html);
        });
    </script>

    <script>
        var table;  //declare table variable global
        $(document).ready(function() {

        var url = "<?php echo $data['searchitem']; ?>";
        url = url.replace(/&amp;/g, '&');

        var edit_url = "";
        edit_url = edit_url.replace(/&amp;/g, '&');

        var departments = "<?php echo $data['departments']; ?>";
        var category = "<?php echo $data['category'] ?>";
        var subcategory = "<?php echo $data['subcategory'] ?>";
        var supplier = "<?php echo $data['supplier'] ?>";
        var price_select_by = "<?php echo $data['price_select_by']; ?>";

        $(document).on('input', '#select_by_value_2, #select_by_value_1', function(){

                select_by_val1 = parseFloat($('#select_by_value_1').val());
                select_by_val2 = parseFloat($('#select_by_value_2').val());
                setTimeout(function(){
                    if($('#price_select_by').val() == 'between' && typeof(select_by_val1) != "undefined" && select_by_val1 !== null && typeof(select_by_val2) != "undefined" && select_by_val2 !== null && select_by_val1 >= select_by_val2){
                            bootbox.alert({
                                        size: 'small',
                                        title: "Attention",
                                        message: "Second value must be greater than first value!",
                                    });
                    }
                }, 1400);
        });


        $('#item_listing thead tr').clone(true).appendTo( '#item_listing thead' );
        $('#item_listing thead tr:eq(1) th').each( function (i) {
            var title = $(this).text();
            if(title == "SKU")
            {
                $(this).html( '<input type="text" name="sku_search" id="sku_search" class="search_text_box" placeholder="Search" style="color:black;border-radius: 4px;height:28px;"/>' );
            }
            else if(title == "ITEM NAME"){
                $(this).html( '<input type="text" name="item_search" id="item_search" class="search_text_box" placeholder="Search" style="color:black;border-radius: 4px;height:28px;"/>' );
            }
            else if(title == "PRICE"){
                $(this).html(price_select_by);
            }
            else if(title == "Dept.")
            {
                $(this).html(departments)
            }
            else if(title == "Category")
            {
                $(this).html(category)
            }
            else if(title == "Sub Category")
            {
                $(this).html(subcategory)
            }
            else if(title == "Supplier")
            {
                $(this).html(supplier)
            }
            else{
                $(this).html( '' );
            }
            var timer;

            $( '.search_text_box', this ).on( 'keyup change', function () {
                var self = this;
                clearTimeout(timer);
                timer = setTimeout(function () {
                    if ( table.column(i).search() !== self.value ) {
                    table
                        .column(i)
                        .search( self.value )
                        .draw();
                }
                },0);

            } );

            //========filter for price==============
            $(document).on( 'input', '.search_text_box1', function () {
                var selectBy = $("#price_select_by").val();
                var select_by_value_1 = $('#select_by_value_1').val();
                var select_by_value_2 = $('#select_by_value_2').val();
                var searchVal = ''
                if(selectBy){
                    searchVal += selectBy
                }
                if(select_by_value_1){
                    searchVal += ('|' + select_by_value_1)
                }
                if(select_by_value_2){
                    searchVal += ('|'+select_by_value_2)
                }
                    clearTimeout(timer);
                    timer = setTimeout(function () {

                        table
                            .column(3)
                            .search( searchVal)
                            .draw();

                    },0);
            } );

            $( '#price_select_by', this ).on( 'change', function () {
                var self = this;
                clearTimeout(timer);
                timer = setTimeout(function () {
                    if ( table.column(i).search() !== self.value ) {
                    table
                        .column(i)
                        .search( self.value )
                        .draw();
                }
                },0);
            } );

            $( '#dept_code', this ).on( 'change', function () {
                var search = [];
                        $.each($('#dept_code option:selected'), function(){
                            search.push(this.value);
                        });
                        search = search.join("','");
                    table
                        .column(5)
                        .search( search )
                        .draw();
            } );
            $( '#category_code', this ).on( 'change', function () {
                var search = [];
                $.each($('#category_code option:selected'), function(){
                            search.push(this.value);
                        });
                        search = search.join("','");
                    table
                        .column(6)
                        .search( search )
                        .draw();
            } );

            $( '#supplier_code', this ).on( 'change', function () {
                var search = [];
                $.each($('#supplier_code option:selected'), function(){
                            search.push(this.value);
                        });
                        search = search.join("','");
                    table
                        .column(8)
                        .search( search )
                        .draw();
            } );
            $( '#subcat_id', this ).on( 'change', function () {
                var search = [];
                $.each($('#subcat_id option:selected'), function(){
                            search.push(this.value);
                        });
                        search = search.join("','");
                    table
                        .column(7)
                        .search( search )
                        .draw();
            } );
        } );

        $(document).on("change","#dept_code",function(){
            var get_category_ajax;
            if($(this).val() != "")
            {
                $('#category_code').attr("placeholder", "Loading...");
                var get_categories_url = "<?php echo $data['get_categories_url']; ?>";
                get_categories_url = get_categories_url.replace(/&amp;/g, '&');
                var get_department_items_url = '';
                get_department_items_url = get_department_items_url.replace(/&amp;/g, '&');
                var dep_code = [$(this).val()];
                console.log(dep_code);
                if(get_category_ajax && get_category_ajax.readyState != 4 ){
                    get_category_ajax.abort();
                }
                get_category_ajax = $.ajax({
                    url: get_categories_url,
                    headers: {
                        'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
                    },
                    type: 'post',
                    data : {dep_code : dep_code},
                    success:function(data){ console.log(data);
                        if(data)
                        {
                            $('#category_code').attr("placeholder", "Select Category");
                            $( '#category_code' ).html( data );
                            $('#category_code').prop("disabled", false);
                        }
                        else
                        {
                            $( '#category_code' ).html( '' );
                            $('#category_code').prop("disabled", true);
                        }
                    }
                })
            }
        });

        $(document).on("change","#category_code",function(){
            var get_subcategory_ajax;
            if($(this).val() != "")
            {
                $('#subcat_id').attr("placeholder", "Loading...");
                var get_subcategories_url = "{{ $data['get_subcategories_url'] }}";
                get_subcategories_url = get_subcategories_url.replace(/&amp;/g, '&');

                var category_code = [$(this).val()];

                if(get_subcategory_ajax && get_subcategory_ajax.readyState != 4 ){
                    get_subcategory_ajax.abort();
                }
                get_category_ajax = $.ajax({
                    url: get_subcategories_url,
                    headers: {
                        'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
                    },
                    type: 'post',
                    data : {category_code : category_code},
                    success:function(data){ console.log(data);
                        if(data)
                        {
                            $('#subcat_id').attr("placeholder", "Select Sub Category");
                            $( '#subcat_id' ).html( data );
                            $('#subcat_id').prop("disabled", false);
                        }
                        else
                        {
                            $( '#subcat_id' ).html( '' );
                            $('#subcat_id').prop("disabled", true);
                        }
                    }
                })
            }
        });

        $("#dept_code").select2({closeOnSelect:true,placeholder: 'Select Department'});
        $("#category_code").select2({closeOnSelect:true,placeholder: 'Select Category'});
        $("#subcat_id").select2({closeOnSelect:true,placeholder: 'Select Sub Category'});
        $("#supplier_code").select2({closeOnSelect:true,placeholder: 'Select Supplier'});
        // $("#price_select_by").select2();
        var dynamic_data = JSON.parse('<?php echo json_encode($dynamic_data);?>');
        var data_array = [];
        $.each(dynamic_data, function(key,value) {
            data_array.push({ "data": value });
        });
        data_array.unshift({data: "iitemid", render: function(data, type, row){
                                return $("<input>").attr({
                                    type: 'checkbox',
                                    class: "iitemid",
                                    value: data,
                                    name: "selected[]",
                                    "data-order": data,
                                    checked:"checked"
                                })[0].outerHTML;
                            }});

            table = $("#item_listing").DataTable({
                            "bSort": false,
                            "autoWidth": false,
                            "fixedHeader": true,
                            "processing": true,
                            "iDisplayLength": 20,
                            "serverSide": true,
                            "bLengthChange": false,
                            "language": {
                                search: "_INPUT_",
                                searchPlaceholder: "Search..."
                            },
                            "dom": '<"mysearch"lf>rt<"bottom"ip>',
                            "ajax": {
                                url: url,
                                headers: {
                                    'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
                                },
                                type: 'POST',
                                "dataSrc": function ( json ) {
                                    $("div#divLoading").removeClass('show');
                                    return json.data;
                                }
                            },
                            columns : data_array,
                    }).on('draw', function(){
                        if($('#parent_selected').prop("checked") == true){
                            $('.iitemid').prop('checked', true);
                        } else{
                            $('.iitemid').prop('checked', false);
                        }
                    });

        var totalDisplayRecord = table.page.info().recordsDisplay;
        console.log(totalDisplayRecord);

        $("#item_listing_processing").remove();
        $("#item_listing_filter").hide();
        });

        var itemId = [];
        var scanned_iitemid = [];
        var selected = [];
        $(document).ready(function(event){

            var session_url = "<?php echo $data['session_url']; ?>" ;
            session_url = session_url.replace(/&amp;/g, '&');

            var scanned_session_url = "<?php echo $data['scanned_session_url']; ?>" ;
            scanned_session_url = scanned_session_url.replace(/&amp;/g, '&');

            $(document).on('click', '.iitemid', function () {

                if($('#for_scanning').prop("checked") == false){

                    $('.iitemid').filter(':checked').each(function(){
                        itemId.push($(this).data('order'));
                    });

                        $.ajax({
                            url: session_url,
                            headers: {
                                'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
                            },
                            type: 'post',
                            data : {itemid : itemId},
                            success:function(data){
                                if(data.success == true)
                                {
                                    console.log(data.success_msg);
                                    console.log(data.item_id);
                                }
                                else
                                {
                                    console.log(data.item_id);
                                    console.log('failed');
                                }

                            }
                        })
                    itemId = [];

                }

                if($('#for_scanning').prop("checked") == true && $('#sku_search').val() != ''){
                }
            });


            //========scanning sku oninput event==========
            $('#sku_search').on('input', function(){

                if($('#for_scanning').prop("checked") == true && $('#sku_search').val() != ''){

                    setTimeout(function(){
                        $('.iitemid').prop("checked", true);

                    // scanned_iitemid.push($('#sku_search').val());
                        $('.iitemid').filter(':checked').each(function(){
                        scanned_iitemid.push($(this).data('order'));
                    });
                        $.ajax({
                                    url: scanned_session_url,
                                    headers: {
                                        'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
                                    },
                                    type: 'post',
                                    data : {itemid : scanned_iitemid},
                                    success:function(data){
                                        if(data.success == true)
                                        {
                                            console.log(data.success_msg);
                                            console.log(data.item_id);
                                        }
                                        else if(data.success == false)
                                        {
                                            console.log(data.item_id);
                                            console.log('failed');
                                        }

                                    }
                            });
                    },2000);
                    setTimeout(function(){
                        $('#sku_search').val('');
                    },3000);
                }
            });
            //========validation on next button =============
            $('#next_btn', this).on('click', function(){
                var count =0;
                var parentcount = 0;
                $('input[name="selected[]"]:checked').each(function(i){
                    count = count + 1;
                    selected[i] = $(this).val();
                });
                if($('#parent_selected').prop("checked") == true){
                    parentcount = parentcount + 1;
                }
                var item_search = $('#item_search').val();
                var sku_search = $('#sku_search').val();
                var price_select_by = $('#price_select_by').val();
                var department = $('#dept_code').val();
                var category = $('#category_code').val();
                var subcategory = $('#subcat_id').val();
                var supplier = $('#supplier_code').val();
                var no_of_rows = $('#item_listing tr').length;
                var empty = $('.dataTables_empty').text();
                if(empty == 'No data available in table'){
                    bootbox.confirm({
                                    size: 'small',
                                    title: "Attention",
                                    message: "Not any Item are there, Table is blank",
                                    callback: function(result){
                                    }
                                });
                }
                else if((count <= 0 || parentcount > 0) && $('#for_scanning').prop("checked") == false && item_search === "" && sku_search === "" && price_select_by == "" && department == null && supplier == null && category == null && subcategory == null ){
                    bootbox.confirm({
                                    size: 'small',
                                    title: "Attention",
                                    message: "No filters have been selected which implies that you intend to include all the items.<br><br> Do you want to proceed ?",
                                    callback: function(result){
                                        if(result){
                                            $('#conditions').val('all');
                                            $("div#divLoading").addClass('show');
                                            $('#itemlistform').submit();
                                        }
                                    }
                                });
                }else if($('#for_scanning').prop("checked") == true && scanned_iitemid.length <= 0 ){
                    bootbox.confirm({
                                    size: 'small',
                                    title: "Attention",
                                    message: "Not any Item is Scanned, Table is blank",
                                    callback: function(result){
                                    }
                                });
                }else if($('#for_scanning').prop("checked") == true && scanned_iitemid.length > 0 ){
                    $('#conditions').val('scanned_data');
                    $('#itemlistform').submit();
                }else if($('#for_scanning').prop("checked") == false){
                    $('#conditions').val('session_filters_data'); //==this is for both session and filters data===
                    $('#itemlistform').submit();
                }
            });
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            var get_scanned_data = "<?php echo $data['get_scanned_data']; ?>";
            get_scanned_data = get_scanned_data.replace(/&amp;/g, '&');
            $('#scanned_items_model').on('click', function(){
                $("#scanned_data_table tbody").empty();
                $.ajax({
                    url: get_scanned_data,
                    headers: {
                        'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
                    },
                    type: 'get',
                    success:function(data){
                        if(data.success == true)
                        {
                            var newRowContent = '';
                            $(data.scanned_data).each(function(index, e){
                                newRowContent += "<tr id='"+index+"'>";
                                newRowContent += "<td><center><input type='checkbox' name='itemid_selected' class='itemid' id='itemid_"+index+"' data-itemid='"+e.iitemid+"' data-row='"+index+"'></center></td>";
                                newRowContent += "<td>"+ e.vbarcode +"</td>";
                                newRowContent += "<td>"+ e.vitemname +"</td>";
                                newRowContent += "<td>"+ e.iqtyonhand +"</td>";
                                newRowContent += "<td>"+ e.unitcost +"</td>";
                                newRowContent += "</tr>";
                                $("#scanned_data_table tbody").append(newRowContent);
                                $('#viewModal').modal('show');
                                newRowContent = '';
                            });
                        }
                        else
                        {
                            $('#errorModal').modal('show');
                            console.log(data);
                            console.log('failed');
                        }
                    }
                });
            });

            var remove_scanned_itemid = [];
            var remove_rows = [];
            var remove_session_scanned_data = "<?php echo $data['remove_session_scanned_data']; ?>";
            remove_session_scanned_data = remove_session_scanned_data.replace(/&amp;/g, '&');
            $("#viewModal").on('click','#remove_button',function(){
                $('.itemid').filter(':checked').each(function(){
                        remove_scanned_itemid.push($(this).data('itemid'));
                        remove_rows.push($(this).data('row'));
                });
                console.log(remove_scanned_itemid);
                $.ajax({
                    url: remove_session_scanned_data,
                    headers: {
                        'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
                    },
                    type: 'post',
                    data : {itemid : remove_scanned_itemid},
                    success:function(data){
                        if(data.success == true)
                        {
                            console.log(data.success_msg);
                            console.log(data.item_id);
                        }
                        else if(data.success == false)
                        {
                            console.log(data.item_id);
                            console.log('failed');
                        }
                    }
                });
                $(remove_rows).each(function(index, e){
                    $('#'+e).remove();
                });
                $(remove_scanned_itemid).each(function(index, e){
                    scanned_iitemid = $.grep(scanned_iitemid, function(value) {
                    return value != e;
                    });
                });
            });
        });
    </script>

    <!-- Modal -->
    <div class="modal fade" id="errorModal" role="dialog">
        <div class="modal-dialog modal-sm">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header" style="border-bottom:none;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-success text-center">
                        <p id="success_msg"><strong>No data Found</strong></p>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="viewModal" role="dialog">
        <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Scanned Item</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12">
                            <button type="button" class="btn btn-primary pull-right" id="remove_button">Remove</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                            <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="scanned_data_table">

                                <thead id="txt" style="font-size:12px;">
                                    <tr>
                                        <th style="width: 1px;color:black;" class="text-center"><input type="checkbox" name="itemid_selected" onchange="$('input[name*=\'itemid_selected\']').prop('checked', this.checked);" /></th>
                                        <th class="text-center" style="vertical-align : middle;">SKU</th>
                                        <th class="text-center" style="vertical-align : middle;">Item Name</th>
                                        <th class="text-center" >QOH</th>
                                        <td class="text-center" >Price</td>
                                    </tr>
                                </thead>
                                <tbody id="cal_post_table">

                                </tbody>
                            </table>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal" onclick="$(this).closest('tr').remove();">Close</button>
            </div>
        </div>

        </div>
    </div>

    <script type="text/javascript">
        $(function() { $('[name="automplete-search-box"]').focus(); });
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#scanned_items_model').attr("disabled", true);
            $('#for_scanning').on('click', function(){
                if($('#for_scanning').prop("checked") == true){
                    $('#parent_selected').prop("checked", false);
                    $('.iitemid').prop("checked", false);
                    $('#scanned_items_model').attr("disabled", false);
                    $('#sku_search').focus();
                }else{
                    $('#parent_selected').prop("checked", true);
                    $('.iitemid').prop("checked", true);
                    $('#scanned_items_model').attr("disabled", true);
                }
            });
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function(){
            $(window).load(function() {
                $("div#divLoading").removeClass('show');
            });

            var unset_session_scanned_data = "<?php echo $data['unset_session_scanned_data']; ?>";
            unset_session_scanned_data = unset_session_scanned_data.replace(/&amp;/g, '&');
            $.ajax({
                url: unset_session_scanned_data,
                headers: {
                    'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
                },
                type: 'get',
                success:function(data){
                    if(data.success == true)
                    {
                        console.log(data);
                    }
                    else
                    {
                        console.log(data);
                    }
                }
            });
        });
    </script>
@endsection
