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
          <td class="center aligned middle aligned" ng-click="view_orders(this)"><a href="javascript:void(0);">@{{customer_data.total|currency:""}}</a></td>
          <td class="left aligned middle aligned" style="width: 28vw;">
            <div class="ui buttons" ng-if="!customer_data.has_order">
              <button class="ui inverted green button" ng-click="add_order(this)"><i class="fa fa-file-text-o" aria-hidden="true"></i> Order</button>
              <button class="ui inverted red button" ng-click="delete_table_customer(this)"><i class="fa fa-trash-o" aria-hidden="true"></i> Remove</button>
            </div>
            <div class="ui buttons" ng-if="customer_data.has_order">
              <div class="ui buttons">
                <button class="ui inverted green button" ng-click="add_order(this)" ng-if="!customer_data.has_billed_out"><i class="fa fa-file-text-o" aria-hidden="true"></i> Order</button>
                <button class="ui inverted violet button" ng-click="bill_out(this)" ng-if="!customer_data.has_bill"><i class="fa fa-calculator" aria-hidden="true"></i> Bill out</button>
                <button class="ui inverted brown button" ng-click="view_bills(this)" ng-if="customer_data.has_bill"><i class="fa fa-calculator" aria-hidden="true"></i> View Bills</button>
                <!-- <button class="ui inverted red button" ng-if="!customer_data.has_billed_out"><i class="fa fa-trash-o" aria-hidden="true"></i> Cancel Orders</button> -->
                <button class="ui inverted red button" ng-click="delete_table_customer(this)" ng-if="customer_data.has_paid == 1"><i class="fa fa-trash-o" aria-hidden="true"></i> Remove</button>
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

<div id="add-table-modal" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Table</h4>
      </div>
      <div class="modal-body">
        <form action="/items" method="post" id="add-items-form">
        {{ csrf_field() }}
        <div class="form-group" ng-show="has_table">
          <select class="form-control" id="select-tablenumber" name="table_id" ng-model="formdata.table_id" ng-options="item as item.name for item in table track by item.id">
            <!-- <option ng-repeat="table_data in table" value="@{{table_data.id}}">@{{table_data.name}}</option> -->
          </select>
        </div>
        <div class="form-group" ng-hide="has_table">
          <span class="form-control">No Available Table</span>
        </div>
        <div class="form-group">
          <label># of Pax</label>
          <input class="form-control" type="number" name="pax" placeholder="Enter # of Pax" name="pax" ng-model="formdata.pax" required>
          <p class="help-block">@{{formerrors.pax[0]}}</p>
        </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" form="add-items-form" ng-click="add_table()" ng-disabled="submit" ng-show="has_table">Save</button>
      </div>
    </div>

  </div>
</div>


<div id="add-order-modal" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog modal-lg order">
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
                  <tr ng-repeat="cart_data in table_customer_cart" ng-init="table_customer_cart={};table_customer_total=''">
                    <td class="center aligned middle aligned">
                      <div style="width: 100%;cursor: pointer;" ng-click="toggle_special_order(this)" ng-init="cart_data.show_special_order=false">
                        @{{cart_data.name}}
                      </div>
                      <input type="text" name="" ng-model="cart_data.special_order" ng-change="add_special_order(this)" ng-if="cart_data.special_order != '' || cart_data.show_special_order">
                    </td>
                    <td class="center aligned middle aligned" ng-init="cart_data.update_quantity=true">
                      <div ng-hide="cart_data.show_update_quantity" ng-click="toggle_update_quantity(this)" style="width: 100%;cursor: pointer;">@{{cart_data.quantity}}</div>
                      <input style="width: 100px" type="number" ng-show="cart_data.show_update_quantity" ng-model="cart_data.quantity" ng-blur="update_quantity(this)" focus-me="cart_data.show_update_quantity">
                    </td>
                    <td class="right aligned middle aligned">@{{cart_data.total|currency:""}}</td>
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
        <button type="button" class="btn btn-primary" ng-click="make_orders(this)" ng-disabled="submit">Confirm</button>
      </div>
    </div>

  </div>
</div>

