@extends('layouts.main')

@section('title', 'Food and Beverages Revenue Report')

@section('css')
<style type="text/css">

</style>
@endsection
@section('breadcrumb')


@if(Session::get('users.user_data')->privilege=="admin")
  <a class="section hideprint" href="/reports">Reports</a>
  <i class="right angle icon divider"></i>
  <div class="active section">F&B Revenue</div>
@else
  <div class="active section">F&B Revenue</div>
@endif
@endsection
@section('content')
 
<div class="col-sm-12">

  <h1 style="text-align: center;">Food and Beverage Revenue Report<br><small><b>Date From:</b> {{date("F d, Y",strtotime($date_from))}} <b>Date To:</b> {{date("F d, Y",strtotime($date_to))}} </small></h1>
  <div>
    <div class="checkbox">
      <label><input type="checkbox" ng-model="show_sales_information">Show Sales Information</label>
    </div>
    <div class="checkbox">
      <label><input type="checkbox" ng-model="show_sales">Show Sales</label>
    </div>
    <div class="checkbox">
      <label><input type="checkbox" ng-model="show_settlements">Show Settlements</label>
    </div>
    @if(Session::get('users.user_data')->privilege!="restaurant_cashier")
    <div class="checkbox">
      <label><input type="checkbox" ng-model="show_accounting">Show Accounting</label>
    </div>
    @endif
    
