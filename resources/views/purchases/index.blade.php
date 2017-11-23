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
<h1 style="text-align: center;">Purchase</h1>
<div class="col-sm-9">
  <div class="row">
    <div class="col-sm-10">
      <label>Search Item</label>
      <div class="ui icon input fluid">
        <i class="search icon"></i>
        <input type="text" placeholder="Search" id="search-item-name" ng-model="search_item_name">
      </div> 
    </div>
<!--     <div class="col-sm-5">
      <label>Search Category</label>
      <div class="ui icon input fluid">
        <i class="search icon"></i>
        <input type="text" placeholder="Search">
      </div>
    </div> -->
    <div class="col-sm-2">
      <div class="form-group">
        <label>&nbsp;</label>
        <button class="btn btn-primary btn-block" onclick="$('#add-item-modal').modal('show')"><span class="glyphicon glyphicon-plus"></span> Add Items</button>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-12">
      <div class="table-responsive">
        <table class="ui single line unstackable table">
          <thead>
            <tr>
              <th class="center aligned middle aligned">Category</th>
              <th class="center aligned middle aligned">Item</th>
              <th class="center aligned middle aligned">Unit</th>
              <th class="center aligned middle aligned">Qty</th>
              <th class="center aligned middle aligned">Cost Price</th>
              <th class="center aligned middle aligned">Total</th>
              <th class="center aligned middle aligned"></th>
            </tr>
          </thead>
          <tbody ng-cloak>
            <tr ng-repeat="item in cart.items" ng-class="item.quantity == 0 ? 'error' : ''">
              <td class="center aligned middle aligned">@{{item.category}}</td>
              <td class="center aligned middle aligned" style="width: 100%">@{{item.item_name}}</td>
              <td class="center aligned middle aligned">@{{item.unit}}</td>
              <td class="middle aligned center aligned" ng-init="item.edit_cost_price=false">
                <div class="ui input" ng-show="item.edit_quantity">
                  <input type="number" placeholder="QTY" ng-model="item.quantity" ng-blur="update_cart(this,'quantity')" focus-me="item.edit_quantity">
                </div>
                <p style="cursor: pointer;" ng-hide="item.edit_quantity" ng-click="edit_quantity(this)" data-balloon="Click to Edit" data-balloon-pos="left">@{{item.quantity}}</p>
              </td>
              <td class="middle aligned right aligned" ng-init="item.edit_cost_price=false">
                <div class="ui input" ng-show="item.edit_cost_price">
                  <input type="number" step="0.01" placeholder="Cost Price" ng-model="item.cost_price" ng-blur="update_cart(this,'cost_price')" focus-me="item.edit_cost_price">
                </div>
                <p style="cursor: pointer;" ng-hide="item.edit_cost_price" ng-click="edit_cost_price(this)" data-balloon="Click to Edit" data-balloon-pos="left">@{{item.cost_price|currency:""}}</p>
              </td>
              <td class="right aligned middle aligned">@{{item.total | currency:""}}</td>
              <td class="right aligned middle aligned"><button type="button" class="btn btn-danger" ng-click="delete_item(this)">&times;</button></td>
            </tr>
          </tbody>
          <tfoot ng-cloak>
            <tr>
              <th class="right aligned middle aligned" colspan="5">TOTAL</th>
              <th class="right aligned middle aligned"><span ng-if="cart.total!=0">@{{cart.total|currency:""}}</span></th>
              <th class="right aligned middle aligned"></th>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
</div>
<div class="col-sm-3">
  <form ng-submit="make_purchase()">
    <input type="hidden" ng-model="cart.items">
    <div class="form-group">
      <label>PO Number</label>
      <input type="text" name="" placeholder="PO Number" class="form-control" ng-model="cart.info.po_number" ng-blur="add_info_cart(this)">
      <p class="help-block" ng-cloak>@{{formerrors.po_number[0]}}</p>
    </div>
    <div class="form-group">
      <label>Comments</label>
      <textarea placeholder="Comments" class="form-control" ng-model="cart.info.comments" ng-blur="add_info_cart(this)"></textarea>
      <p class="help-block" ng-cloak>@{{formerrors.comments[0]}}</p>
    </div>
    <div class="form-group">
      <label>Controls</label>
      <button type="submit" class="btn btn-primary btn-block" ng-disabled="submit"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>
      <button type="button" class="btn btn-danger btn-block" ng-click="destroy_cart()"><span class="glyphicon glyphicon-trash"></span> Cancel</button>
    </div>
  </form>
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
            <p class="help-block" ng-cloak>@{{formerrors.category[0]}}</p>
          </div>
<!--           <div class="form-group">
            <label>Subcategory:</label>
            <input type="text" name="subcategory" class="form-control" placeholder="Enter Subcategory" ng-model="formdata.subcategory">
            <p class="help-block" ng-cloak>@{{formerrors.subcategory[0]}}</p>
          </div> -->
          <div class="form-group">
            <label>Item Name:</label>
            <input type="text" name="item_name" class="form-control" placeholder="Enter Item Name" ng-model="formdata.item_name">
            <p class="help-block" ng-cloak>@{{formerrors.item_name[0]}}</p>
          </div>
          <div class="form-group">
            <label>Unit of Measure:</label>
            <input type="text" name="unit" class="form-control" placeholder="Enter Unit of Measure" ng-model="formdata.unit">
            <p class="help-block" ng-cloak>@{{formerrors.unit[0]}}</p>
          </div>
