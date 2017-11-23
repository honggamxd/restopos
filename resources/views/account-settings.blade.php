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
.modal-lg.order{
  width: 95vw;
}


</style>
@endsection
@section('breadcrumb')
<div class="active section">Account Settings</div>
@endsection
@section('content')
<div class="col-sm-6 col-sm-offset-3">
<form id="account-settings-form" ng-submit="save_settings()">
{{ csrf_field() }}


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
  <input class="form-control" type="password" placeholder="Enter Password" name="pax" ng-model="formdata.old_password">
  <p class="help-block">@{{formerrors.old_password[0]}}</p>
</div>

<div class="form-group">
  <label>New Password <small style="color: red">* This is not required to fill unless you want to change your password.</small></label>
  <input class="form-control" type="password" placeholder="Enter New Password" name="pax" ng-model="formdata.password">
  <p class="help-block">@{{formerrors.password[0]}}</p>
</div>

<div class="form-group">
  <label>Confirm New Password <small style="color: red">* This is not required to fill unless you want to change your password.</small></label>
  <input class="form-control" type="password" placeholder="Enter Confirm New Password" name="pax" ng-model="formdata.password_confirmation">
  <p class="help-block">@{{formerrors.password_confirmation[0]}}</p>
</div>
<button type="button" class="ui default button" data-dismiss="modal">Cancel</button>
<button type="submit" class="ui primary button" form="account-settings-form" ng-disabled="submit" ng-class="{'loading':submit}">Save</button>
</form>
</div>
@endsection

@section('modals')


@endsection

@section('scripts')
<script type="text/javascript">
  var app = angular.module('main', []);
  app.controller('content-controller', function($scope,$http, $sce, $window) {
    $scope.formdata = {!! $user_data !!};
    $scope.formerrors = {};

    $scope.save_settings = function() {
      $scope.formerrors = {};
      $scope.submit = true;
      $http({
         method: 'POST',
         url: '/api/users/settings',
         data: $.param($scope.formdata),
         headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
      })
      .then(function(response) {
        console.log(response.data)
         $scope.submit = false;
         $scope.formdata.old_password = "";
         $scope.formdata.password = "";
         $scope.formdata.password_confirmation = "";
         $.notify("Your settings have been saved.");
      }, function(rejection) {
        if(rejection.status != 422){
          request_error(rejection.status);
        }else if(rejection.status == 422){        
           var errors = rejection.data;
           $scope.formerrors = errors;
        }
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