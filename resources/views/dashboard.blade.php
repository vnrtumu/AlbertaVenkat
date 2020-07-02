@extends('layouts.master')

@section('main-content')
{{-- {{ dd($output) }} --}}
<div id="content">
    <div class="page-header">
      <div class="container-fluid">
        <!-- <h1></h1> -->
        <ul class="breadcrumb">

          <li><a href="">Dashboard</a></li>

        </ul>

      </div>
      <!-- <br>
      <div class="container-fluid" style="margin-bottom:-5px;">
        <p><a style="font-size:14px;" class="btn btn-success" href="">Quick Links</a></p>
      </div> -->
    </div>
    <div class="container-fluid">
      {{-- <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div> --}}


      <div class="panel panel-default" style="border-left:none;border-right:none;border-bottom:none;">
        <div class="panel-body dashboard_charts_title">
          <div class="row dashboard_amt">
              <div class="col-lg-4 col-md-6">
              <input type="hidden" name='fdate' id= "fdate" value="" >
              <input type="hidden" name='tdate' id= "tdate" value="" >
              <input type="hidden" name='date' id= "date" value="" >
              <input type="hidden" name='api' id= "api" value="" >
              <input type="hidden" name='token' id= "token" value="" >
              <input type="hidden" name='sid' id= "sid" value="" >
                  <!-- small box -->
                  <div class="small-box bg-sales">
                    <div class="inner">
                        <h3>Sales</h3>
                        <p>
                        <h2>Today : $ {{ $output['today'] ??  $output['today'] }} <br></h2>
                            Yesterday : $ {{ $output['yesterday'] ??  $output['yesterday'] }}<br>
                            Week :  $  {{ $output['week'] ??  $output['week'] }}<br>
                        </p>
                    </div>
                    <div class="icon">
                      <i class="fa fa-bars"></i>
                    </div>
                  </div>
              </div>
              <!-- ./col -->
              <div class="col-lg-4 col-md-6">
                  <!-- small box -->
                  <div class="small-box bg-customer">
                    <div class="inner">
                      <h3>Customer</sup></h3>
                      <p>
                        <h2>Today : $ {{ $output['today'] ??  $output['today'] }} <br></h2>
                            Yesterday : $ {{ $output['yesterday'] ??  $output['yesterday'] }}<br>
                            Week :  $  {{ $output['week'] ??  $output['week'] }}<br>
                        </p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-user"></i>
                    </div>
                  </div>
              </div>
              <!-- ./col -->
              <div class="col-lg-4 col-md-6">
                  <!-- small box -->
                  <div class="small-box bg-void">
                    <div class="inner">
                      <h3>Void</sup></h3>
                      <p>
                        <h2>Today : $ {{ $output['today'] ??  $output['today'] }} <br></h2>
                            Yesterday : $ {{ $output['yesterday'] ??  $output['yesterday'] }}<br>
                            Week :  $  {{ $output['week'] ??  $output['week'] }}<br>
                        </p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-pencil"></i>
                    </div>
                  </div>
              </div>
              <!-- ./col -->
          </div>

          <div class="row">
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-body padding15">
                        <strong><h2 class="md-title " align="center"><sup style="font-size: 20px">Last 7 Day Sales</sup></h2></strong>
                         <div class="box-body chart-responsive">
                            <div class="chart" id="chart" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-body padding15">
                        <strong><h2 class="md-title" align="center"><sup style="font-size: 20px">Last 7 Day Customer</sup></h2></strong>
                        <div class="box-body chart-responsive">
                            <div class="chart" id="line-chart" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="panel panel-default">
                  <div class="panel-body padding15">
                     <strong><h2 class="md-title" align="center"><sup style="font-size: 20px">Sales Summary (  )</sup></h2></strong>
                       <div class="box-body chart-responsive">
                           <div class="chart col-md-6" id="sales-chart" style="height: 350px;"></div>
                           <div id="legend" class="donut-legend col-md-6"></div>
                      </div>
                  </div>
              </div>
            </div>
          <div>

          <div class="row">
            <div class="col-md-6">
              <div class="panel panel-default">
                <div class="panel-body padding15" style="padding-bottom:50px;">
                   <strong><h2 class="md-title" align="center"><sup style="font-size: 20px">Top 5 Product</sup></h2></strong>
                     <div class="box-body chart-responsive">
                        <div class="chart" id="item-chart" style="height: 300px;"></div>
                    </div>
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="panel panel-default">
                <div class="panel-body padding15" style="padding-bottom:50px;">
                    <strong><h2 class="md-title" align="center"><sup style="font-size: 20px">Top 5 Category</sup></h2></strong>
                     <div class="box-body chart-responsive">
                        <div class="chart" id="bar-chart" style="height: 300px;"></div>
                    </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="panel panel-default">
                <div class="panel-body padding15">
                  <strong><h2 class="md-title" align="center"><sup style="font-size: 20px">Past 24 Hours Customer Flow</sup></h2></strong>

                  <div class="box-body chart-responsive">
                    <div class="chart" id="cust-chart" style="height: 300px;"></div>
                  </div>
                  <p class="text-center" style="font-size:14px;">Hour [EST]</p>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
  </div>
