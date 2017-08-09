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
<div class="active section">Settings</div>
@endsection
@section('content')
<div class="col-sm-12">
 <button type="button" class="ui icon secondary button" onclick="$('#add-list-table-modal').modal('show')" data-tooltip="Add Table" data-position="right center"><i class="add icon"></i> Add tables</button>
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
         </tr>
       </tbody>
     </thead>
   </table>
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
        <form>

<!--           <div class="form-group">
            <label>Outlet:</label>
            <select name="restaurant_id" placeholder="Outlet" class="form-control" ng-model="formdata.restaurant_id" required>
              <option value="">Select Outlet</option>
              @foreach($restaurants as $restaurant)
                <option value="{{$restaurant->id}}">{{$restaurant->name}}</option>
              @endforeach
            </select>
          </div> -->
          
          <input type="hidden" name="restaurant_id" ng-model="formdata.restaurant_id">

          <div class="form-group">
            <label>Table Name:</label>
            <input type="text" ng-model="formdata.name" placeholder="Table Name" class="form-control">
          </div>


        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" ng-click="add_list_table()">Save</button>
      </div>
    </div>
  </div>
</div>





@endsection

@section('scripts')
<script type="text/javascript">
  
  var app = angular.module('main', []);
  app.controller('content-controller', function($scope,$http, $sce, $window) {
    $scope.formdata = {
     _token: "{{csrf_token()}}",
    };
    $scope.formdata.restaurant_id = {{Session::get('users.user_data')->restaurant_id}};
    $scope.add_list_table = function() {
      $http({
         method: 'POST',
         url: '/api/restaurant/table/add',
         data: $.param($scope.formdata),
         headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
      })
      .then(function(response) {
        // console.log(response.data);
        alertify.success(response.data+" is added.");
        $scope.formdata.name = "";
            show_table();
      }, function(rejection) {
         var errors = rejection.data;
         $scope.formdata.date_payment_error = errors.date_payment;
      });
    }
    show_table();
    function show_table() {
      $http({
          method : "GET",
          params: {
            'restaurant_id': {{Session::get('users.user_data')->restaurant_id}}
          },
          url : "/api/restaurant/table/list/all",
      }).then(function mySuccess(response) {
          $scope.table = response.data.result;
      }, function myError(response) {
          console.log(response.statusText);
      });
    }

  });


  angular.bootstrap(document, ['main']);
</script>
@endsection