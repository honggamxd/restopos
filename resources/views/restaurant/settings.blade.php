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

          <div class="form-group">
            <label>Outlet:</label>
            <select name="restaurant_id" placeholder="Outlet" class="form-control" ng-model="formdata.restaurant_id" required>
              <option value="">Select Outlet</option>
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
            </select>
          </div>

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
    $scope.add_list_table = function() {
      $http({
         method: 'POST',
         url: '/restaurant/table/add',
         data: $.param($scope.formdata),
         headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
      })
      .then(function(response) {
        // console.log(response.data);
        alertify.success(response.data+" is added.");
        $scope.formdata.name = "";
      }, function(rejection) {
         var errors = rejection.data;
         $scope.formdata.date_payment_error = errors.date_payment;
      });
    }
  });


  angular.bootstrap(document, ['main']);
</script>
@endsection