@extends('layouts.main')

@section('title', '')

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
<div class="active section">Users</div>
@endsection
@section('content')
<div class="col-sm-12">
  <div class="form-group">
    <button class="ui primary button" onclick="$('#add-user-modal').modal('show')">Add User</button>
  </div>
  <div class="table-responsive">
    <table class="ui unstackable celled table" id="customer-table">
      <thead>
        <tr>
          <th class="center aligned">Name</th>
          <th class="center aligned">Username</th>
          <th class="center aligned">Privilege</th>
          <th class="center aligned">Outlet</th>
        </tr>
      </thead>
      <tbody>
        <tr ng-repeat="user in users">
          <td class="center aligned">@{{user.name}}</td>
          <td class="center aligned">@{{user.username}}</td>
          <td class="center aligned">@{{user.privilege}}</td>
          <td class="center aligned">@{{user.restaurant_name}}</td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
@endsection

@section('modals')


<div id="add-user-modal" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add User</h4>
      </div>
      <div class="modal-body">
        <form id="add-user-form" ng-submit="add_user()">
        {{ csrf_field() }}

        <div class="form-group">
          <label>Privilege:</label>
          <select name="restaurant_id" placeholder="Outlet" class="form-control" ng-model="formdata.privilege" ng-init="formdata.privilege='restaurant_cashier'">
            <option value="restaurant_cashier">Restaurant Cashier</option>
            <option value="admin">Admin</option>
          </select>
          <p class="help-block">@{{formerrors.privilege[0]}}</p>
        </div>


        <div class="form-group" ng-hide="formdata.privilege=='admin'">
          <label>Outlet:</label>
          <select name="restaurant_id" placeholder="Outlet" class="form-control" ng-model="formdata.restaurant_id">
            <option value="">Select Outlet</option>
            @foreach($restaurants as $restaurant)
              <option value="{{$restaurant->id}}">{{$restaurant->name}}</option>
            @endforeach
          </select>
          <p class="help-block">@{{formerrors.restaurant_id[0]}}</p>
        </div>

        <div class="form-group">
          <label>Name</label>
          <input class="form-control" type="text" placeholder="Enter Name" name="pax" ng-model="formdata.name">
          <p class="help-block">@{{formerrors.name[0]}}</p>
        </div>


        <div class="form-group">
          <label>Username</label>
          <input class="form-control" type="text" placeholder="Enter Username" name="pax" ng-model="formdata.username">
          <p class="help-block">@{{formerrors.username[0]}}</p>
        </div>

        <div class="form-group">
          <label>Password</label>
          <input class="form-control" type="text" placeholder="Enter Password" name="pax" ng-model="formdata.password">
          <p class="help-block">@{{formerrors.password[0]}}</p>
        </div>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" form="add-user-form" ng-disabled="submit">Save</button>
      </div>
    </div>

  </div>
</div>


@endsection

@section('scripts')
<script type="text/javascript">
  var app = angular.module('main', []);
  app.controller('content-controller', function($scope,$http, $sce, $window) {
    show_users();
    function show_users() {
      $http({
          method : "GET",
          url : "/api/users",
      }).then(function mySuccess(response) {
          $scope.users = response.data.result;
      }, function myError(response) {
          console.log(response.statusText);
      });
    }

    $scope.add_user = function(){

      $scope.formerrors = {};
      $scope.submit = true;
      $http({
         method: 'POST',
         url: '/api/users/add',
         data: $.param($scope.formdata),
         headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
      })
      .then(function(response) {
        console.log(response.data)
         show_users();
         $scope.submit = false;
         $("#add-user-modal").modal("hide");
         $('#add-user-form')[0].reset();
      }, function(rejection) {
         var errors = rejection.data;
         // console.log(errors.server_id);
         $scope.formerrors = errors;
         $scope.submit = false;
      });
      
    }
  });

  app.directive('focusMe', ['$timeout', '$parse', function ($timeout, $parse) {
      return {
          link: function (scope, element, attrs) {
              var model = $parse(attrs.focusMe);
              scope.$watch(model, function (value) {
                  if (value === true) {
                      $timeout(function () {
                          element[0].focus();
                      });
                  }
              });
              element.bind('blur', function () {
                  scope.$apply(model.assign(scope, false));
              });
          }
      };
  }]);
  angular.bootstrap(document, ['main']);
</script>
@endsection