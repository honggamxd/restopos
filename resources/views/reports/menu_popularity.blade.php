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
  <form class="form-inline">
    <div class="form-group">
      <label>Outlet:</label>
      <div class="ui action input form-group">
        <div ng-hide="hide_outlet">
          <select id="restaurant_id" placeholder="Outlet" class="form-control" ng-model="restaurant_id" ng-options="restaurant as restaurant.name for restaurant in restaurants track by restaurant.id" ng-change="change_outlet()">
            <option value="">All Outlet</option>
          </select>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label>Category</label>
      <select class="form-control" ng-change="category_change_menu_list(this)" ng-model="select_category" ng-init="select_category=''">
        <option value="">All Categories</option>
        @foreach($categories as $category)
        <option value="{{$category}}">{{$category}}</option>
        @endforeach
      </select>
    </div>
    <div class="form-group">
      <label>Subcategory</label>
      <select class="form-control" ng-change="subcategory_change_menu_list(this)" ng-model="select_subcategory" ng-options="item for item in subcategories">
        <option value="">All Subcategories</option>
      </select>
    </div>
    <div class="form-group">
      <div class="ui buttons">
        <button class="ui primary button" ng-click="filter_report()" ng-class="{'loading':submit}">Filter Result</button>
        <button class="ui positive button" ng-click="export_reports()" ng-class="{'loading':export}">Download</button>
      </div>
    </div>
  </form>
  <br>

  <div class="table-responsive">
  <table class="ui unstackable striped celled structured table">
    <thead>
      <tr>
        <th class="center aligned middle aligned">Category</th>
        <th class="center aligned middle aligned">Subcategory</th>
        <th class="center aligned middle aligned">Menu</th>
        <th class="center aligned middle aligned">Served Quantity</th>
        <th class="center aligned middle aligned">Total Amount</th>
      </tr>
    </thead>
    <tbody>
      <tr ng-repeat="item in menu_popularity">
        <td class="center aligned middle aligned">@{{item.category}}</td>
        <td class="center aligned middle aligned">@{{item.subcategory}}</td>
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

@push('scripts')
<script type="text/javascript">
  $(document).ready(function() {
  });
  var app = angular.module('main', ['ngSanitize']);
  app.controller('content-controller', function($scope,$http, $sce) {
    $scope.show_paging = true;
    $scope.restaurants = {!! json_encode($restaurants) !!};
    $scope.outlet = "";
    $(document).on('click','.pagination li a',function(e) {
      e.preventDefault();
      show_reports(e.target.href);
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
            "category": $scope.select_category,
            "subcategory": $scope.select_subcategory,
            "restaurant_name":$scope.outlet,
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

    $scope.filter_report = function() {
      show_reports();
    }

    $scope.category_change_menu_list = function() {
      var restaurant_id = ($scope.restaurant_id==undefined?"":$scope.restaurant_id['id']);
      $http({
          method : "GET",
          url : "/api/restaurant/menu/subcategory",
          params: {
            category: $scope.select_category,
            restaurant_id: restaurant_id,
          },
      }).then(function mySuccess(response) {
          $scope.subcategories = response.data;
      }, function(rejection) {
        if(rejection.status != 422){
          request_error(rejection.status);
        }else if(rejection.status == 422){ 
          console.log(rejection.statusText);
        }
      });
    }

    $scope.change_outlet = function() {
      $scope.select_category = null;
      $scope.select_subcategory = null;
      $scope.subcategories = {};
    }

    show_reports();
    function show_reports(myUrl) {
      myUrl = (typeof myUrl !== 'undefined') && myUrl !== "" ? myUrl : '/api/reports/general/menu_popularity';
      var restaurant_id = ($scope.restaurant_id==undefined?"":$scope.restaurant_id['id']);
      $http({
          method : "GET",
          url : myUrl,
          params: {
            "date_from":"{{$date_from}}",
            "date_to":"{{$date_to}}",
            "restaurant_id":restaurant_id,
            "category": $scope.select_category,
            "subcategory": $scope.select_subcategory,
          }
      }).then(function mySuccess(response) {
          $scope.menu_popularity = response.data.result.data;
          $scope.footer = response.data.footer;
          $scope.pagination = $sce.trustAsHtml(response.data.pagination);
          var restaurant_name = ($scope.restaurant_id==undefined?"":$scope.restaurant_id['name']);
          $scope.outlet = restaurant_name;
      }, function myError(rejection) {
          if(rejection.status != 422){
            request_error(rejection.status);
          }else if(rejection.status == 422){
            var errors = rejection.data;
          }
      });
    }
  });
  angular.bootstrap(document, ['main']);
</script>
@endpush