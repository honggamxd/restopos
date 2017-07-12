@extends('layouts.main')

@section('title', 'Restaurant POS')

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
<div class="active section">Restaurant Menu</div>
@endsection
@section('content')
<div class="col-sm-12">
<h1 style="text-align: center;">Restaurant's Menu</h1>
<br><br>
  <form action="/items" method="post" class="form-horizontal ui form" id="add-items-form">
  {{ csrf_field() }}
    <div class="field">
      <div class="four fields">
        <div class="field">
          <label>Category</label>
          <select class="ui dropdown fluid">
            <option value="food">Food</option>
            <option value="beverage">Beverage</option>
          </select>
        </div>
        <div class="field">
          <label>Subcategory</label>
          <select class="ui dropdown fluid">
            <option value="food">Food</option>
            <option value="beverage">Beverage</option>
          </select>
        </div>

        <div class="field">
          <label>Menu Search</label>
          <div class="ui icon input focus">
            <input type="text" placeholder="Search...">
            <i class="search icon"></i>
          </div>
        </div>
        <div class="field">
          <label>&nbsp;</label>
          <button type="button" class="ui primary button fluid" onclick="$('#addmenu-modal').modal('show')">Add Menu</button>
        </div>
      </div>
    </div>
  </form>
  <div class="table-responsive">
    <table class="ui sortable compact table unstackable" id="menu-table">
      <thead class="full-width">
        <tr>
          <th class="center aligned">Category</th>
          <th class="center aligned">Subcategory</th>
          <th class="center aligned">Item</th>
          <th class="center aligned">Price</th>
        </tr>
      </thead>
      <tbody ng-cloak>
        <tr ng-repeat="menu_data in menu">
          <td class="center aligned middle aligned" ng-bind="menu_data.category" ng-cloak>Category</td>
          <td class="center aligned middle aligned" ng-bind="menu_data.subcategory" ng-cloak>Subcategory</td>
          <td class="center aligned middle aligned" ng-bind="menu_data.name" ng-cloak>Menu Name</td>
          <td class="right aligned middle aligned" ng-cloak>@{{menu_data.price|currency:""}}</td>
          <td class="center aligned middle aligned" style="width: 12vw">
            <div class="ui toggle checkbox">
              <input type="checkbox" name="public" ng-change="available_to_menu(this)" ng-model="menu_data.is_prepared">
              <label>Available</label>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

@endsection

@section('modals')
<div>
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

  <div id="addmenu-modal" class="modal fade" role="dialog" tabindex="-1" ng-controller="add_menu-controller">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add Menu</h4>
        </div>
        <div class="modal-body">
          <form action="/restaurant/menu" method="post" id="add-items-form">
          {{ csrf_field() }}
          <div class="form-group">
            <label>Category</label>
            <input type="text" name="category" placeholder="Category" class="form-control" ng-model="formdata.category">
            <p class="help-block" ng-show="formdata.category_error" ng-bind="formdata.category_error[0]"></p>
          </div>

          <div class="form-group">
            <label>Subcategory</label>
            <input type="text" name="subcategory" placeholder="Subcategory" class="form-control" ng-model="formdata.subcategory">
            <p class="help-block" ng-show="formdata.subcategory_error" ng-bind="formdata.subcategory_error[0]"></p>
          </div>

          <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" placeholder="Name" class="form-control" ng-model="formdata.name">
            <p class="help-block" ng-show="formdata.name_error" ng-bind="formdata.name_error[0]"></p>
          </div>

          <div class="form-group">
            <label>Price</label>
            <input type="text" name="price" placeholder="Price" class="form-control" ng-model="formdata.price">
            <p class="help-block" ng-show="formdata.price_error" ng-bind="formdata.price_error[0]"></p>
          </div>

          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" form="add-items-form" ng-disabled="submit" ng-click="add_menu()">Save</button>
        </div>
      </div>

    </div>
  </div>
</div>






@endsection

@section('scripts')
<script type="text/javascript">
  $('table').tablesort();

  // $('.ui.checkbox').checkbox('enable');
  var app = angular.module('main', []);
  app.controller('add_menu-controller', function($scope,$http, $sce) {
  angular.element('.ui.checkbox').checkbox('enable');

    $scope.formdata = {
      _token: "{{csrf_token()}}",
    };

    $scope.add_menu = function() {
      $scope.submit = true;
      $http({
          method: 'POST',
          url: '/restaurant/menu',
          data: $.param($scope.formdata),
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
      })
      .then(function(response) {
          console.log(response.data);
          location.reload();
      }, function(rejection) {
          var errors = rejection.data;
          $scope.formdata.ar_number_error = errors.ar_number;
          $scope.formdata.amount_error = errors.amount;
          $scope.formdata.date_payment_error = errors.date_payment;
          $scope.submit = false;
      });
    }

  });

  app.controller('content-controller', function($scope,$http, $sce) {
    $scope.formdata = {
      _token: "{{csrf_token()}}",
    };

    show_menu();
    function show_menu() {
      $http({
          method : "GET",
          url : "/restaurant/menu/list",
      }).then(function mySuccess(response) {
          $scope.menu = response.data.result;
      }, function myError(response) {
          console.log(response.statusText);
      });
    }
    $scope.available_to_menu = function(data) {
      // console.log(data.menu_data.is_prepared);
      $scope.formdata.is_prepared = data.menu_data.is_prepared;
      $http({
          method: 'PUT',
          url: '/restaurant/menu/list/'+data.menu_data.id,
          data: $.param($scope.formdata),
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
      })
      .then(function(response) {
          console.log(response.data);
      }, function(rejection) {
          var errors = rejection.data;
      });
    }
  });

  angular.bootstrap(document, ['main']);
</script>
@endsection