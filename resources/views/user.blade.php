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
  <table class="ui unstackable celled table" id="customer-table">
    <thead>
      <tr>
        <th class="center aligned">Name</th>
        <th class="center aligned">Username</th>
        <th class="center aligned">Privilege</th>
      </tr>
    </thead>
    <tbody>
      <tr ng-repeat="user in users">
        <td class="center aligned">@{{user.name}}</td>
        <td class="center aligned">@{{user.username}}</td>
        <td class="center aligned">@{{user.privilege}}</td>
      </tr>
    </tbody>
  </table>
</div>
@endsection

@section('modals')

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