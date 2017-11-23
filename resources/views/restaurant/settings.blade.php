@extends('layouts.main')

@section('title', 'Settings')

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

#complete-order-table{
  width: 100%;
  border-collapse: collapse;
}
#complete-order-table tr td,#complete-order-table tr th{
  border: 1px solid black;
  padding: 0px 2px 0px 2px;
}

.order-table{
  width: 100%;
  border-collapse: collapse;
  margin: 0;
}

</style>
@endsection
@section('breadcrumb')
<div class="active section">Restaurant Settings</div>
@endsection
@section('two_row_content')
<div class="row">
  <h1 style="text-align: center;">Restaurant Settings</h1>
    @if(Session::get('users.user_data')->privilege=="admin")
    <div class="col-sm-6">
        <label>Outlet:</label>
        <div class="ui action input">
          <div ng-hide="hide_outlet">
            <select id="restaurant_id" placeholder="Outlet" class="form-control" ng-model="restaurant_id" ng-change="change_restaurant(this)" ng-options="restaurant as restaurant.name for restaurant in restaurants track by restaurant.id">>
            </select>
          </div>
          <input type="text" ng-model="formdata.restaurant_name" ng-show="hide_outlet">
          <!-- @{{formdata.restaurant_id}} -->
          <button class="ui primary button" ng-click="toggle_outlet(this)" ng-hide="hide_outlet">Rename</button>
          <button class="ui green button" ng-click="rename_restaurant(this)" ng-show="hide_outlet">Save</button>
        </div>
    </div>
    @endif
</div>
<div class="row">
  <div class="col-sm-6">
   <button type="button" class="ui icon secondary button" ng-click="add_table()" data-tooltip="Add Table" data-position="right center"><i class="add icon"></i> Add tables</button>
   <div class="table-responsive">
     <table class="ui unstackable table">
       <thead>
         <tr>
           <th>Table Name</th>
           <!-- <th>Outlet</th> -->
           <th>Status</th>
         </tr>
         <tbody>
           <tr ng-repeat="table_data in table" ng-cloak>
             <td>@{{table_data.name}}</td>
             <!-- <td>@{{table_data.restaurant_name}}</td> -->
             <td>@{{(table_data.occupied==0?"Available":"Occupied")}}</td>
             <td><a href="javascript:void(0)" ng-click="edit_table(this)">Edit</a></td>
           </tr>
         </tbody>
       </thead>
     </table>
   </div>
  </div>
  <div class="col-sm-6">
   <button type="button" class="ui icon secondary button" ng-click="add_server()" data-tooltip="Add Waiter/Waitress" data-position="right center"><i class="add icon"></i> Add Waiter/Waitress</button>
   <div class="table-responsive">
     <table class="ui unstackable table">
       <thead>
         <tr>
           <th>Waiter/Waitress Name</th>
           <!-- <th>Outlet</th> -->
           <!-- <th>Status</th> -->
         </tr>
         <tbody>
           <tr ng-repeat="server_data in server" ng-cloak>
             <td>@{{server_data.name}}</td>
             <td><a href="javascript:void(0)" ng-click="edit_server(this)">Edit</a></td>
             <!-- <td>@{{server_data.restaurant_name}}</td> -->
           </tr>
         </tbody>
       </thead>
     </table>
   </div>
  </div>
</div>
@endsection

@section('modals')

<div id="add-list-table-modal" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Table</h4>
      </div>
      <div class="modal-body">
        <form id="add-table-form" ng-submit="add_list_table()">
          <input type="hidden" name="restaurant_id" ng-model="formdata.restaurant_id">

          <div class="form-group">
            <label>Table Name:</label>
            <input type="text" ng-model="formdata.name" placeholder="Table Name" class="form-control">
            <p class="help-block">@{{formerror.name[0]}}</p>
          </div>


        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="ui default button" data-dismiss="modal">Cancel</button>
        <button type="submit" class="ui primary button" form="add-table-form" ng-disabled="submit" ng-class="{'loading':submit}">Save</button>
      </div>
    </div>
  </div>
</div>