<!--     <div class="checkbox">
      <label><input type="checkbox" ng-model="show_paging" ng-change="toggle_paging()">Paging</label>
    </div> -->
  </div>
  <div>
    @if(Session::get('users.user_data')->privilege=="restaurant_cashier")
    @else
    <label>Filter By:</label>
    <form class="form-inline" style="margin-bottom: 20px;">
      <div class="form-group">
        <label>Server:</label>
        <select class="form-control input-sm" ng-options="item as item.name for item in restaurant_servers track by item.id" ng-model="server">
          <option value="">All Waiter/Waitress</option>
        </select>
      </div>
      <div class="form-group">
      <label>Cashier:</label>
      <select class="form-control input-sm" ng-options="item as item.name for item in restaurant_cashiers track by item.id" ng-model="cashier">
        <option value="">All Cashiers</option>
      </select>
      </div>
      <div class="form-group">
      <label>Date From:</label>
      <input type="text" class="form-control input-sm" id="date_from" ng-model="date_from" readonly>
      </div>
      <div class="form-group">
      <label>Date To:</label>
      <input type="text" class="form-control input-sm" id="date_to" ng-model="date_to" readonly>
      <button class="ui primary button" ng-click="filter_result()" ng-class="{'loading':submit}">Filter Results</button>
      </div>
    </form>
    @endif
  </div>
  
  <div class="table-responsive">
    <table class="ui unstackable striped celled structured table">
      <thead>
        <tr>
          <th rowspan="2" class="center aligned middle aligned">Date</th>
          <th rowspan="2" class="center aligned middle aligned">Check #</th>
          <th rowspan="2" class="center aligned middle aligned" ng-show="show_sales_information">Outlet</th>
          <th rowspan="2" class="center aligned middle aligned" ng-show="show_sales_information"># of Pax</th>
          <th rowspan="2" class="center aligned middle aligned" ng-show="show_sales_information">Server</th>
          <th rowspan="2" class="center aligned middle aligned" ng-show="show_sales_information">Cashier</th>
          <th rowspan="2" class="center aligned middle aligned" ng-show="show_sales_information">Guest Name</th>
          <th rowspan="2" class="center aligned middle aligned" ng-show="show_sales_information"># of SC/PWD</th>
          @foreach ($categories as $category)
            <th rowspan="2" class="center aligned middle aligned" ng-show="show_sales">{{$category}}</th>
          @endforeach
          <th rowspan="2" class="center aligned middle aligned" ng-show="show_sales">Gross Amount</th>
          <th rowspan="2" class="center aligned middle aligned" ng-show="show_sales">Total Discount</th>
          <th rowspan="2" class="center aligned middle aligned" ng-show="show_sales">NET Amount</th>
          <th class="center aligned middle aligned" ng-show="show_settlements" colspan="{{ count($settlements)+4 }}">Mode of Payments / Settlements</th>
          <th rowspan="2" class="center aligned middle aligned" ng-show="show_accounting">Special Discount</th>
          <th rowspan="2" class="center aligned middle aligned" ng-show="show_accounting">Gross Billing</th>
          <th rowspan="2" class="center aligned middle aligned" ng-show="show_accounting">SC/PWD Discount</th>
          <th rowspan="2" class="center aligned middle aligned" ng-show="show_accounting">SC/PWD VAT Exemption</th>
          <th rowspan="2" class="center aligned middle aligned" ng-show="show_accounting">NET Billing</th>
          <th rowspan="2" class="center aligned middle aligned" ng-show="show_accounting">Sales NET of VAT & Service Charge</th>
          <th rowspan="2" class="center aligned middle aligned" ng-show="show_accounting">Service Charge</th>
          <th rowspan="2" class="center aligned middle aligned" ng-show="show_accounting">VATable Sales</th>
          <th rowspan="2" class="center aligned middle aligned" ng-show="show_accounting">Output VAT</th>
          <th rowspan="2" class="center aligned middle aligned" ng-show="show_accounting">Sales Inclusive of VAT</th>
          <!-- <th rowspan="2" class="center aligned middle aligned" ng-show="show_settlements">Excess</th> -->
        </tr>
        <tr>
          @foreach ($settlements as $settlement)
            <th class="center aligned middle aligned" ng-show="show_settlements">{{ settlements($settlement) }}</th>
          @endforeach
          <th class="center aligned middle aligned" ng-show="show_settlements">Cancelled / Void</th>
          <th class="center aligned middle aligned" ng-show="show_settlements">BOD Charge</th>
          <th class="center aligned middle aligned" ng-show="show_settlements">Staff Charge</th>
          <th class="center aligned middle aligned" ng-show="show_settlements">Total Settlements</th>
        </tr>
      </thead>
      <tbody ng-cloak>
        <tr ng-repeat="bill_data in bills">
          <td class="center aligned middle aligned">@{{bill_data.date_}}</td>
          <td class="center aligned middle aligned"><a href="/restaurant/bill/@{{bill_data.id}}" target="_blank"><p>@{{bill_data.check_number}}</p></a></td>
          <td class="center aligned middle aligned" ng-show="show_sales_information">@{{bill_data.restaurant_name}}</td>
          <td class="center aligned middle aligned" ng-show="show_sales_information">@{{bill_data.pax}}</td>
          <td class="center aligned middle aligned" ng-show="show_sales_information">@{{bill_data.server_name}}</td>
          <td class="center aligned middle aligned" ng-show="show_sales_information">@{{bill_data.cashier_name}}</td>
          <td class="center aligned middle aligned" ng-show="show_sales_information">@{{bill_data.guest_name}}</td>
          <td class="center aligned middle aligned" ng-show="show_sales_information">@{{bill_data.sc_pwd}}</td>
          @foreach ($categories as $category)
            <td class="right aligned middle aligned" ng-show="show_sales"> {{bill_data.<?php echo $category; ?> |currency:""}}</td>
          @endforeach
          <td class="right aligned middle aligned" ng-show="show_sales"> @{{bill_data.total_item_amount |currency:""}}</td>
          <td class="right aligned middle aligned" ng-show="show_sales"> @{{bill_data.special_trade_discount |currency:""}}</td>
          <td class="right aligned middle aligned" ng-show="show_sales"> @{{bill_data.net_total_amount |currency:""}}</td>
          @foreach ($settlements as $settlement)
            @if($settlement=="cash")
              <td class="right aligned middle aligned" ng-show="show_settlements"> {{bill_data.<?php echo $settlement; ?>-bill_data.excess |currency:""}}</td>
            @else
              <td class="right aligned middle aligned" ng-show="show_settlements"> {{bill_data.<?php echo $settlement; ?> |currency:""}}</td>
            @endif
          @endforeach
          <td class="right aligned middle aligned" ng-show="show_settlements">@{{bill_data.cancelled |currency:""}}</td>
          <td class="right aligned middle aligned" ng-show="show_settlements">@{{bill_data.bad_order |currency:""}}</td>
          <td class="right aligned middle aligned" ng-show="show_settlements">@{{bill_data.staff_charge |currency:""}}</td>
          <td class="right aligned middle aligned" ng-show="show_settlements">@{{bill_data.total_settlements|currency:""}}</td>
          <td class="right aligned middle aligned" ng-show="show_accounting">@{{bill_data.total_discount|currency:""}}</td>
          <td class="right aligned middle aligned" ng-show="show_accounting">@{{bill_data.gross_billing|currency:""}}</td>
          <td class="right aligned middle aligned" ng-show="show_accounting">@{{bill_data.sc_pwd_discount|currency:""}}</td>
          <td class="right aligned middle aligned" ng-show="show_accounting">@{{bill_data.sc_pwd_vat_exemption|currency:""}}</td>
          <td class="right aligned middle aligned" ng-show="show_accounting">@{{bill_data.net_billing|currency:""}}</td>
          <td class="right aligned middle aligned" ng-show="show_accounting">@{{bill_data.sales_net_of_vat_and_service_charge|currency:""}}</td>
          <td class="right aligned middle aligned" ng-show="show_accounting">@{{bill_data.service_charge|currency:""}}</td>
          <td class="right aligned middle aligned" ng-show="show_accounting">@{{bill_data.vatable_sales|currency:""}}</td>
          <td class="right aligned middle aligned" ng-show="show_accounting">@{{bill_data.output_vat|currency:""}}</td>
          <td class="right aligned middle aligned" ng-show="show_accounting">@{{bill_data.sales_inclusive_of_vat|currency:""}}</td>
          <!-- <td class="right aligned middle aligned" ng-show="show_settlements">@{{bill_data.excess |currency:""}}</td> -->
        </tr>
      </tbody>
      <tfoot ng-cloak>
        <tr ng-cloak>
          <th class="right aligned middle aligned" colspan="2">Total>>></th>
          <th class="center aligned middle aligned" ng-show="show_sales_information"></th>
          <th class="center aligned middle aligned" ng-show="show_sales_information">@{{footer.pax}}</th>
          <th class="center aligned middle aligned" ng-show="show_sales_information"></th>
          <th class="center aligned middle aligned" ng-show="show_sales_information"></th>
          <th class="center aligned middle aligned" ng-show="show_sales_information"></th>
          <th class="center aligned middle aligned" ng-show="show_sales_information">@{{footer.sc_pwd}}</th>
          @foreach ($categories as $category)
            <th class="right aligned middle aligned" ng-show="show_sales"> {{footer.<?php echo $category; ?> |currency:""}}</th>
          @endforeach
          <th class="right aligned middle aligned" ng-show="show_sales">@{{footer.total_item_amount|currency:""}}</th>
          <th class="right aligned middle aligned" ng-show="show_sales">@{{footer.special_trade_discount|currency:""}}</th>
          <th class="right aligned middle aligned" ng-show="show_sales">@{{footer.net_total_amount|currency:""}}</th>
          @foreach ($settlements as $settlement)
            <th class="right aligned middle aligned" ng-show="show_settlements"> {{footer.<?php echo $settlement; ?> |currency:""}}</th>
          @endforeach
          <th class="right aligned middle aligned" ng-show="show_settlements">@{{footer.cancelled|currency:""}}</th>
          <th class="right aligned middle aligned" ng-show="show_settlements">@{{footer.bad_order|currency:""}}</th>
          <th class="right aligned middle aligned" ng-show="show_settlements">@{{footer.staff_charge|currency:""}}</th>
          <th class="right aligned middle aligned" ng-show="show_settlements">@{{footer.total_settlements|currency:""}}</th>
          <th class="right aligned middle aligned" ng-show="show_accounting">@{{footer.total_discount|currency:""}}</th>
          <th class="right aligned middle aligned" ng-show="show_accounting">@{{footer.gross_billing|currency:""}}</th>
          <th class="right aligned middle aligned" ng-show="show_accounting">@{{footer.sc_pwd_discount|currency:""}}</th>
          <th class="right aligned middle aligned" ng-show="show_accounting">@{{footer.sc_pwd_vat_exemption|currency:""}}</th>
          <th class="right aligned middle aligned" ng-show="show_accounting">@{{footer.net_billing|currency:""}}</th>
          <th class="right aligned middle aligned" ng-show="show_accounting">@{{footer.sales_net_of_vat_and_service_charge|currency:""}}</th>
          <th class="right aligned middle aligned" ng-show="show_accounting">@{{footer.service_charge|currency:""}}</th>
          <th class="right aligned middle aligned" ng-show="show_accounting">@{{footer.vatable_sales|currency:""}}</th>
          <th class="right aligned middle aligned" ng-show="show_accounting">@{{footer.output_vat|currency:""}}</th>
          <th class="right aligned middle aligned" ng-show="show_accounting">@{{footer.sales_inclusive_of_vat|currency:""}}</th>
          <!-- <th class="right aligned middle aligned" ng-show="show_settlements">@{{footer.excess|currency:""}}</th> -->
        </tr>
      </tfoot>
    </table>
  </div>
  <div ng-bind-html="paging" class="text-center" ng-show="show_paging"></div>
