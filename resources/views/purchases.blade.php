@extends('layouts.main')

@section('title', 'Purchases  ')

@section('css')
<style type="text/css">

</style>
@endsection
@section('breadcrumb')
<div class="active section">Purchases</div>
@endsection
@section('content')
<div class="col-sm-9">
  <div class="row">
    <div class="col-sm-5">
      <label>Search Item</label>
      <div class="ui icon input fluid">
        <i class="search icon"></i>
        <input type="text" placeholder="Search">
      </div>  
    </div>
    <div class="col-sm-5">
      <label>Search Category</label>
      <div class="ui icon input fluid">
        <i class="search icon"></i>
        <input type="text" placeholder="Search">
      </div>
    </div>
    <div class="col-sm-2">
      <div class="form-group">
        <label>&nbsp;</label>
        <button class="btn btn-primary btn-block"><span class="glyphicon glyphicon-plus"></span> Add Items</button>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-12">
      <div class="table-responsive">
        <table class="table table-condensed">
          <thead>
            <tr>
              <td>Category</td>
              <td>Item</td>
              <td>Stocks</td>
              <td>Qty</td>
              <td>Cost Price</td>
              <td></td>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Inv Category</td>
              <td>Inv Item 1</td>
              <td>1</td>
              <td>
                <div class="ui input">
                  <input type="number" placeholder="" value="20">
                </div>
              </td>
              <td>
                <div class="ui input">
                  <input type="number" placeholder="" value="15">
                </div>
              </td>
              <td><button type="button" class="btn btn-danger">&times;</button></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<div class="col-sm-3">
  <div class="form-group">
    <label>PO Number</label>
    <input type="text" name="" placeholder="PO Number" class="form-control">
  </div>
  <div class="form-group">
    <label>Comments</label>
    <textarea placeholder="Comments" class="form-control"></textarea>
  </div>
  <div class="form-group">
    <label>Controls</label>
    <button class="btn btn-primary btn-block">Save</button>
    <button class="btn btn-danger btn-block">Cancel</button>
  </div>
</div>
@endsection

@section('modals')

<div id="add-item-modal" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Items</h4>
      </div>
      <div class="modal-body">
        <form ng-submit="add_items()" id="add-item-form">
          <div class="form-group">
            <label>Category:</label>
            <input type="text" name="category" class="form-control" placeholder="Enter Category" ng-model="formdata.category">
            <p class="help-block">@{{formerrors.category[0]}}</p>
          </div>
          <div class="form-group">
            <label>Subcategory:</label>
            <input type="text" name="subcategory" class="form-control" placeholder="Enter Subcategory" ng-model="formdata.subcategory">
            <p class="help-block">@{{formerrors.subcategory[0]}}</p>
          </div>
          <div class="form-group">
            <label>Item Name:</label>
            <input type="text" name="item_name" class="form-control" placeholder="Enter Item Name" ng-model="formdata.item_name">
            <p class="help-block">@{{formerrors.item_name[0]}}</p>
          </div>
          <div class="form-group">
            <label>Cost Price:</label>
            <input type="text" name="cost_price" class="form-control" placeholder="Enter Cost Price" ng-model="formdata.cost_price">
            <p class="help-block">@{{formerrors.cost_price[0]}}</p>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" ng-submit="submit" form="add-item-form">Submit</button>
      </div>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script type="text/javascript">
  $('table').tablesort();
  $("#add-item-modal").modal("show");
  var app = angular.module('main', ['ngSanitize']);
  app.controller('content-controller', function($scope,$http, $sce) {
    $scope.formdata = {};
    $scope.formerrors = {};
    $scope.add_items = function() {
      $http({
         method: 'POST',
         url: '/api/inventory/item/add',
         data: $.param($scope.formdata),
         headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
      })
      .then(function(response) {
        // console.log(response.data);
        $scope.formdata = {};
        $scope.formerrors = {};
        alertify.success(response.data.item_name+" is added.");
      }, function(rejection) {
         var errors = rejection.data;
         console.log(errors);
         $scope.formerrors = errors;
      });
    }
  });
  angular.bootstrap(document, ['main']);
</script>
@endsection