<div id="edit-list-table-modal" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit Table</h4>
      </div>
      <div class="modal-body">
        <form id="edit-table-form" ng-submit="edit_list_table()">
          <input type="hidden" name="restaurant_id" ng-model="formdata.restaurant_id">

          <div class="form-group">
            <label>Table Name:</label>
            <input type="text" ng-model="formdata.name" placeholder="Table Name" class="form-control">
            <p class="help-block">@{{formerror.name[0]}}</p>
          </div>


        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="ui default button" data-dismiss="modal">Cancel</button>
        <button type="submit" class="ui primary button" form="edit-table-form" ng-disabled="submit" ng-class="{'loading':submit}">Save</button>
      </div>
    </div>
  </div>
</div>


<div id="add-list-server-modal" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Waiter/Waitress</h4>
      </div>
      <div class="modal-body">
        <form id="add-server-form" ng-submit="add_list_server()">


          
          <input type="hidden" name="restaurant_id" ng-model="formdata.restaurant_id">

          <div class="form-group">
            <label>Waiter/Waitress Name:</label>
            <input type="text" ng-model="formdata.name" placeholder="Waiter/Waitress Name" class="form-control">
            <p class="help-block">@{{formerror.name[0]}}</p>
          </div>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="ui default button" data-dismiss="modal">Close</button>
        <button type="submit" class="ui primary button" form="add-server-form" ng-disabled="submit" ng-class="{'loading':submit}">Save</button>
      </div>
    </div>
  </div>