<div id="view-order-modal" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Order</h4>
      </div>
      <div class="modal-body">
        <table class="order-table">
        <tbody>
          <tr>
            <td style="width: 50%">Outlet:<span ng-cloak></span></td>
            <td>Date: <span ng-cloak>@{{order.date_}}</span></td>
          </tr>
          <tr>
            <td>Food Order #: <span ng-cloak>@{{order.id}}</td>
            <td>Time: <span ng-cloak>@{{order.date_time}}</span></td>
          </tr>
          <tr>
            <td>Table #: <span ng-cloak>@{{order.table_name}}</span></td>
            <td># of Pax: <span ng-cloak>@{{order.pax}}</span></td>
          </tr>
        </tbody>
        </table>
        <h1 style="text-align: center;">H1</h1>
        <table class="order-table">
        <tbody>
          <thead>
            <tr>
              <th style="text-align: center;">ITEM</th>
              <th style="text-align: center;">QTY</th>
              <th style="text-align: right;">TOTAL</th>
            </tr>
          </thead>
          <tbody>
            <tr ng-repeat="items in order_detail" ng-cloak>
              <td>@{{items.menu}}<span ng-if="items.special_order != ''"><br>(@{{items.special_order}})</span></td>
              <td style="text-align: center;" ng-bind="items.quantity"></td>
              <td style="text-align: right;">@{{items.quantity*items.price|currency:""}}</td>
            </tr>
          </tbody>
        </tbody>
        </table>
        <br>
        <p>Server:</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <a class="btn btn-primary" href="/restaurant/order/@{{order.id}}" target="_blank"><span class="glyphicon glyphicon-print"></span> Print</a>
      </div>
    </div>
  </div>
</div>


<div id="view-list-order-modal" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Orders of Table @{{table_name}}</h4>
      </div>
      <div class="modal-body">
        <table class="ui unstackable sortable compact table" id="list-order-table">
          <thead>
            <tr>
              <th class="center aligned" width="100%">Order #</th>
            </tr>
          </thead>
          <tbody>
            <tr ng-repeat="order_data in orders">
              <td ng-bind="order_data.id"></td>
              <td>
                <a class="btn btn-primary" href="/restaurant/order/@{{order_data.id}}" target="_blank"><span class="glyphicon glyphicon-print"></span> Print</a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div id="bill-preview-modal" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Bill</h4>
      </div>
      <div class="modal-body">
        <table class="ui unstackable compact table">
          <thead>
            <tr>
              <th class="center aligned">Item</th>
              <th class="center aligned">Qty</th>
              <th class="right aligned">Price</th>
              <th class="right aligned">Total</th>
            </tr>
          </thead>
          <tbody>
            <tr ng-repeat="bill_preview_data in bill_preview">
              <td class="left aligned middle aligned">@{{bill_preview_data.name}}<span ng-if="bill_preview_data.special_order != ''"><br>(@{{bill_preview_data.special_order}})</span></td>
              <td class="center aligned">@{{bill_preview_data.quantity}}</td>
              <td class="right aligned">@{{bill_preview_data.price|currency:""}}</td>
              <td class="right aligned">@{{ (bill_preview_data.quantity*bill_preview_data.price)|currency:"" }}</td>
            </tr>
          </tbody>
          <tfoot>
            <tr>
              <th colspan="3" class="right aligned">Total</th>
              <th class="right aligned">@{{bill_preview.total|currency:""}}</th>
            </tr>
          </tfoot>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" ng-click="make_bill(this)" ng-model="bill_preview.table_customer_id">Proceed</button>
      </div>
    </div>
  </div>
</div>