</div>
@endsection

@section('modals')

@endsection

@section('scripts')
<script type="text/javascript">
  $(document).ready(function() {
    $("#date-from,#date-to").datepicker();
  });
  // $('.ui.checkbox').checkbox('enable');
  var app = angular.module('main', ['ngSanitize']);
  app.controller('content-controller', function($scope,$http, $sce) {
    $scope.show_sales = true;
    $scope.show_sales_information = true;
    $scope.show_paging = false;
    $scope.show_settlements = true;
    $scope.date_from = "{{date('m/d/Y',strtotime($date_from))}}";
    $scope.date_to = "{{date('m/d/Y',strtotime($date_to))}}";

    $('#date_from,#date_to').datepicker();


    @if(Session::get('users.user_data')->privilege!="restaurant_cashier")
      $scope.show_accounting = true;
    @else
      $scope.show_accounting = false;
    @endif
    
    $scope.toggle_paging = function() {
      show_reports();
    }
    show_reports();
    $(document).on("click",".paging",function(e) {
      show_reports($scopee.target.id);
    });
    $scope.filter_result = function(){
      $scope.submit = true;
      show_reports();
    }

    @if(Session::get('users.user_data')->privilege=="restaurant_cashier")

    @else
      $scope.restaurant_cashiers = {!! $restaurant_cashiers !!};
      $scope.restaurant_servers = {!! $restaurant_servers !!};
    @endif
    function show_reports(page=1) {
      var server = ($scope.server==undefined?"":$scope.server['id']);
      var cashier = ($scope.cashier==undefined?"":$scope.cashier['id']);
      $http({
          method : "GET",
          url : "/api/reports/general/f_and_b",
          params: {
            "date_from":$scope.date_from,
            "date_to":$scope.date_to,
            "paging":$scope.show_paging,
            "page":page,
            "display_per_page":50,
            "server_id": server,
            "cashier_id": cashier,
          }
      }).then(function mySuccess(response) {
          $scope.bills = response.data.result;
          $scope.footer = response.data.footer;
          $scope.paging = $sce.trustAsHtml(response.data.paging);
          $scope.submit = false;
          $.notify('Food and Beverage Revenue Report has been populated.','info');
      }, function myError(response) {
          $scope.submit = false;
          console.log(response.statusText);
      });
    }

  });
  angular.bootstrap(document, ['main']);
</script>
@endsection