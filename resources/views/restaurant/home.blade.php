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


#complete-order-table{
  width: 100%;
  border-collapse: collapse;
}
#complete-order-table tr td,#complete-order-table tr th{
  border: 1px solid black;
  padding: 0px 2px 0px 2px;
}




</style>
@endsection
@section('breadcrumb')
<div class="active section">Restaurant</div>
@endsection
@section('content')
<div class="col-sm-12">
  <h1 style="text-align: center;">Name of Outlet</h1>
  <div class="form-group">
      <div class="ui action input">
        <input type="text" placeholder="Search Table">
        <button class="ui icon button" type="submit">
          <i class="search icon"></i>
        </button>
        <button type="button" class="ui icon primary button" ng-click="show_table()" data-tooltip="Add Table" data-position="right center"><i class="add icon"></i></button>
      </div>
  </div>
  <div class="table-responsive">
    <table class="ui unstackable sortable celled table" id="customer-table">
      <thead>
        <tr>
          <th class="center aligned">Table</th>
          <th class="center aligned">Time</th>
          <th class="center aligned">Pax</th>
          <th class="center aligned">Total</th>
        </tr>
      </thead>
      <tbody>
        <tr ng-repeat="customer_data in table_customers" ng-cloak>
          <td style="width: 30vw;" class="center aligned middle aligned" ng-bind="customer_data.table_name"></td>
          <td class="center aligned middle aligned" ng-bind="customer_data.date_time"></td>
          <td class="center aligned middle aligned"><i class="fa fa-users" aria-hidden="true"></i> @{{customer_data.pax}}</td>
          <td class="center aligned middle aligned">10,000.00</td>
          <td class="left aligned middle aligned" style="width: 28vw;">
            <div class="ui buttons" ng-if="!customer_data.has_order">
              <button class="ui inverted green button" ng-click="add_order(this)"><i class="fa fa-file-text-o" aria-hidden="true"></i> Order</button>
              <button class="ui inverted red button"><i class="fa fa-trash-o" aria-hidden="true"></i> Remove</button>
            </div>
            <div class="ui buttons" ng-if="customer_data.has_order">
              <div class="ui buttons">
                <button class="ui inverted green button" ng-click="add_order(this)"><i class="fa fa-file-text-o" aria-hidden="true"></i> Order</button>
                <a href="/bill" class="ui inverted violet button"><i class="fa fa-calculator" aria-hidden="true"></i> Bill out</a>
                <button class="ui inverted red button"><i class="fa fa-trash-o" aria-hidden="true"></i> Cancel Orders</button>
              </div>
            </div>
          </td>
        </tr>
       
      </tbody>
    </table>
  </div>  
</div>
@endsection

@section('modals')

<div id="add-table-modal" class="modal fade" role="dialog" tabindex="-1" ng-controller="add-table-controller">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Table</h4>
      </div>
      <div class="modal-body">
        <form action="/items" method="post" id="add-items-form">
        {{ csrf_field() }}
        <div class="form-group">
          <select class="form-control" id="select-tablenumber" name="table_id" ng-model="formdata.table_id">
            <option value="">Table Number</option>
            <option ng-repeat="table_data in table" value="@{{table_data.id}}">@{{table_data.name}}</option>
          </select>
        </div>
        <div class="form-group">
          <label># of Pax</label>
          <input class="form-control" type="text" name="pax" placeholder="Enter # of Pax" name="pax" ng-model="formdata.pax">
        </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" form="add-items-form" ng-click="add_table()" ng-disabled="submit">Save</button>
      </div>
    </div>

  </div>
</div>


<div id="add-order-modal" class="modal fade" role="dialog" tabindex="-1" ng-controller="add-order-controller">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Orders</h4>
      </div>
      <div class="modal-body">
        <form action="/items" method="post" class="form-horizontal ui form" id="add-items-form">
        {{ csrf_field() }}
        <input type="hidden" name="table_customer_id" ng-model="table_customer_id">
        <div class="row">
          <div class="col-sm-6">
            <div class="field">
              <div class="two fields">
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
              </div>

              <div class="field">
                <label>Menu Search</label>
                <div class="ui fluid icon input focus">
                  <input type="text" placeholder="Search...">
                  <i class="search icon"></i>
                </div>
              </div>
            </div>

            <div class="table-responsive" style="height: 30vh;">
              <table class="ui compact table" id="menu-table">
                <thead>
                <tr>
                  <th class="center aligned">Menu</th>
                  <th class="center aligned">Price</th>
                  <th class="center aligned"></th>
                </tr>
                </thead>
                <tbody>
                  <tr ng-repeat="menu_data in menu">
                    <td class="left aligned middle aligned">@{{menu_data.name}}</td>
                    <td class="center aligned middle aligned">@{{menu_data.price|currency:""}}</td>
                    <td class="right aligned middle aligned">
                      <button type="button" class="btn btn-success" ng-click="add_cart(this)"><i class="fa fa-arrow-right" aria-hidden="true"></i> Place Order</button>
                    </td>
                  </tr>
                </tbody>
                <tfoot>

                </tfoot>
              </table>
            </div>
          </div>
          <div class="col-sm-6">
            <p>Table #: <b>@{{table_name}}</b></p>
            <div class="table-responsive" style="height: 42.5vh;">
              <table class="ui compact table unstackable">
                <thead>
                  <tr>
                    <th class="center aligned">Menu</th>
                    <th class="center aligned">Quantity</th>
                    <th class="center aligned">Total</th>
                  </tr>
                </thead>
                <tbody>
                  <tr ng-repeat="cart_data in table_customer_cart">
                    <td class="center aligned">@{{cart_data.name}}</td>
                    <td class="right aligned">@{{cart_data.quantity}}</td>
                    <td class="right aligned">@{{cart_data.total|currency:""}}</td>
                  </tr>
                </tbody>
                <tfoot>
                  <tr>
                    <th class="right aligned" colspan="2">Total:</th>
                    <th class="right aligned">@{{table_customer_total|currency:""}}</th>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" ng-click="make_orders(this)" ng-disabled="submit"><span class="glyphicon glyphicon-print"></span> Print</button>
      </div>
    </div>

  </div>
