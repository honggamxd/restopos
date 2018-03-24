@extends('layouts.main')

@section('title', 'Purchase Request')

@section('css')
<style type="text/css">


</style>
@endsection
@section('breadcrumb')
<a class="section" href="{{route('inventory.purchase-request.list')}}">Purchase Request</a>
<i class="right angle icon divider"></i>
<div class="active section">Create Purchase Request</div>
@endsection
@section('content')
<h1 style="text-align: center;">Create Purchase Request</h1>
<div class="col-sm-9">
  <div class="row">
    <div class="col-sm-12">
      <label>Search Item</label>
      <div class="ui icon input fluid">
        <i class="search icon"></i>
        <input type="text" placeholder="Search" id="search-item-name" ng-model="search_item_name">
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
                <th class="center aligned middle aligned">Stocks</th>
                <th class="center aligned middle aligned">Qty</th>
                <th class="center aligned middle aligned"></th>
              </tr>
            </thead>
            <tbody ng-cloak>
              <tr ng-repeat="item in cart.items" ng-init="item.edit_quantity=false" ng-class="item.quantity == 0 ? 'error' : (item.valid == 0 ? 'warning' : '')">
                <td class="center aligned middle aligned">@{{item.category}}</td>
                <td class="center aligned middle aligned" style="width: 100%">@{{item.item_name}}</td>
                <td class="center aligned middle aligned">@{{item.unit}}</td>
                <td class="center aligned middle aligned">@{{item.stocks}}</td>
                <td class="center aligned middle aligned">
                  <div class="ui input" ng-show="item.edit_quantity">
                    <input type="number" placeholder="Cost Price" ng-model="item.quantity" ng-blur="update_cart(this)" focus-me="item.edit_quantity">
                  </div>
                  <p ng-hide="item.edit_quantity" ng-click="edit_quantity(this)" style="cursor: pointer;" data-balloon="Click to Edit" data-balloon-pos="left">@{{item.quantity}}</p>
                </td>
                <td class="right aligned middle aligned"><button type="button" class="btn btn-danger" ng-click="delete_item(this)">&times;</button></td>
              </tr>
            </tbody>  
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@section('modals')

@endsection

@push('scripts')
<script type="text/javascript">
    app.controller('content-controller', function($scope,$http, $sce, $window) {
        $scope.formdata = {};
        $scope.formerrors = {};
        $scope.submit = false;
        $scope.loading = true;
    });
</script>
@endpush