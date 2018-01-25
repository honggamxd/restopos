@extends('layouts.main')

@section('title', 'Users')

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
      <tbody ng-cloak>
        <tr ng-repeat="user in users" ng-hide="user.id=={{Auth::user()->id}}">
          <td class="center aligned middle aligned">@{{user.name}}</td>
          <td class="center aligned middle aligned">@{{user.username}}</td>
          <td class="center aligned middle aligned">@{{user.str_privilege}}</td>
          <td class="center aligned middle aligned">@{{user.restaurant_name}}</td>
          <td class="center aligned middle aligned">
            <div class="ui buttons">
              <button class="ui primary button" ng-click="edit_user(this)" data-balloon="Click Edit the Privilege of the User" data-balloon-pos="down">
                <i class="glyphicon glyphicon-edit" aria-hidden="true"></i>
              </button>
              <button class="ui negative button" ng-click="delete_user(this)" data-balloon="Click Delete User" data-balloon-pos="down">
                <i class="glyphicon glyphicon-trash" aria-hidden="true"></i>
              </button>
            </div>
          </td>
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
            <option value="restaurant_admin">Restaurant Admin</option>
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
          <input class="form-control" type="text" placeholder="Enter Password" name="pax" ng-model="formdata.password" ng-init="formdata.password='password123'">
          <!-- <small>* This is the default password, the user must change his password after account creation.</small> -->
          <p class="help-block">@{{formerrors.password[0]}}</p>
        </div>

        <div class="form-group">
          <div class="checkbox">
            <label><input type="checkbox" ng-model="formdata.allow_edit_info">Allow User to Edit Information</label>
          </div>
        </div>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="ui default button" data-dismiss="modal">Cancel</button>
        <button type="submit" class="ui primary button" form="add-user-form" ng-disabled="submit" ng-class="{'loading':submit}">Save</button>
      </div>
    </div>

  </div>
</div>

<div id="edit-user-modal" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">edit User privileges</h4>
      </div>
      <div class="modal-body">
        <form id="edit-user-form" ng-submit="update_user()">
        {{ csrf_field() }}

        <div class="form-group">
          <label>Privilege:</label>
          <select name="restaurant_id" placeholder="Outlet" class="form-control" ng-model="formdata.privilege" ng-init="formdata.privilege='restaurant_cashier'">
            <option value="restaurant_cashier">Restaurant Cashier</option>
            <option value="restaurant_admin">Restaurant Admin</option>
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
          <label>Replace Password</label>
          <input class="form-control" type="text" placeholder="Enter Password" name="pax" ng-model="formdata.password">
          <!-- <small>* This is the default password, the user must change his password after account creation.</small> -->
          <p class="help-block">@{{formerrors.password[0]}}</p>
        </div>

        <div class="form-group">
          <div class="checkbox">
            <label><input type="checkbox" ng-model="formdata.allow_edit_info" ng-checked="formdata.allow_edit_info==1">Allow User to Edit Information</label>
          </div>
        </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="ui default button" data-dismiss="modal">Cancel</button>
        <button type="submit" class="ui primary button" form="edit-user-form" ng-disabled="submit" ng-class="{'loading':submit}">Save</button>
      </div>
    </div>

  </div>
</div>


@endsection

@push('scripts')
<script type="text/javascript">
  
  app.controller('content-controller', function($scope,$http, $sce, $window) {
    show_users();
    function show_users() {
      $http({
          method : "GET",
          url : "/api/users",
      }).then(function mySuccess(response) {
          $scope.users = response.data.result;
      }, function myError(rejection) {
          if(rejection.status != 422){
            request_error(rejection.status);
          }else if(rejection.status == 422){
            var errors = rejection.data;
          }
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

    $scope.edit_user = function(data) {
      console.log(data);
      $scope.formdata = data.user;
      $scope.formdata.restaurant_id = (data.user.restaurant_id.toString()=='0'?'':data.user.restaurant_id.toString()); 
      $('#edit-user-modal').modal('show');
    }

    $scope.update_user = function(data) {
      var formdata = {
        id: $scope.formdata.id,
        privilege: $scope.formdata.privilege,
        restaurant_id: $scope.formdata.restaurant_id,
        allow_edit_info: $scope.formdata.allow_edit_info,
        password: $scope.formdata.password
      };
      $scope.formerrors = {};
      $scope.submit = true;
      $http({
         method: 'POST',
         url: '/api/users/edit/'+formdata.id,
         data: $.param(formdata),
         headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
      })
      .then(function(response) {
        console.log(response.data)
         $("#edit-user-modal").modal("hide");
         $.notify('The Privileges of '+$scope.formdata.username+' has been updated.');
         $scope.formdata = {};
         $scope.formerrors = {};
         $scope.submit = false;
         $scope.users = response.data.result;
      }, function(rejection) {
         var errors = rejection.data;
         if(rejection.status != 422){
           request_error(rejection.status);
         }else if(rejection.status == 422){        
            var errors = rejection.data;
            $scope.formerrors = errors;
            $scope.submit = false;
         }
      });
    }

    $scope.delete_user = function(data) {
      alertify.confirm(
        'DELETE  <b style=" text-transform: uppercase;">'+data.user.username+'</b>',
        'Are you sure you want to delete this  <b>'+data.user.username+'</b> user?',
        function(){
          var formdata = {
            id: data.user.id,
            privilege: data.user.privilege,
            restaurant_id: data.user.restaurant_id,
          };
          $scope.formerrors = {};
          $scope.submit = true;
          $http({
             method: 'POST',
             url: '/api/users/delete/'+formdata.id,
             data: $.param(formdata),
             headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
          })
          .then(function(response) {
             $.notify('The user '+data.user.username+' has been deleted.');
             $scope.users = response.data.result;
          }, function(rejection) {
             var errors = rejection.data;
             if(rejection.status != 422){
               request_error(rejection.status);
             }else if(rejection.status == 422){        
                var errors = rejection.data;
                $scope.formerrors = errors;
                $scope.submit = false;
             }
          });
        },
        function()
        {
          // alertify.error('Cancel')
        }
      );
    }
  });


  
</script>
@endpush