@endsection

@section('scripts')
<style type="text/css">
    .small-box .icon {
      position: absolute;
      top: auto;
      bottom: 5px;
      right: 5px;
      z-index: 0;
      font-size: 90px;
      color: rgba(0, 0, 0, 0.15);
    }

    .small-box > .inner {
      padding: 10px;
    }

    .small-box {
      position: relative;
      display: block;
      -webkit-border-radius: 2px;
      -moz-border-radius: 2px;
      border-radius: 2px;
      margin-bottom: 15px;
    }

    .small-box h3 {
      font-size: 38px;
      font-weight: bold;
      margin: 0 0 10px 0;
      white-space: nowrap;
      padding: 0;
    }

    .bg-sales {
      background-color: #00a65a !important;
    }

    .bg-customer {
      background-color: #147ABF !important;
    }

    .bg-void {
      background-color: #F05A28 !important;
    }

    .dashboard_amt{
      color: #fff;
    }

    .dashboard_amt div.icon i{
      color: #fff;
    }

    .dashboard_charts_title h2{
      margin-top: 10px;
    }

    .dashboard_charts_title .panel-default{
      border-top: 1px solid #e8e8e8;
    }

  </style>

  {{-- <script type="text/javascript">
    $(document).ready(function(){
      var temp_sevendaysales = '<?php echo json_encode($sevendaysales); ?>';
      window.sevendaysales = $.parseJSON(temp_sevendaysales);

      var temp_sevendaysCustomer = '<?php echo json_encode($sevendaysCustomer); ?>';
      window.sevendaysCustomer = $.parseJSON(temp_sevendaysCustomer);

      var temp_topCategory = '<?php echo json_encode($topCategory); ?>';
      window.topCategory = $.parseJSON(temp_topCategory);

      var temp_topItem = '<?php echo json_encode($topItem); ?>';
      window.topItem = $.parseJSON(temp_topItem);

      var temp_dailySummary = '<?php echo json_encode($dailySummary); ?>';
      window.dailySummary = $.parseJSON(temp_dailySummary);

      var temp_customer = '<?php echo json_encode($customer); ?>';
      window.customer = $.parseJSON(temp_customer);

    });
  </script> --}}

  <link href="view/stylesheet/morris.css" rel="stylesheet" type="text/css"/>
  <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
  <script src="view/javascript/morriss/morris.min.js"></script>
  <script src="view/javascript/dashboardApi.js"></script>

  <script type="text/javascript">
    $(window).load(function() {
      $("div#divLoading").removeClass('show');
    });
  </script>
@endsection