</div>


@endsection

@section('scripts')
<script type="text/javascript">
  $('#customer-table').tablesort();
  shortcut.add("Ctrl+Shift+A",function() {
    $('#add-table-modal').modal('show')
  });

  $('#add-table-modal').on('shown.bs.modal', function () {
      $('#select-tablenumber').focus();
  });
  var app = angular.module('main', []);
  app.controller('content-controller', function($scope,$http, $sce) {
    $scope.formdata = {
     _token: "{{csrf_token()}}",
    };
    $scope.table = {};

    $scope.add_table = function() {
      $scope.submit = true;
      $http({
         method: 'POST',
         url: '/restaurant/table/customer/add',
         data: $.param($scope.formdata),
         headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
      })
      .then(function(response) {
         console.log(response.data);
         show_table_customers();
         show_table();
         $scope.submit = false;
         $("#add-table-modal").modal("hide");
      }, function(rejection) {
         var errors = rejection.data;
         $scope.formdata.date_payment_error = errors.date_payment;
         $scope.submit = false;
      });
    }

    function show_table() {
      $http({
          method : "GET",
          url : "/restaurant/table/list",
      }).then(function mySuccess(response) {
          $scope.table = response.data.result;
      }, function myError(response) {
          console.log(response.statusText);
      });
    }

    $scope.show_table = function() {
      $('#add-table-modal').modal('show');
      show_table();
    }

    $scope.table_customers = {};
    show_table_customers();
    function show_table_customers() {
      $http({
          method : "GET",
          url : "/restaurant/table/customer/list",
      }).then(function mySuccess(response) {
          $scope.table_customers = response.data.result;
      }, function myError(response) {
          console.log(response.statusText);
      });
    }
    $scope.table_customer_cart = {};
    $scope.table_customer_total = "";
    $scope.table_name = "";

    $scope.add_order = function(data) {
      $("#add-order-modal").modal("show");
      $scope.table_customer_id = data.$parent.customer_data.id;
      $scope.table_name = data.$parent.customer_data.table_name;
      show_cart(data.$parent.customer_data.id);
      show_menu();
    }
    function show_cart(table_customer_id) {
      $http({
          method : "GET",
          url : "/restaurant/table/order/cart/"+table_customer_id,
      }).then(function mySuccess(response) {
        $scope.table_customer_cart = response.data.cart;
        $scope.table_customer_total = response.data.total;
      }, function myError(response) {
          console.log(response.statusText);
      });
    }

    $scope.menu = {};
    function show_menu() {
      $http({
          method : "GET",
          url : "/restaurant/menu/list",
          params: {
            for: "orders"
          },
      }).then(function mySuccess(response) {
          $scope.menu = response.data.result;
      }, function myError(response) {
          console.log(response.statusText);
      });
    }

    $scope.make_orders = function(data) {
      // console.log($scope.formdata);
      
      $scope.submit = true;
      $http({
        method: 'POST',
        url: '/restaurant/table/order/make/'+$scope.table_customer_id,
        data: $.param($scope.formdata),
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
      })
      .then(function(response) {
        console.log(response.data);
        $scope.submit = false;
      }, function(rejection) {
        var errors = rejection.data;
        $scope.formdata.ar_number_error = errors.ar_number;
        $scope.formdata.amount_error = errors.amount;
        $scope.formdata.date_payment_error = errors.date_payment;
        $scope.submit = false;
      }); 
    }

    
  });

  app.controller('add-order-controller',function ($scope,$http,$sce) {
    $scope.formdata = {
       _token: "{{csrf_token()}}",
    };

    function show_cart(table_customer_id) {
      $http({
          method : "GET",
          url : "/restaurant/table/order/cart/"+table_customer_id,
      }).then(function mySuccess(response) {
        $scope.table_customer_cart = response.data.cart;
          $scope.table_customer_total = response.data.total;
      }, function myError(response) {
          console.log(response.statusText);
      });
    }
    $scope.add_cart = function(data) {
      // console.log(data.menu_data);
      $scope.formdata.menu_id = data.menu_data.id;
      $scope.formdata.table_customer_id = $scope.table_customer_id;
      $http({
         method: 'POST',
         url: '/restaurant/table/order/cart',
         data: $.param($scope.formdata),
         headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
      })
      .then(function(response) {
         // console.log(response.data);
         $scope.table_customer_cart = response.data.cart;
         $scope.table_customer_total = response.data.total;
         alertify.success("Order has been placed.");
      }, function(rejection) {
         var errors = rejection.data;
         alertify.error(rejection.data.error);
         $scope.formdata.date_payment_error = errors.date_payment;
      });
    }

  });

  app.controller('add-table-controller',function($scope,$http,$sce) {
    
  });
  angular.bootstrap(document, ['main']);
</script>
@endsection