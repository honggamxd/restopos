@extends('layouts.main')

@section('title', 'Purchases  ')

@section('css')
<style type="text/css">

</style>
@endsection
@section('breadcrumb')
<a class="section" href="/purchases">Purchases</a>
<i class="right angle icon divider"></i>
<div class="active section">View</div>
@endsection
@section('content')
<div class="col-sm-12">
  <p ng-cloak>
    ID : @{{purchase.id}} <br>
    Date : @{{purchase.date_}} <br>
    Time : @{{purchase.date_time}} <br>
    PO # : @{{purchase.po_number}} <br>
  </p>
  <div class="table-responsive">
    <table class="ui single line unstackable table">
      <thead>
        <tr>
          <th class="center aligned middle aligned">Category</th>
          <th class="center aligned middle aligned">Item Name</th>
          <th class="center aligned middle aligned">Quantity</th>
          <th class="center aligned middle aligned">Cost Price</th>
          <th class="center aligned middle aligned">Total</th>
        </tr>
      </thead>
      <tbody ng-init="total=0" ng-cloak>
        <tr ng-repeat="purchase_item in purchase.items">
          <td class="center aligned middle aligned">@{{purchase_item.category}}</td>
          <td class="center aligned middle aligned">@{{purchase_item.item_name}}</td>
          <td class="center aligned middle aligned">@{{purchase_item.quantity}}</td>
          <td class="right aligned middle aligned">@{{purchase_item.cost_price|currency:""}}</td>
          <td class="right aligned middle aligned" ng-init="$parent.total = $parent.total + (purchase_item.cost_price*purchase_item.quantity)">@{{(purchase_item.cost_price*purchase_item.quantity)|currency:""}}</td>
        </tr>
      </tbody>
      <tfoot ng-cloak>
        <tr>
          <th class="right aligned middle aligned" colspan="4">TOTAL</th>
          <th class="right aligned middle aligned">@{{total}}</th>
        </tr>
      </tfoot>
    </table>
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
<!--           <div class="form-group">
            <label>Subcategory:</label>
            <input type="text" name="subcategory" class="form-control" placeholder="Enter Subcategory" ng-model="formdata.subcategory">
            <p class="help-block">@{{formerrors.subcategory[0]}}</p>
          </div> -->
          <div class="form-group">
            <label>Item Name:</label>
            <input type="text" name="item_name" class="form-control" placeholder="Enter Item Name" ng-model="formdata.item_name">
            <p class="help-block">@{{formerrors.item_name[0]}}</p>
          </div>
          <div class="form-group">
            <label>Cost Price:</label>
            <input type="number" step="0.01" name="cost_price" class="form-control" placeholder="Enter Cost Price" ng-model="formdata.cost_price">
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
  // $("#add-item-modal").modal("show");
  var app = angular.module('main', ['ngSanitize']);
  app.controller('content-controller', function($scope,$http, $sce, $window) {
    $scope.purchase = {!! $purchase_data !!};
    $scope.purchase.items = {!! $items !!};
    console.log($scope.purchase.id);
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