<div id="view-bill-modal" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Table #: @{{bill.table_name}} Bill</h4>
      </div>
      <div class="modal-body">
        <table class="ui unstackable compact table">
          <thead>
            <tr>
              <th class="center aligned">Check #</th>
            </tr>
          </thead>
          <tbody>
            <tr ng-repeat="bill_data in bill">
              <td ng-bind="bill_data.id" class="center aligned"></td>
              <td>
                <div class="btn-group">
                  <a class="btn btn-primary" href="/restaurant/bill/@{{bill_data.id}}" target="_blank"><span class="glyphicon glyphicon-print"></span> Print</a>
                  <button class="btn btn-success" ng-click="add_payment(this)" ng-if="bill_data.is_paid == 0"><span class="glyphicon glyphicon-shopping-cart"></span> Payments</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div id="payment-modal" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Payment of Check #</h4>
      </div>
      <div class="modal-body" style="min-height: 50vh;">
        <div class="form-group">
          <label>Total:</label>
          <span class="form-control">@{{formdata.total|currency:""}}</span>
        </div>
        <form class="form">
          <div class="form-group">
            <label>Settlement:</label>
            <select name="settlement" id="settlement" multiple="" class="ui fluid dropdown" ng-model="formdata.settlement" ng-change="settlements_payment()">
              <option value="">Select Settlement</option>
              <option value="cash">{{settlements("cash")}}</option>
              <option value="credit">{{settlements("credit")}}</option>
              <option value="debit">{{settlements("debit")}}</option>
              <option value="cheque">{{settlements("cheque")}}</option>
              <option value="guest_ledger">{{settlements("guest_ledger")}}</option>
              <option value="send_bill">{{settlements("send_bill")}}</option>
              <option value="free_of_charge">{{settlements("free_of_charge")}}</option>
            </select>
          </div>
          <div class="form-group" ng-if="formdata.settlements_payment.cash">
            <label>{{settlements("cash")}}:</label>
            <input type="number" class="form-control" step="0.01" ng-model="formdata.settlements_amount.cash" ng-change="input_payment()">
          </div>
          <div class="form-group" ng-if="formdata.settlements_payment.credit">
            <label>{{settlements("credit")}}:</label>
            <input type="number" class="form-control" step="0.01" ng-model="formdata.settlements_amount.credit"  ng-change="input_payment()">
          </div>
          <div class="form-group" ng-if="formdata.settlements_payment.debit">
            <label>{{settlements("debit")}}:</label>
            <input type="number" class="form-control" step="0.01" ng-model="formdata.settlements_amount.debit"  ng-change="input_payment()">
          </div>
          <div class="form-group" ng-if="formdata.settlements_payment.cheque">
            <label>{{settlements("cheque")}}:</label>
            <input type="number" class="form-control" step="0.01" ng-model="formdata.settlements_amount.cheque"  ng-change="input_payment()">
          </div>
          <div class="form-group" ng-if="formdata.settlements_payment.guest_ledger">
            <label>{{settlements("guest_ledger")}}:</label>
            <input type="number" class="form-control" step="0.01" ng-model="formdata.settlements_amount.guest_ledger"  ng-change="input_payment()">
          </div>
          <div class="form-group" ng-if="formdata.settlements_payment.send_bill">
            <label>{{settlements("send_bill")}}:</label>
            <input type="number" class="form-control" step="0.01" ng-model="formdata.settlements_amount.send_bill"  ng-change="input_payment()">
          </div>
          <div class="form-group" ng-if="formdata.settlements_payment.free_of_charge">
            <label>{{settlements("free_of_charge")}}:</label>
            <input type="number" class="form-control" step="0.01" ng-model="formdata.settlements_amount.free_of_charge"  ng-change="input_payment()">
          </div>
          <div class="form-group">
            <label>Excess:</label>
            <span class="form-control">@{{formdata.excess|currency:""}}</span>
          </div>

        </form>
      </div>
      <div class="modal-footer" ng-model="bill_id">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" ng-click="make_payment(this)" ng-if="valid_payment">Save</button>
      </div>
    </div>
  </div>
</div>



@endsection

