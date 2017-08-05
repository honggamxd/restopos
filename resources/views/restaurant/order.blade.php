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

.order-table{
  width: 100%;
  border-collapse: collapse;
  margin: 0;
}

@media print{
  .hideprint{
    display: none !important;
  }

  *{
    background-color: white !important;
  }
}

</style>
@endsection
@section('breadcrumb')
<a class="section hideprint" href="/restaurant">Restaurant</a>
<i class="right angle icon divider hideprint"></i>
<div class="active section hideprint">Order</div>
@endsection
@section('content')
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
<a href="javascript:void(0);" class="btn btn-primary hideprint" onclick="window.print()"><span class="glyphicon glyphicon-print"></span> Print</a>
<a href="javascript:void(0);" class="btn btn-danger hideprint" onclick="window.close()"><span class="glyphicon glyphicon-remove"></span> Close</a>
@endsection

@section('scripts')
<script type="text/javascript">
  $('table').tablesort();
  
  shortcut.add("x",function() {
    window.close();
  });
  shortcut.add("esc",function() {
    window.close();
  });
  $(document).ready(function() {
    setTimeout(function(){ window.print(); }, 500);
  });
  var app = angular.module('main', []);
  app.controller('content-controller', function($scope,$http, $sce) {
        $scope.order = {!! $order !!};
        $scope.order_detail = {!! $order_detail !!};
  });
  angular.bootstrap(document, ['main']);
</script>
@endsection