<!--           <div class="form-group">
            <label>Type:</label>
            <select class="form-control" ng-model="formdata.type" ng-init="formdata.type='ingredient'">
              <option value="ingredient">Ingredient</option>
              <option value="general">General Item</option>
            </select>
            <p class="help-block" ng-cloak>@{{formerrors.type[0]}}</p>
          </div> -->
          <div class="form-group">
            <label>Cost Price:</label>
            <input type="number" step="0.01" name="cost_price" class="form-control" placeholder="Enter Cost Price" ng-model="formdata.cost_price">
            <p class="help-block" ng-cloak>@{{formerrors.cost_price[0]}}</p>
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
    $scope.formdata = {};
    $scope.formerrors = {};
    $scope.cart = {};
    $scope.cart.info = {};
    $scope.cart.info.po_number = "";
    $scope.cart.info.comments = "";
    $scope.search_item_name = "";

    $scope.edit_cost_price = function(data) {
      data.item.edit_cost_price = (data.item.edit_cost_price?false:true);
    }
    $scope.edit_quantity = function(data) {
      data.item.edit_quantity = (data.item.edit_quantity?false:true);
    }

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
        show_cart();
      }, function(rejection) {
         var errors = rejection.data;
         console.log(errors);
         $scope.formerrors = errors;
      });
    }

    $scope.delete_item = function(data) {
      $http({
         method: 'DELETE',
         url: '/api/purchase/cart/item/delete/'+data.item.inventory_item_id,
         data: $.param($scope.formdata),
         headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
      })
      .then(function(response) {
        // console.log(response.data);
        // show_cart();
        $scope.cart.items = response.data.items;
        $scope.cart.total = response.data.total;
        $scope.cart.info = response.data.info;
      }, function(rejection) {
         var errors = rejection.data;
         console.log(errors);
         $scope.formerrors = errors;
      });
    }

    $scope.update_cart = function(data,type) {
      if(type=="quantity"){
        data.item.edit_quantity = (data.item.edit_quantity?false:true);
      }else{
        data.item.edit_cost_price = (data.item.edit_cost_price?false:true);
      }
      $http({
         method: 'PUT',
         url: '/api/purchase/cart/item/update/'+data.item.inventory_item_id,
         data: $.param({
          'quantity':data.item.quantity,
          'cost_price':data.item.cost_price,
         }),
         headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
      })
      .then(function(response) {
        // console.log(response.data);
        // $scope.cart.total = response.data.total;
        $scope.cart.items = response.data.items;
        $scope.cart.total = response.data.total;
        $scope.cart.info = response.data.info;

      }, function(rejection) {
         var errors = rejection.data;
         console.log(errors);
         $scope.formerrors = errors;
      });
    }

    $scope.add_info_cart = function(data) {
      $http({
         method: 'POST',
         url: '/api/purchase/cart/info',
         data: $.param({
          'po_number':$scope.cart.info.po_number,
          'comments':$scope.cart.info.comments,
         }),
         headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
      })
      .then(function(response) {
        // console.log(response.data);
        // $scope.cart.total = response.data.total;
      }, function(rejection) {
         var errors = rejection.data;
         console.log(errors);
         $scope.formerrors = errors;
      });
    }

    $scope.destroy_cart = function() {
      $http({
         method: 'DELETE',
         url: '/api/purchase/cart/delete',
         headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
      })
      .then(function(response) {
        $scope.cart.items = response.data.items;
        $scope.cart.total = response.data.total;
        $scope.cart.info = response.data.info;
        // show_cart();
        // console.log(response.data);
        // $scope.cart.total = response.data.total;
      }, function(rejection) {
         var errors = rejection.data;
         console.log(errors);
         $scope.formerrors = errors;
      });
    }

    $scope.make_purchase = function(){
      if(isEmpty($scope.cart.items)){
        alertify.error("Cart is empty. Please add items in the cart.");
      }else{
      $scope.submit = true;
        $http({
           method: 'POST',
           url: '/api/purchase/make',
           data: $.param({
            'items':$scope.cart.items,
            'po_number':$scope.cart.info.po_number,
            'comments':$scope.cart.info.comments,
           }),
           headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
        .then(function(response) {
          // console.log(response.data);
          $window.location.assign('/purchase/view/'+response.data);
          $scope.submit = false;
        }, function(rejection) {
           var errors = rejection.data;
           console.log(errors);
           $scope.formerrors = errors;
           if($scope.formerrors.hasOwnProperty('items')){
             alertify.error($scope.formerrors.items[0]);
            
           }
          $scope.submit = false;
        });
      }
    }

    show_cart();


    $( "#search-item-name" ).autocomplete({
      source: "/api/search/inventory/item/item_name/value",
      select: function( event, ui ) {
        add_items_cart(ui.item.id);
        $scope.search_item_name = "";
      }
    });


    function add_items_cart(id) {
      $http({
         method: 'POST',
         url: '/api/purchase/cart/item/add/'+id,
         data: $.param($scope.formdata),
         headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
      })
      .then(function(response) {
        // console.log(response.data);
        $scope.cart.items = response.data.items;
        $scope.cart.total = response.data.total;
        $scope.cart.info = response.data.info;
        // show_cart();
      }, function(rejection) {
         var errors = rejection.data;
         console.log(errors);
         $scope.formerrors = errors;
      });
    }


    function show_cart() {
      $http({
          method : "GET",
          url : "/api/purchase/cart",
      }).then(function mySuccess(response) {
          // console.log(response.data.items);
          $scope.cart.items = response.data.items;
          $scope.cart.total = response.data.total;
          $scope.cart.info = response.data.info;
      }, function myError(rejection) {
          if(rejection.status != 422){
            request_error(rejection.status);
          }else if(rejection.status == 422){
            var errors = rejection.data;
          }
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