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
          <th class="middle aligned center aligned">#</th>
          <th class="middle aligned center aligned">Order #</th>
          <th class="middle aligned center aligned">Outlet</th>
          <th class="middle aligned center aligned">Requested By</th>
          <th class="middle aligned center aligned">Cancellation Message</th>
          <th class="middle aligned center aligned">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr ng-repeat="item in cancellations" ng-cloak>
          <td class="middle aligned center aligned" ng-bind="item.id"></td>
          <td class="middle aligned center aligned" ng-bind="item.restaurant_order_id"></td>
          <td class="middle aligned center aligned" ng-bind="item.restaurant_name"></td>
          <td class="middle aligned center aligned" ng-bind="item.cancelled_by_name"></td>
          <td class="middle aligned center aligned" ng-bind="item.reason_cancelled"></td>
          <td class="middle aligned center aligned">
            <div class="ui buttons">
              <button class="ui primary button" ng-click="view_request(this)">View</button>
              <button class="ui positive button" ng-click="confirm_accept_request(this)">Accept</button>
              <button class="ui negative button" ng-click="confirm_delete_request(this)">Delete</button>
            </div>
          </td>
        </tr>
      </tbody>
      <tfoot>
        <tr ng-if="cancellations | isEmpty">
          <td colspan="20" style="text-align: center;">
            <h1 ng-if="loading">
              <img src="{{asset('assets/images/loading.gif')}}" style="height: 70px;">
              <br>
              LOADING
            </h1>
            <h1>
              <span ng-if="!loading" ng-cloak>NO DATA</span>
              <span ng-if="loading" ng-cloak></span>
            </h1>
          </td>
        </tr>
      </tfoot>
    </table>
  </div>
</div>

@endsection

@section('modals')
<div id="view-request-modal" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Cancellation Request #: @{{request_data.id}}</h4>
      </div>
      <div class="modal-body">
        <table class="ui unstackable sortable celled table">
          <thead>
            <tr>
              <th class="center aligned middle aligned">Outlet</th>
              <th class="center aligned middle aligned">Item</th>
              <th class="center aligned middle aligned">Quantity to Cancel</th>
              <th class="right aligned middle aligned">Price</th>
              <th class="right aligned middle aligned">Total</th>
            </tr>
          </thead>
          <tbody>
            <tr ng-repeat="item in request_items">
              <td class="center aligned middle aligned">@{{request_data.restaurant_name}}</td>
              <td class="center aligned middle aligned">@{{item.menu_name}}</td>
              <td class="center aligned middle aligned">@{{item.quantity}}</td>
              <td class="right aligned middle aligned">@{{item.price|currency:""}}</td>
              <td class="right aligned middle aligned">@{{item.price*item.quantity|currency:""}}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div id="accept-request-modal" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Cancellation Request #: @{{request_data.id}}</h4>
      </div>
      <div class="modal-body">
        Accept this cancellation request?
      </div>
      <div class="modal-footer">
        <button type="button" class="ui button negative" data-dismiss="modal">Cancel</button>
        <button type="button" class="ui button positive" ng-click="accept_request()" ng-disabled="submit" ng-class="{'loading':submit}">Accept</button>
      </div>
    </div>
  </div>
</div>
<div id="delete-request-modal" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Cancellation Request #: @{{request_data.id}}</h4>
      </div>
      <div class="modal-body">
        Delete this cancellation request?
      </div>
      <div class="modal-footer">
        <button type="button" class="ui button negative" data-dismiss="modal">Cancel</button>
        <button type="button" class="ui button positive" ng-click="delete_request()" ng-disabled="submit" ng-class="{'loading':submit}">Delete</button>
      </div>
    </div>
  </div>
</div>



@endsection

