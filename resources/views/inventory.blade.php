@extends('layouts.main')

@section('title', 'Inventory')

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
<div class="active section">Inventory</div>
@endsection
@section('content')
<div class="col-sm-12">
<h1 style="text-align: center;">Inventory</h1>
<br>
  <form action="/items" method="post" class="form-horizontal ui form" id="add-items-form">
  {{ csrf_field() }}
    <div class="field">
      <div class="six fields">
        <div class="field">
          <label>Item Search</label>
          <div class="ui icon input focus">
            <input type="text" placeholder="Search...">
            <i class="search icon"></i>
          </div>
        </div>

      </div>
    </div>
  </form>
  <div class="table-responsive">
    <table class="ui sortable compact table unstackable" id="menu-table">
      <thead>
        <tr>
          <th class="center aligned middle aligned">Category</th>
          <th class="center aligned middle aligned">Item</th>
          <th class="center aligned middle aligned">Unit</th>
          <th class="center aligned middle aligned">Qty</th>
        </tr>
      </thead>
      <tbody ng-cloak>
        <tr ng-repeat="item_data in items">
          <td class="center aligned middle aligned">@{{item_data.category}}</td>
          <td class="center aligned middle aligned">@{{item_data.item_name}}</td>
          <td class="center aligned middle aligned">@{{item_data.unit}}</td>
          <td class="center aligned middle aligned">@{{item_data.quantity}}</td>
          <td class="center aligned middle aligned"><a href="/inventory/item/@{{item_data.id}}">View History</a></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>




@endsection

@section('modals')
<div id="useinventory-modal" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Use Inv Item 1</h4>
      </div>
      <div class="modal-body">
        <form action="/items" method="post" id="add-items-form">
        {{ csrf_field() }}
        <div class="form-group">
          <label>Stocks</label>
          <input type="text" name="" placeholder="Stocks" class="form-control" value="1" readonly>
        </div>

        <div class="form-group">
          <label>How Many</label>
          <input type="text" name="" placeholder="Quantity" class="form-control">
        </div>

        <div class="form-group">
          <label>Comments</label>
          <textarea placeholder="Comments" class="form-control"></textarea>
        </div>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" form="add-items-form">Confirm</button>
      </div>
    </div>

  </div>
</div>

<div id="editmenu-modal" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit Menu 1</h4>
      </div>
      <div class="modal-body">
        <form action="/items" method="post" id="add-items-form">
        {{ csrf_field() }}
        <div class="form-group">
          <label>Category</label>
          <input type="text" name="" placeholder="Category" class="form-control" value="Food">
        </div>

        <div class="form-group">
          <label>Subcategory</label>
          <input type="text" name="" placeholder="Subcategory" class="form-control" value="Food">
        </div>


        <div class="form-group">
          <label>Item</label>
          <input type="text" name="" placeholder="Item" class="form-control" value="Menu 1">
        </div>

        <div class="form-group">
          <label>Quantity</label>
          <input type="text" name="" placeholder="Quantity" class="form-control" value="1">
        </div>

        <div class="form-group">
          <label>Price</label>
          <input type="text" name="" placeholder="Price" class="form-control" value="10.00">
        </div>

        <div class="form-group">
          <label>Comments</label>
          <textarea placeholder="Comments" class="form-control"></textarea>
        </div>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" form="add-items-form">Confirm</button>
      </div>
    </div>

  </div>
</div>

<div id="addmenu-modal" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Menu</h4>
      </div>
      <div class="modal-body">
        <form action="/items" method="post" id="add-items-form">
        {{ csrf_field() }}
        <div class="form-group">
          <label>Category</label>
          <input type="text" name="" placeholder="Category" class="form-control">
        </div>

        <div class="form-group">
          <label>Subcategory</label>
          <input type="text" name="" placeholder="Subcategory" class="form-control">
        </div>

        <div class="form-group">
          <label>Item</label>
          <input type="text" name="" placeholder="Item" class="form-control">
        </div>

        <div class="form-group">
          <label>Quantity</label>
          <input type="text" name="" placeholder="Quantity" class="form-control">
        </div>

        <div class="form-group">
          <label>Price</label>
          <input type="text" name="" placeholder="Price" class="form-control">
        </div>

        <div class="form-group">
          <label>Comments</label>
          <textarea placeholder="Comments" class="form-control"></textarea>
        </div>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" form="add-items-form">Confirm</button>
      </div>
    </div>

  </div>
</div>






@endsection

@section('scripts')
<script type="text/javascript">
  $('table').tablesort();
  $('.ui.checkbox').checkbox('enable');

  

  var app = angular.module('main', ['ngSanitize']);
  app.controller('content-controller', function($scope,$http, $sce, $window) {
    show_items();
    function show_items() {
      $http({
          method : "GET",
          url : "/api/inventory/item/show",
      }).then(function mySuccess(response) {
          // console.log(response.data.items);
          $scope.items = response.data.items;
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