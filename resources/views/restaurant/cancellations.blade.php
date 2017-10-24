@extends('layouts.main')

@section('title', 'Restaurant Menu')

@section('css')
<style type="text/css">
@media (min-width: 768px){
  .modal-lg {
    width: 760px;
  }  
}
@media (min-width: 992px){
  .modal-lg {
    width: 990px;
  }  
}

</style>
@endsection
@section('breadcrumb')
<div class="active section">Cancellation Requests</div>
@endsection
@section('content')
<div class="col-sm-12">
  <h1 style="text-align: center;">Cancellation of Orders Requests</h1>
  <div class="table-responsive">
    <table class="ui unstackable sortable celled table">
      <thead>
        <tr>
          <th class="middle aligned center aligned">Order #</th>
          <th class="middle aligned center aligned">Outlet</th>
          <th class="middle aligned center aligned">Requested By</th>
          <th class="middle aligned center aligned">Cancellation Message</th>
          <th class="middle aligned center aligned">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr ng-repeat="item in cancellations">
          <td class="middle aligned center aligned" ng-bind="item.restaurant_order_id"></td>
          <td class="middle aligned center aligned" ng-bind="item.restaurant_name"></td>
          <td class="middle aligned center aligned" ng-bind="item.cancelled_by_name"></td>
          <td class="middle aligned center aligned" ng-bind="item.reason_cancelled"></td>
          <td class="middle aligned center aligned">
            <div class="ui buttons">
              <button class="ui positive button" ng-click="accept_request(this)">Accept</button>
              <div class="or"></div>
              <button class="ui negative button" ng-click="delete_request(this)">Delete</button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

@endsection

@section('modals')

@endsection

@section('scripts')
<script type="text/javascript">
  $('table').tablesort();

  // $('.ui.checkbox').checkbox('enable');
  var app = angular.module('main', []);
  app.controller('content-controller', function($scope,$http, $sce) {
    $scope.formdata = {};
    $scope.accept_request = function(data) {
      console.log(data);
      $scope.formdata.id = data.item.id;
      $http({
        method: 'POST',
        url: '/api/restaurant/orders/cancellations/accept/'+data.item.id,
        data: $.param($scope.formdata),
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
      })
      .then(function(response) {
        console.log(response.data);
        alertify.success('Cancellation Request has been accepted.');
        show_cancellation_request();
      }, function(rejection) {
        console.log(rejection);
        if(rejection.status == 500){
          error_505('Server Error, Try Refreshing the Page.');
        }else if(rejection.status == 422){
          var errors = rejection.data;
          angular.forEach(errors, function(value, key) {
              alertify.error(value[0]);
          });
        }
        $scope.submit = false;
      });
    }
    $scope.delete_request = function(data) {
      console.log(data);
      $scope.formdata.id = data.item.id;
      $http({
        method: 'POST',
        url: '/api/restaurant/orders/cancellations/delete/'+data.item.id,
        data: $.param($scope.formdata),
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
      })
      .then(function(response) {
        console.log(response.data);
        alertify.success('Cancellation Request has been deleted.');
        show_cancellation_request();
      }, function(rejection) {
        if(rejection.status == 500){
          error_505('Server Error, Try Refreshing the Page.');
        }else if(rejection.status == 422){
          var errors = rejection.data;
          angular.forEach(errors, function(value, key) {
              alertify.error(value[0]);
          });
        }
        $scope.submit = false;
      });
    }
    setInterval(function(){
      show_cancellation_request();
    }, 1000);
    function show_cancellation_request() {
      $http({
          method : "GET",
          @if(Session::get('users.user_data')->privilages=="admin")
          url : "/api/restaurant/orders/cancellations/show",
          @else
          url : "/api/restaurant/orders/cancellations/show/{{Session::get('users.user_data')->restaurant_id}}",
          @endif
      }).then(function mySuccess(response) {
        console.log(response.data.result);

        if(angular.equals($scope.cancellations, {})){
          $scope.cancellations = response.data.result;
        }else if(angular.equals($scope.cancellations, response.data.result)){

        }else{
          $scope.cancellations = response.data.result;
        }
      }, function myError(response) {
          console.log(response.statusText);
      });
    }
  });

  angular.bootstrap(document, ['main']);
</script>
@endsection