@section('scripts')
<script type="text/javascript">
  $('table').tablesort();

  // $('.ui.checkbox').checkbox('enable');
  var app = angular.module('main', []);
  app.controller('content-controller', function($scope,$http, $sce) {
    $scope.formdata = {};
    $scope.request_data = {};
    $scope.request_items = {};
    $scope.loading = true;
    $scope.submit = false;
    $scope.view_request = function(data) {
      $scope.request_data = {};
      $scope.request_items = {};
      $http({
          method : "GET",
          url : "/api/restaurant/orders/cancellations/view/"+data.item.id,
      }).then(function mySuccess(response) {
        console.log(response);
        $scope.loading = false;
        $scope.request_data = response.data.request_data;
        $scope.request_items = response.data.request_items;
        $('#view-request-modal').modal('show');
      }, function(rejection) {
        if(rejection.status != 422){
          request_error(rejection.status);
        }else if(rejection.status == 422){
          console.log(rejection.statusText);
        }
        $scope.loading = false;
      });
      
    }

    $scope.confirm_accept_request = function(data) {
      $('#accept-request-modal').modal('show');
      $scope.formdata = {};
      $scope.formdata = data.item;
    }
    $scope.confirm_delete_request = function(data) {
      $('#delete-request-modal').modal('show');
      $scope.formdata = {};
      $scope.formdata = data.item;
    }
    $scope.accept_request = function(data) { 
      $scope.submit = true;
      $http({
        method: 'POST',
        url: '/api/restaurant/orders/cancellations/accept/'+$scope.formdata.id,
        data: $.param($scope.formdata),
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
      })
      .then(function(response) {
        console.log(response.data);
        $scope.submit = false;
        $('#accept-request-modal').modal('hide');
        $.notify('The Cancellation Request has been accepted.');
        show_cancellation_request();
      }, function(rejection) {
        $scope.submit = false;
        console.log(rejection);
        if(rejection.status != 422){
          request_error(rejection.status);
        }else if(rejection.status == 422){
          var errors = rejection.data;
          angular.forEach(errors, function(value, key) {
              $.notify(value[0],'error');
          });
        }
        $scope.submit = false;
      });
      
      
    }
    $scope.delete_request = function(data) {
      $scope.submit = true;
      $http({
        method: 'POST',
        url: '/api/restaurant/orders/cancellations/delete/'+$scope.formdata.id,
        data: $.param($scope.formdata),
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
      })
      .then(function(response) {
        $('#delete-request-modal').modal('hide');
        $.notify('The Cancellation Request has been deleted.','info');
        show_cancellation_request();
        $scope.submit = false;
      }, function(rejection) {
        if(rejection.status != 422){
          request_error(rejection.status);
        }else if(rejection.status == 422){
          var errors = rejection.data;
          angular.forEach(errors, function(value, key) {
            $.notify(value[0],'error');
          });
        }
        $scope.submit = false;
      });
      
    }
    function show_cancellation_request() {
      $http({
          method : "GET",
          @if(Session::get('users.user_data')->privilages=="admin")
          url : "/api/restaurant/orders/cancellations/show",
          @else
          url : "/api/restaurant/orders/cancellations/show/{{Session::get('users.user_data')->restaurant_id}}",
          @endif
      }).then(function mySuccess(response) {
        $scope.loading = false;
        console.log(response.data.result);

        if(angular.equals($scope.cancellations, {})){
          $scope.cancellations = response.data.result;
        }else if(angular.equals($scope.cancellations, response.data.result)){

        }else{
          $scope.cancellations = response.data.result;
        }

        if (!$('.modal').is(':visible')) {
          setTimeout(show_cancellation_request, 1500);
        }
      }, function myError(rejection) {
          $scope.loading = false;
          if(rejection.status != 422){
            request_error(rejection.status);
            clearInterval(refreshIntervalId);
          }else if(rejection.status == 422){
            var errors = rejection.data;
          }
          if (!$('.modal').is(':visible')) {
            setTimeout(show_cancellation_request, 1500);
          }
      });
    }
    show_cancellation_request();
    $('.modal').on('hidden.bs.modal', function() {
      show_cancellation_request();
    });
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