@section('scripts')
<script type="text/javascript">
  $('#customer-table').tablesort();
  $('#list-order-table').tablesort();
  shortcut.add("Ctrl+Shift+A",function() {
    $('#add-table-modal').modal('show');
  });

  $('#add-table-modal').on('shown.bs.modal', function () {
      $('#select-tablenumber').focus();
  });
  var app = angular.module('main', []);
  app.controller('content-controller', function($scope,$http, $sce, $window) {
    $scope.formdata = {
     _token: "{{csrf_token()}}",
    };
    $scope.table = {};

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

    $scope.add_table = function() {
      $scope.formerrors = {};
      $scope.submit = true;
      $http({
         method: 'POST',
         url: '/restaurant/table/customer/add',
         data: $.param($scope.formdata),
         headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
      })
      .then(function(response) {
        console.log(response.data)
         show_table_customers();
         show_table();
         $scope.submit = false;
         $("#add-table-modal").modal("hide");
      }, function(rejection) {
         var errors = rejection.data;
         $scope.formerrors.pax = errors.pax;
         $scope.submit = false;
      });
    }
    $scope.table = {};
    $scope.has_table = false;
    function show_table() {
      $http({
          method : "GET",
          url : "/restaurant/table/list/serve",
      }).then(function mySuccess(response) {
          $scope.table = response.data.result;
          $scope.formdata.table_id = $scope.table[0];
          if( typeof Object.keys($scope.table)[0] === 'undefined' ){
            $scope.has_table = false;
          }else{
            $scope.has_table = true;
          }
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

    $scope.delete_table_customer = function(data) {
      console.log(data.$parent.customer_data.id);
      $http({
         method: 'POST',
         url: '/restaurant/table/customer/remove/'+data.$parent.customer_data.id,
         data: $.param($scope.formdata),
         headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
      })
      .then(function(response) {
        console.log(response.data);
        show_table_customers();
      }, function(rejection) {
         var errors = rejection.data;
      });
    }

    $scope.table_name = "";

    $scope.add_order = function(data) {
      if(!data.$parent.customer_data.has_billed_out){
        $("#add-order-modal").modal("show");
        $scope.table_customer_id = data.$parent.customer_data.id;
        $scope.table_name = data.$parent.customer_data.table_name;
        show_cart(data.$parent.customer_data.id);
        show_menu();
      }else{
        alertify.error("The customer has already billed out");
      }
    }
    function show_cart(table_customer_id) {
      $scope.table_customer_cart = {};
      $scope.table_customer_total = "";
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

    $scope.toggle_special_order = function(data) {
      var toggle = (data.cart_data.show_special_order?false:true);
      $scope.table_customer_cart["menu_"+data.cart_data.id].show_special_order = toggle;
    }
    $scope.toggle_update_quantity = function(data) {
      var toggle = (data.cart_data.show_update_quantity?false:true);
      $scope.table_customer_cart["menu_"+data.cart_data.id].show_update_quantity = toggle;
    }

    $scope.add_special_order = function(data) {
      // console.log(data.$parent.table_customer_id);
      $scope.formdata.special_order = data.cart_data.special_order;
      $scope.formdata.menu_id = data.cart_data.id;
      $http({
         method: 'POST',
         url: '/restaurant/table/order/cart/update/special_order/'+data.$parent.table_customer_id,
         data: $.param($scope.formdata),
         headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
      })
      .then(function(response) {
        // console.log(response.data);
      }, function(rejection) {
         var errors = rejection.data;
      });
    }
    $scope.update_quantity = function(data) {
      // console.log(data.$parent.table_customer_id);
      $scope.formdata.quantity = data.cart_data.quantity;
      $scope.formdata.menu_id = data.cart_data.id;
      $http({
         method: 'POST',
         url: '/restaurant/table/order/cart/update/quantity/'+data.$parent.table_customer_id,
         data: $.param($scope.formdata),
         headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
      })
      .then(function(response) {
        // show_cart(data.$parent.table_customer_id);
        $scope.table_customer_cart = response.data.cart;
        $scope.table_customer_total = response.data.total;
        console.log(response.data);
      }, function(rejection) {
         var errors = rejection.data;
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
        // console.log(response.data);
        $scope.submit = false;
        show_table_customers();
        $("#add-order-modal").modal('hide');
        $('#view-order-modal').modal('show');
        show_order(response.data.id);
      }, function(rejection) {
        var errors = rejection.data;
        $scope.formdata.ar_number_error = errors.ar_number;
        $scope.formdata.amount_error = errors.amount;
        $scope.formdata.date_payment_error = errors.date_payment;
        $scope.submit = false;
      }); 
    }

    function show_order(id) {
      $http({
        method : "GET",
        url : "/restaurant/table/order/view/"+id,
      }).then(function mySuccess(response) {
        // console.log(response.data);
        $scope.order = response.data.order;
        $scope.order_detail = response.data.order_detail;
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
         // alertify.success("Order has been placed.");
      }, function(rejection) {
         var errors = rejection.data;
         alertify.error(rejection.data.error);
         $scope.formdata.date_payment_error = errors.date_payment;
      });
    }

    $scope.orders = {};
    $scope.view_orders = function(data) {
      $http({
          method : "GET",
          url : "/restaurant/table/customer/orders/"+data.customer_data.id,
      }).then(function mySuccess(response) {
          $('#view-list-order-modal').modal('show');
          $scope.orders = response.data.result;
          $scope.table_name = data.customer_data.table_name;
      }, function myError(response) {
          console.log(response.statusText);
      });
    }

    $scope.bill_out = function(data) {
      // console.log(data.$parent.customer_data);
      if(data.$parent.customer_data.has_billed_out){
        // bill_preview
        $http({
          method : "GET",
          url : "/restaurant/table/customer/bill/preview/"+data.$parent.customer_data.id,
        }).then(function mySuccess(response) {
          console.log(response.data);
          $scope.bill_preview = response.data.result;
          $scope.bill_preview.total = data.$parent.customer_data.total;
          $scope.bill_preview.table_customer_id = data.$parent.customer_data.id;
          $('#bill-preview-modal').modal('show');
        }, function myError(response) {
          console.log(response.statusText);
        });
      }else{
        $http({
           method: 'POST',
           url: '/restaurant/table/customer/bill/preview/'+data.$parent.customer_data.id,
           data: $.param($scope.formdata),
           headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
        .then(function(response) {
          console.log(response.data);
          $scope.bill_preview = response.data.result;
          $scope.bill_preview.total = data.$parent.customer_data.total;
          $scope.bill_preview.table_customer_id = data.$parent.customer_data.id;
          show_table_customers();
          $('#bill-preview-modal').modal('show');
        }, function(rejection) {
           var errors = rejection.data;
           $scope.formdata.date_payment_error = errors.date_payment;
           $scope.submit = false;
        });
      }
    }

    $scope.make_bill = function(data) {
      // console.log(data.bill_preview.table_customer_id);
      $http({
         method: 'POST',
         url: '/restaurant/table/customer/bill/make/'+data.bill_preview.table_customer_id,
         data: $.param($scope.formdata),
         headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
      })
      .then(function(response) {
        $scope.bill = response.data.result;
        $scope.bill.table_name = response.data.table_name;
        $("#bill-preview-modal").modal("hide");
        $("#view-bill-modal").modal("show");
        show_table_customers();
      }, function(rejection) {
         var errors = rejection.data;
         $scope.formdata.date_payment_error = errors.date_payment;
         $scope.submit = false;
      });
    }

    $scope.view_bills = function(data) {
      var id = data.$parent.$parent.customer_data.id;
      $http({
          method : "GET",
          url : "/restaurant/table/customer/bill/list/"+id,
      }).then(function mySuccess(response) {
          console.log(response.data);
          $scope.bill = response.data.result;
          $scope.bill.table_name = response.data.table_name;
          $("#view-bill-modal").modal("show");
      }, function myError(response) {
          console.log(response.statusText);
      });
    }

    $('#settlement').dropdown();
    $scope.add_payment = function(data) {
      console.log(data);
      $scope.formdata.settlements_amount = {
        cash: 0,
        credit: 0,
        debit: 0,
        cheque: 0,
        guest_ledger: 0,
        send_bill: 0,
        free_of_charge: 0
      };
      $scope.formdata.total = data.bill_data.total;
      $scope.bill_id = data.bill_data.id;
      $("#payment-modal").modal("show");
      $('#settlement').dropdown('clear');
      $scope.formdata.settlement = {} ;
      $scope.formdata.excess = excess($scope);
      $scope.valid_payment = valid_payment($scope);

      $scope.formdata.settlements_payment.cash = false;
      $scope.formdata.settlements_payment.credit = false;
      $scope.formdata.settlements_payment.debit = false;
      $scope.formdata.settlements_payment.cheque = false;
      $scope.formdata.settlements_payment.guest_ledger = false;
      $scope.formdata.settlements_payment.send_bill = false;
      $scope.formdata.settlements_payment.free_of_charge = false;
    }

    $scope.make_payment = function(data) {
      // console.log(data.$parent.bill_id);
      $http({
         method: 'POST',
         url: '/restaurant/table/customer/payment/make/'+data.$parent.bill_id,
         data: $.param($scope.formdata),
         headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
      })
      .then(function(response) {
        $scope.bill = response.data.result;
        $scope.bill.table_name = response.data.table_name;
        $("#payment-modal").modal("hide");
        console.log(response.data)
      }, function(rejection) {
         var errors = rejection.data;
      });
    }
    $scope.formdata.settlements_payment = {};
    $scope.formdata.settlements_amount = {
      cash: 0,
      credit: 0,
      debit: 0,
      cheque: 0,
      guest_ledger: 0,
      send_bill: 0,
      free_of_charge: 0
    };
    $scope.input_payment = function() {
      $scope.formdata.excess = excess($scope);
      $scope.valid_payment = valid_payment($scope);
    }

    function excess($scope) {
      var excess = (
        $scope.formdata.settlements_amount.cash
      + $scope.formdata.settlements_amount.credit
      + $scope.formdata.settlements_amount.debit
      + $scope.formdata.settlements_amount.cheque
      + $scope.formdata.settlements_amount.guest_ledger
      + $scope.formdata.settlements_amount.send_bill
      + $scope.formdata.settlements_amount.free_of_charge
      ) - $scope.formdata.total;
      return (excess>=0?excess:0);
    }

    function valid_payment(argument) {
      var total_payment = (
        $scope.formdata.settlements_amount.cash
      + $scope.formdata.settlements_amount.credit
      + $scope.formdata.settlements_amount.debit
      + $scope.formdata.settlements_amount.cheque
      + $scope.formdata.settlements_amount.guest_ledger
      + $scope.formdata.settlements_amount.send_bill
      + $scope.formdata.settlements_amount.free_of_charge
      );
      return (total_payment>=$scope.formdata.total?true:false);
    }
    $scope.settlements_payment = function(data) {
      if($scope.formdata.settlement.includes('cash')){
        $scope.formdata.settlements_payment.cash = true;
      }else{
        $scope.formdata.settlements_payment.cash = false;
        $scope.formdata.settlements_amount.cash = 0;
        $scope.formdata.excess = excess($scope);
        $scope.valid_payment = valid_payment($scope);
      }
      if($scope.formdata.settlement.includes('credit')){
        $scope.formdata.settlements_payment.credit = true;
      }else{
        $scope.formdata.settlements_payment.credit = false;
        $scope.formdata.settlements_amount.credit = 0;
        $scope.formdata.excess = excess($scope);
        $scope.valid_payment = valid_payment($scope);
      }
      if($scope.formdata.settlement.includes('debit')){
        $scope.formdata.settlements_payment.debit = true;
      }else{
        $scope.formdata.settlements_payment.debit = false;
        $scope.formdata.settlements_amount.debit = 0;
        $scope.formdata.excess = excess($scope);
        $scope.valid_payment = valid_payment($scope);
      }
      if($scope.formdata.settlement.includes('cheque')){
        $scope.formdata.settlements_payment.cheque = true;
      }else{
        $scope.formdata.settlements_payment.cheque = false;
        $scope.formdata.settlements_amount.cheque = 0;
        $scope.formdata.excess = excess($scope);
        $scope.valid_payment = valid_payment($scope);
      }
      if($scope.formdata.settlement.includes('guest_ledger')){
        $scope.formdata.settlements_payment.guest_ledger = true;
      }else{
        $scope.formdata.settlements_payment.guest_ledger = false;
        $scope.formdata.settlements_amount.guest_ledger = 0;
        $scope.formdata.excess = excess($scope);
        $scope.valid_payment = valid_payment($scope);
      }
      if($scope.formdata.settlement.includes('send_bill')){
        $scope.formdata.settlements_payment.send_bill = true;
      }else{
        $scope.formdata.settlements_payment.send_bill = false;
        $scope.formdata.settlements_amount.send_bill = 0;
        $scope.formdata.excess = excess($scope);
        $scope.valid_payment = valid_payment($scope);
      }
      if($scope.formdata.settlement.includes('free_of_charge')){
        $scope.formdata.settlements_payment.free_of_charge = true;
      }else{
        $scope.formdata.settlements_payment.free_of_charge = false;
        $scope.formdata.settlements_amount.free_of_charge = 0;
        $scope.formdata.excess = excess($scope);
        $scope.valid_payment = valid_payment($scope);
      }
    }

    $('#view-bill-modal').on('hidden.bs.modal', function () {
      show_table_customers();
    });

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