@extends('layouts.main')




@if(Session::get('users.user_data')->privilege=="admin")
  @section('title', 'Food Orders')
@else
  @section('title', Session::get('users.user_data')->restaurant.' Food Orders')
@endif


@section('css')
<style type="text/css">

</style>
@endsection
@section('breadcrumb')


@if(Session::get('users.user_data')->privilege=="admin")
  <a class="section hideprint" href="/reports">Reports</a>
  <i class="right angle icon divider"></i>
  <div class="active section">Food Orders</div>
@else
  <div class="active section">Food Orders</div>
@endif
@endsection
@section('content')
 
<div class="col-sm-12">

  @if(Session::get('users.user_data')->privilege=="admin")
    <h1 style="text-align: center;">Food Orders<br><small><b>Date From:</b> @{{date_from_str}} <b>Date To:</b> @{{date_to_str}} </small></h1>
  @else
    <h1 style="text-align: center;"> {{Session::get('users.user_data')->restaurant}} Food Orders<br><small><b>Date From:</b> @{{date_from_str}} <b>Date To:</b> @{{date_to_str}} </small></h1>
  @endif
  <div>
    @if(Session::get('users.user_data')->privilege=="restaurant_cashier")
    <!-- <button class="ui positive button" ng-click="export_reports()" ng-class="{'loading':export}">Download</button> -->
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
      <label>Date From:</label>
      <input type="text" class="form-control input-sm" id="date_from" ng-model="date_from" readonly>
      </div>
      <div class="form-group">
      <label>Date To:</label>
      <input type="text" class="form-control input-sm" id="date_to" ng-model="date_to" readonly>
      <div class="ui buttons">
        <button class="ui primary button" ng-click="filter_result()" ng-class="{'loading':submit}">Filter Results</button>
        <!-- <button class="ui positive button" ng-click="export_reports()" ng-class="{'loading':export}">Download</button> -->
      </div>
      </div>
    </form>
    @endif
  </div>
  
  <div class="table-responsive">
    <table class="ui unstackable celled structured table">
      <thead>
        <tr>
          <th rowspan="2" class="center aligned middle aligned">FOOD ORDER #</th>
          <th rowspan="2" class="center aligned middle aligned">DATE</th>
          <th rowspan="2" class="center aligned middle aligned">TIME</th>
          <th rowspan="2" class="center aligned middle aligned">SERVER</th>
          <th rowspan="2" class="right aligned middle aligned">TOTAL</th>
        </tr>
      </thead>
      <tbody ng-cloak>
        <tr ng-repeat="order_data in orders" ng-class="{'warning':order_data.type=='bad_order'}">
          <td class="center aligned middle aligned"><a href="/restaurant/order/@{{order_data.id}}">@{{order_data.que_number}}</a></td>
          <td class="center aligned middle aligned">@{{order_data.date_}}</td>
          <td class="center aligned middle aligned">@{{order_data.date_time}}</td>
          <td class="center aligned middle aligned">@{{order_data.server_name}}</td>
          <td class="right aligned middle aligned">@{{order_data.total|currency:""}}</td>
        </tr>
      </tbody>
    </table>
  </div>
  <div ng-bind-html="paging" class="text-center"></div>
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
    $scope.date_from = "{{date('m/d/Y',strtotime($date_from))}}";
    $scope.date_to = "{{date('m/d/Y',strtotime($date_to))}}";
    $scope.date_from_str = "";
    $scope.date_to_str = "";
    @if(Session::get('users.user_data')->privilege=="restaurant_cashier")

    @else
      $scope.restaurant_cashiers = {!! $restaurant_cashiers !!};
      $scope.restaurant_servers = {!! $restaurant_servers !!};
    @endif
    $('#date_from,#date_to').datepicker();
    $(document).on('click','.gotopage',function(e) {
      show_reports(e.target.attributes.page.nodeValue);
    });
    $scope.filter_result = function(){
      $scope.submit = true;
      show_reports();
    }
    show_reports();
    function show_reports(page=1) {
      var server = ($scope.server==undefined?"":$scope.server['id']);
      var cashier = ($scope.cashier==undefined?"":$scope.cashier['id']);
      $http({
          method : "GET",
          url : "/api/reports/general/orders",
          params: {
            "date_from":$scope.date_from,
            "date_to":$scope.date_to,
            "page":page,
            "server_id":server
          }
      }).then(function mySuccess(response) {
          $scope.orders = response.data.result.data;
          $scope.footer = response.data.footer;
          $scope.paging = $sce.trustAsHtml(response.data.pagination);
          $scope.date_from_str = moment($scope.date_from).format("MMMM DD, YYYY");
          $scope.date_to_str = moment($scope.date_to).format("MMMM DD, YYYY");
          if($scope.submit){
            $.notify('Food Orders from '+$scope.date_from_str+' to '+$scope.date_to_str+' has been populated.','info');
          }
          $scope.submit = false;
      }, function myError(response) {
          $scope.submit = false;
          console.log(response.statusText);
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
  angular.bootstrap(document, ['main']);
</script>
@endsection