</div>
<div id="edit-list-server-modal" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit Waiter/Waitress</h4>
      </div>
      <div class="modal-body">
        <form id="edit-server-form" ng-submit="edit_list_server()">


          
          <input type="hidden" name="restaurant_id" ng-model="formdata.restaurant_id">

          <div class="form-group">
            <label>Waiter/Waitress Name:</label>
            <input type="text" ng-model="formdata.name" placeholder="Waiter/Waitress Name" class="form-control">
            <p class="help-block">@{{formerror.name[0]}}</p>
          </div>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="ui default button" data-dismiss="modal">Close</button>
        <button type="submit" class="ui primary button" form="edit-server-form" ng-disabled="submit" ng-class="{'loading':submit}">Save</button>
      </div>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script type="text/javascript">
  
  var app = angular.module('main', []);
  app.controller('content-controller', function($scope,$http, $sce, $window) {
    $scope.formdata = {};

    $scope.restaurants = {!! json_encode($restaurants) !!};
    @if(Session::get('users.user_data')->privilege=="restaurant_admin")
      $scope.restaurant_id = {
        id: {{Session::get('users.user_data')->restaurant_id}}
      };
    @else
      $scope.restaurant_id = $scope.restaurants[0];
    @endif

    $scope.hide_outlet = false;
    $scope.toggle_outlet = function(data) {
      // console.log(data.restaurant_id.name);
      $scope.formdata.restaurant_name = data.restaurant_id.name;
      $scope.hide_outlet = ($scope.hide_outlet?false:true);
    }

    $scope.rename_restaurant = function(data) {
      
      // console.log(data.restaurant_id.name);
      data.restaurant_id.name = $scope.formdata.restaurant_name;
      $scope.formdata.restaurant_id = $scope.restaurant_id['id'];
      $scope.formerror = {};
      $http({
         method: 'POST',
         url: '/api/restaurant/name',
         data: $.param($scope.formdata),
         headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
      })
      .then(function(response) {
        // console.log(response.data);
        $scope.formdata.name = "";
        $scope.restaurants = response.data;
        $scope.restaurant_id = $scope.restaurants[$scope.formdata.restaurant_id-1];
        $scope.hide_outlet = ($scope.hide_outlet?false:true);
      }, function(rejection) {
         var errors = rejection.data;
         // $scope.formerror = errors;
         $.notify(errors.restaurant_name[0],'error');

      });
      

    }

    $scope.edit_server = function(data) {
      $('#edit-list-server-modal').modal('show');
      $scope.formdata = data.server_data;
    }

    $scope.add_table = function() {
      $('#add-list-table-modal').modal('show');
      $scope.formdata.name = "";
    }
    $scope.edit_table = function(data) {
      $('#edit-list-table-modal').modal('show');
      $scope.formdata = data.table_data;
    }


    $scope.add_server = function() {
      $('#add-list-server-modal').modal('show');
      $scope.formdata.name = "";
    }

    $scope.add_list_table = function() {
      $scope.submit = true;
      $scope.formdata.restaurant_id = $scope.restaurant_id['id'];
      $scope.formerror = {};
      $http({
         method: 'POST',
         url: '/api/restaurant/table',
         data: $.param($scope.formdata),
         headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
      })
      .then(function(response) {
        // console.log(response.data);
        $.notify("Table " + response.data.toString() + " is added.");
        $scope.formdata.name = "";
        show_table();
        $scope.submit = false;
      }, function(rejection) {
         var errors = rejection.data;
         $scope.formerror = errors;
        $scope.submit = false;
      });
    }

    $scope.edit_list_table = function() {
      $scope.submit = true;
      $scope.formdata.restaurant_id = $scope.restaurant_id['id'];
      $scope.formerror = {};
      $http({
         method: 'PUT',
         url: '/api/restaurant/table',
         data: $.param($scope.formdata),
         headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
      })
      .then(function(response) {
        $scope.submit = false;
        // console.log(response.data);
        $.notify("Table " + response.data+" has been updated.");
        $('#edit-list-table-modal').modal('hide');
        $scope.formdata.name = "";
            show_table();
      }, function(rejection) {
        $scope.submit = false;
         var errors = rejection.data;
         $scope.formerror = errors;
      });
    }

    $scope.add_list_server = function() {
      $scope.submit = true;
      $scope.formdata.restaurant_id = $scope.restaurant_id['id'];
      $scope.formerror = {};
      $http({
         method: 'POST',
         url: '/api/restaurant/server',
         data: $.param($scope.formdata),
         headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
      })
      .then(function(response) {
        // console.log(response.data);
        $scope.submit = false;
        $.notify("Waiter/Waitress " + response.data+" is added.");
        $scope.formdata.name = "";
        show_server();
      }, function(rejection) {
        $scope.submit = false;
         var errors = rejection.data;
         $scope.formerror = errors;
      });
    }

    $scope.edit_list_server = function() {

      alertify.confirm(
        'Rename Waiter/Waitress',
        'Renaming the Waiter/Waitress updates the information to all order slips/bills.',
        function(){
          $scope.submit = true;
          $scope.formdata.restaurant_id = $scope.restaurant_id['id'];
          $scope.formerror = {};
          $http({
             method: 'PUT',
             url: '/api/restaurant/server',
             data: $.param($scope.formdata),
             headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
          })
          .then(function(response) {
            $scope.submit = false;
            // console.log(response.data);
            $('#edit-list-server-modal').modal('hide');
            $.notify("Waiter/Waitress " + response.data+" has been updated.");
            $scope.formdata.name = "";
            show_server();
          }, function(rejection) {
            $scope.submit = false;
             var errors = rejection.data;
             $scope.formerror = errors;
          });
        },
        function(){}
        );
    }
    $scope.change_restaurant = function(data) {
      console.log(data);
      show_server();
      show_table();
    }

    show_server();
    function show_server() {
      $scope.formdata.restaurant_id = $scope.restaurant_id['id'];
      $http({
          method : "GET",
          params: {
            'restaurant_id': $scope.formdata.restaurant_id
          },
          url : "/api/restaurant/server/list",
      }).then(function mySuccess(response) {
          $scope.server = response.data.result;
      }, function myError(rejection) {
          if(rejection.status != 422){
            request_error(rejection.status);
          }else if(rejection.status == 422){
            var errors = rejection.data;
          }
      });
    }

    show_table();
    function show_table() {
      $scope.formdata.restaurant_id = $scope.restaurant_id['id'];
      $http({
          method : "GET",
          params: {
            'restaurant_id': $scope.formdata.restaurant_id,
          },
          url : "/api/restaurant/table/list/all",
      }).then(function mySuccess(response) {
          $scope.table = response.data.result;
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
@endsection