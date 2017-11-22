@extends('layouts.main')

@section('title', 'Menu Popularity Report')

@section('css')
<style type="text/css">

</style>
@endsection
@section('breadcrumb')
<a class="section hideprint" href="/reports">Reports</a>
<i class="right angle icon divider"></i>
<div class="active section">@{{outlet}} Menu Popularity Report</div>
@endsection
@section('content')
 
<div class="col-sm-12">
  <div>
    <div class="checkbox">
      <!-- <label><input type="checkbox" ng-model="show_paging" ng-change="toggle_paging()">Paging</label> -->
    </div>
  </div>
  <h1 style="text-align: center;">@{{outlet}} Menu Popularity Report<br><small><b>Date From:</b> {{date("F d, Y",strtotime($date_from))}} <b>Date To:</b> {{date("F d, Y",strtotime($date_to))}} </small></h1>
  <label>Outlet:</label>
  <div class="ui action input form-group">
    <div ng-hide="hide_outlet">
      <select id="restaurant_id" placeholder="Outlet" class="form-control" ng-model="restaurant_id" ng-options="restaurant as restaurant.name for restaurant in restaurants track by restaurant.id" ng-change="change_outlet()">
        <option value="">All Outlet</option>
      </select>
    </div>
  </div>
  <button class="ui positive button" ng-click="export_reports()" ng-class="{'loading':export}">Download</button>

  <div class="table-responsive">
  <table class="ui unstackable striped celled structured table">
    <thead>
      <tr>
        <th class="center aligned middle aligned">Category</th>
        <th class="center aligned middle aligned">Menu</th>
        <th class="center aligned middle aligned">Served Quantity</th>
        <th class="center aligned middle aligned">Total Amount</th>
      </tr>
    </thead>
    <tbody>
      <tr ng-repeat="item in menu_popularity">
        <td class="center aligned middle aligned">@{{item.category}}</td>
        <td class="center aligned middle aligned">@{{item.name}}</td>
        <td class="center aligned middle aligned">@{{item.total_quantity}}</td>
        <td class="center aligned middle aligned">@{{item.price*item.total_quantity|currency:""}}</td>
      </tr>
    </tbody>
    <tfoot>

    </tfoot>
  </table>
  </div>
  <div ng-bind-html="pagination" class="text-center"></div>
</div>
@endsection

@section('modals')

@endsection

@section('scripts')
<script type="text/javascript">
  $(document).ready(function() {
  });
  var app = angular.module('main', ['ngSanitize']);
  app.controller('content-controller', function($scope,$http, $sce) {
    $scope.show_paging = true;
    $scope.restaurants = {!! json_encode($restaurants) !!};
    $scope.outlet = "";
    // $scope.restaurant_id = "";
    $(document).on('click','.gotopage',function(e) {
      show_reports(e.target.attributes.page.nodeValue);
    });
    $scope.export_reports = function(page=1) {
      $scope.export = true;
      var restaurant_id = ($scope.restaurant_id==undefined?"":$scope.restaurant_id['id']);
      $http({
          method : "GET",
          url : "/api/reports/general/menu_popularity_export",
          params: {
            "date_from":"{{$date_from}}",
            "date_to":"{{$date_to}}",
            "page":page,
            'export': '1',
            "restaurant_id":restaurant_id,
            "restaurant_name":$scope.outlet,
          }
      }).then(function mySuccess(response) {
        $scope.export = false;
        // console.log(response);
        window.location = response.data;
      }, function myError(response) {
          $scope.export = false;
          console.log(response.statusText);
      });
    }
    $scope.change_outlet = function() {
      show_reports();
    }
    show_reports();
    function show_reports(page=1) {
      var restaurant_id = ($scope.restaurant_id==undefined?"":$scope.restaurant_id['id']);
      $http({
          method : "GET",
          url : "/api/reports/general/menu_popularity",
          params: {
            "date_from":"{{$date_from}}",
            "date_to":"{{$date_to}}",
            "page":page,
            "restaurant_id":restaurant_id,
          }
      }).then(function mySuccess(response) {
          $scope.menu_popularity = response.data.result.data;
          $scope.footer = response.data.footer;
          $scope.pagination = $sce.trustAsHtml(response.data.pagination);
          var restaurant_name = ($scope.restaurant_id==undefined?"":$scope.restaurant_id['name']);
          $scope.outlet = restaurant_name;
      }, function myError(response) {
          console.log(response.statusText);
      });
    }
  });
  angular.bootstrap(document, ['main']);
</script>
@endsection