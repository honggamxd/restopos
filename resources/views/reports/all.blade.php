@extends('layouts.main')




@if(Session::get('users.user_data')->privilege=="admin")
  @section('title', 'Order Slip Summary Report')
@else
  @section('title', Session::get('users.user_data')->restaurant.' Order Slip Summary Report')
@endif


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

  @if(Session::get('users.user_data')->privilege=="admin")
    <h1 style="text-align: center;">Order Slip Summary Report<br><small><b>Date From:</b> @{{date_from_str}} <b>Date To:</b> @{{date_to_str}} </small></h1>
  @else
    <h1 style="text-align: center;"> {{Session::get('users.user_data')->restaurant}} Order Slip Summary Report<br><small><b>Date From:</b> @{{date_from_str}} <b>Date To:</b> @{{date_to_str}} </small></h1>
  @endif
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
    @if(Session::get('users.user_data')->privilege=="admin")
    <div class="checkbox">
      <label><input type="checkbox" ng-model="show_accounting">Show Accounting</label>
    </div>
    @endif
  </div>
  <div>
    @if(Session::get('users.user_data')->privilege=="restaurant_cashier")
    <button class="ui positive button" ng-click="export_reports()" ng-class="{'loading':export}">Download</button>
    @else
    <label>Filter By:</label>
    <form class="form-inline" style="margin-bottom: 20px;">
      @if(Session::get('users.user_data')->privilege=="admin")
      <div class="form-group">
      <label>Outlet:</label>
      <select class="form-control input-sm" ng-options="item as item.name for item in restaurants track by item.id" ng-model="restaurant">
        <option value="">All Outlets</option>
      </select>
      </div>
      @endif
      <div class="form-group">
        <label>Meal Type:</label>
        <select class="form-control input-sm" ng-model="meal_type">
          <option value="">All</option>
          <option value="Breakfast">Breakfast</option>
          <option value="Lunch">Lunch</option>
          <option value="PM Snacks">PM Snacks</option>
          <option value="Dinner">Dinner</option>
        </select>
      </div>
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
      <div class="ui buttons">
        <button class="ui primary button" ng-click="filter_result()" ng-class="{'loading':submit}" ng-disabled="submit" ng-submit="export">Filter Results</button>
        <button class="ui positive button" ng-click="export_reports()" ng-class="{'loading':export}" ng-disabled="submit" ng-submit="export">Download</button>
      </div>
      </div>
    </form>
    @endif
  </div>
  
  <div class="table-responsive">
    <table class="ui unstackable celled structured table">
      <thead>
        <tr>
          <th rowspan="2" class="center aligned middle aligned">Date</th>
          <th rowspan="2" class="center aligned middle aligned" ng-show="show_sales_information">Outlet</th>
          <th rowspan="2" class="center aligned middle aligned">Check #</th>
          <th rowspan="2" class="center aligned middle aligned" ng-show="show_sales_information">Invoice #</th>
          <th rowspan="2" class="center aligned middle aligned" ng-show="show_sales_information">Guest Name</th>
          <th rowspan="2" class="center aligned middle aligned" ng-show="show_sales_information"># of Pax</th>
          <th rowspan="2" class="center aligned middle aligned" ng-show="show_sales_information"># of SC/PWD</th>
          <th rowspan="2" class="center aligned middle aligned" ng-show="show_sales_information">Server</th>
          <th rowspan="2" class="center aligned middle aligned" ng-show="show_sales_information">Cashier</th>
          @foreach ($categories as $category)
            <th rowspan="2" class="center aligned middle aligned" ng-show="show_sales">{{$category}}</th>
          @endforeach
          <th rowspan="2" class="center aligned middle aligned" ng-show="show_sales">Gross Amount</th>
          <th rowspan="2" class="center aligned middle aligned" ng-show="show_sales">Total Discount</th>
          <th rowspan="2" class="center aligned middle aligned" ng-show="show_sales">NET Amount</th>
          <th class="center aligned middle aligned" ng-show="show_settlements" colspan="{{ count($settlements)+1 }}">Mode of Payments / Settlements</th>
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
          <th class="center aligned middle aligned" ng-show="show_settlements">Total Settlements</th>
        </tr>
      </thead>
      <tbody>
        <tr ng-if="bills | isEmpty">
          <th colspan="200" style="text-align: center;">
            <h1 ng-if="submit">
              <img src="{{asset('assets/images/loading.gif')}}" style="height: 70px;">
              <br>
              LOADING
            </h1>
            <h1 ng-cloak>
              <span ng-if="!submit">NO DATA</span>
              <span ng-if="submit"></span>
            </h1>
          </th>
        </tr>
        <tr ng-repeat="bill_data in bills" ng-class="{'warning':bill_data.type=='bad_order'}" ng-cloak>
          <td class="center aligned middle aligned">@{{bill_data.date_}}</td>
          <td class="center aligned middle aligned" ng-show="show_sales_information">@{{bill_data.restaurant_name}}</td>
          <td class="center aligned middle aligned"><a href="/restaurant/bill/@{{bill_data.id}}" target="_blank">
            <p ng-if="bill_data.type=='good_order'">@{{bill_data.check_number}}</p>
            <p ng-if="bill_data.type=='bad_order'" title="@{{bill_data.reason_cancelled}}">@{{bill_data.check_number}}</p>
          </a></td>
          <td class="center aligned middle aligned" ng-show="show_sales_information">@{{bill_data.invoice_number}}</td>
          <td class="center aligned middle aligned" ng-show="show_sales_information">@{{bill_data.guest_name}}</td>
          <td class="center aligned middle aligned" ng-show="show_sales_information">@{{bill_data.pax}}</td>
          <td class="center aligned middle aligned" ng-show="show_sales_information">@{{bill_data.sc_pwd}}</td>
          <td class="center aligned middle aligned" ng-show="show_sales_information">@{{bill_data.server_name}}</td>
          <td class="center aligned middle aligned" ng-show="show_sales_information">@{{bill_data.cashier_name}}</td>
          @foreach ($categories as $category)
            <td class="right aligned middle aligned" ng-show="show_sales"> {{bill_data.<?php echo $category; ?> |chkNull|currency:""}}</td>
          @endforeach
          <td class="right aligned middle aligned" ng-show="show_sales"> @{{bill_data.total_item_amount |chkNull|currency:""}}</td>
          <td class="right aligned middle aligned" ng-show="show_sales"> @{{bill_data.special_trade_discount |chkNull|currency:""}}</td>
          <td class="right aligned middle aligned" ng-show="show_sales"> @{{bill_data.net_total_amount |chkNull|currency:""}}</td>
          @foreach ($settlements as $settlement)
            @if($settlement=="cash")
              <td class="right aligned middle aligned" ng-show="show_settlements"> {{bill_data.<?php echo $settlement; ?>-bill_data.excess |chkNull|currency:""}}</td>
            @elseif($settlement=="cancelled")
              <td class="right aligned middle aligned" ng-show="show_settlements"> {{bill_data.<?php echo $settlement; ?> |chkNull|currency:""}}</td>
            @else
              <td class="right aligned middle aligned" ng-show="show_settlements"> {{bill_data.<?php echo $settlement; ?> |chkNull|currency:""}}</td>
            @endif
          @endforeach
          <td class="right aligned middle aligned" ng-show="show_settlements">@{{bill_data.total_settlements |chkNull|currency:""}}</td>
          <td class="right aligned middle aligned" ng-show="show_accounting">@{{bill_data.total_discount |chkNull|currency:""}}</td>
          <td class="right aligned middle aligned" ng-show="show_accounting">@{{bill_data.gross_billing |chkNull|currency:""}}</td>
          <td class="right aligned middle aligned" ng-show="show_accounting">@{{bill_data.sc_pwd_discount |chkNull|currency:""}}</td>
          <td class="right aligned middle aligned" ng-show="show_accounting">@{{bill_data.sc_pwd_vat_exemption |chkNull|currency:""}}</td>
          <td class="right aligned middle aligned" ng-show="show_accounting">@{{bill_data.net_billing |chkNull|currency:""}}</td>
          <td class="right aligned middle aligned" ng-show="show_accounting">@{{bill_data.sales_net_of_vat_and_service_charge |chkNull|currency:""}}</td>
          <td class="right aligned middle aligned" ng-show="show_accounting">@{{bill_data.service_charge |chkNull|currency:""}}</td>
          <td class="right aligned middle aligned" ng-show="show_accounting">@{{bill_data.vatable_sales |chkNull|currency:""}}</td>
          <td class="right aligned middle aligned" ng-show="show_accounting">@{{bill_data.output_vat |chkNull|currency:""}}</td>
          <td class="right aligned middle aligned" ng-show="show_accounting">@{{bill_data.sales_inclusive_of_vat |chkNull|currency:""}}</td>
          <!-- <td class="right aligned middle aligned" ng-show="show_settlements">@{{bill_data.excess |currency:""}}</td> -->
        </tr>
      </tbody>
      <tfoot ng-cloak ng-hide="bills | isEmpty">
        <tr ng-cloak>
          <th class="right aligned middle aligned" colspan="2">Total>>></th>
          <th class="center aligned middle aligned"></th>
          <th class="center aligned middle aligned"></th>
          <th class="center aligned middle aligned"></th>
          <th class="center aligned middle aligned" ng-show="show_sales_information">@{{footer.pax}}</th>
          <th class="center aligned middle aligned" ng-show="show_sales_information">@{{footer.sc_pwd}}</th>
          <th class="center aligned middle aligned" ng-show="show_sales_information"></th>
          <th class="center aligned middle aligned" ng-show="show_sales_information"></th>
          @foreach ($categories as $category)
            <th class="right aligned middle aligned" ng-show="show_sales"> {{footer.<?php echo $category; ?> |chkNull|currency:""}}</th>
          @endforeach
          <th class="right aligned middle aligned" ng-show="show_sales">@{{footer.total_item_amount|chkNull|currency:""}}</th>
          <th class="right aligned middle aligned" ng-show="show_sales">@{{footer.special_trade_discount|chkNull|currency:""}}</th>
          <th class="right aligned middle aligned" ng-show="show_sales">@{{footer.net_total_amount|chkNull|currency:""}}</th>
          @foreach ($settlements as $settlement)
            @if($settlement=="cancelled")
            <th class="right aligned middle aligned warning" ng-show="show_settlements"> {{footer.<?php echo $settlement; ?> |chkNull|currency:""}}</th>
            @else
            <th class="right aligned middle aligned" ng-show="show_settlements"> {{footer.<?php echo $settlement; ?> |chkNull|currency:""}}</th>
            @endif
          @endforeach
          <th class="right aligned middle aligned" ng-show="show_settlements">@{{footer.total_settlements|chkNull|currency:""}}</th>
          <th class="right aligned middle aligned" ng-show="show_accounting">@{{footer.total_discount|chkNull|currency:""}}</th>
          <th class="right aligned middle aligned" ng-show="show_accounting">@{{footer.gross_billing|chkNull|currency:""}}</th>
          <th class="right aligned middle aligned" ng-show="show_accounting">@{{footer.sc_pwd_discount|chkNull|currency:""}}</th>
          <th class="right aligned middle aligned" ng-show="show_accounting">@{{footer.sc_pwd_vat_exemption|chkNull|currency:""}}</th>
          <th class="right aligned middle aligned" ng-show="show_accounting">@{{footer.net_billing|chkNull|currency:""}}</th>
          <th class="right aligned middle aligned" ng-show="show_accounting">@{{footer.sales_net_of_vat_and_service_charge|chkNull|currency:""}}</th>
          <th class="right aligned middle aligned" ng-show="show_accounting">@{{footer.service_charge|chkNull|currency:""}}</th>
          <th class="right aligned middle aligned" ng-show="show_accounting">@{{footer.vatable_sales|chkNull|currency:""}}</th>
          <th class="right aligned middle aligned" ng-show="show_accounting">@{{footer.output_vat|chkNull|currency:""}}</th>
          <th class="right aligned middle aligned" ng-show="show_accounting">@{{footer.sales_inclusive_of_vat|chkNull|currency:""}}</th>
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
    $scope.date_from = "{{date('m/d/Y h:i:s A',strtotime($date_from))}}";
    $scope.date_to = "{{date('m/d/Y h:i:s A',strtotime($date_to))}}";
    $scope.date_from_str = "";
    $scope.date_to_str = "";
    $scope.restaurants = {!! $restaurants !!};

    $('#date_from,#date_to').datetimepicker({
      timeFormat: "hh:mm:ss tt"
    });


    @if(Session::get('users.user_data')->privilege!="admin")
      $scope.show_accounting = false;
    @else
      $scope.show_accounting = true;
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
      $scope.submit = true;
      var server = ($scope.server==undefined?"":$scope.server['id']);
      var cashier = ($scope.cashier==undefined?"":$scope.cashier['id']);
      var restaurant = ($scope.restaurant==undefined?"":$scope.restaurant['id']);
      $scope.bills = {};
      $scope.footer = {};
      $scope.paging = "";
      $http({
          method : "GET",
          url : "/api/reports/general/f_and_b",
          params: {
            "date_from":$scope.date_from,
            "date_to":$scope.date_to,
            "paging":$scope.show_paging,
            "meal_type": $scope.meal_type,
            "server_id": server,
            "cashier_id": cashier,
            "restaurant_id": restaurant,
          }
      }).then(function mySuccess(response) {
          $scope.bills = response.data.result;
          $scope.footer = response.data.footer;
          $scope.paging = $sce.trustAsHtml(response.data.paging);
          $scope.submit = false;
          $scope.date_from_str = moment($scope.date_from).format("MMMM DD, YYYY");
          $scope.date_to_str = moment($scope.date_to).format("MMMM DD, YYYY");
          $.notify('Order Slip Summary Report from '+$scope.date_from_str+' to '+$scope.date_to_str+' has been populated.','info');
      }, function myError(rejection) {
          if(rejection.status != 422){
            request_error(rejection.status);
          }else if(rejection.status == 422){
            var errors = rejection.data;
          }
          $scope.submit = false;
      });
    }

    $scope.export_reports = function(page=1) {
      $scope.export = true;
      var server = ($scope.server==undefined?"":$scope.server['id']);
      var cashier = ($scope.cashier==undefined?"":$scope.cashier['id']);
      var restaurant = ($scope.restaurant==undefined?"":$scope.restaurant['id']);
      $http({
          method : "GET",
          url : "/api/reports/general/f_and_b_export",
          params: {
            "date_from":$scope.date_from,
            "date_to":$scope.date_to,
            "meal_type": $scope.meal_type,
            "server_id": server,
            "cashier_id": cashier,
            "restaurant_id": restaurant,

          }
      }).then(function mySuccess(response) {
        $scope.export = false;
        // console.log(response);
        window.location = response.data;
      }, function myError(rejection) {
          if(rejection.status != 422){
            request_error(rejection.status);
          }else if(rejection.status == 422){
            var errors = rejection.data;
          }
          $scope.export = false;
      });
    }

  });

  app.filter('chkNull',function(){
      return function(input){
          if(!(angular.equals(input,null)))
              return input;
          else
              return 0;
      };
  });
  app.filter('isEmpty', function () {
      var bar;
      return function (obj) {
          for (bar in obj) {
              if (obj.hasOwnProperty(bar)) {
                  return false;
              }
          }
          return true;
      };
  });
  angular.bootstrap(document, ['main']);
</script>
@endsection