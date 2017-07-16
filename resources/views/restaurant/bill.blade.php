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
<img src="/assets/images/logo.png" style="margin-right: auto;margin-left: auto; width: 150px;display: block;">
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
      <td ng-bind="items.menu"></td>
      <td style="text-align: center;" ng-bind="items.quantity"></td>
      <td style="text-align: right;">@{{items.price|currency:""}}</td>
    </tr>
  </tbody>
</tbody>
</table>
<br>
<p>Server:</p>
<a href="#" class="btn btn-primary hideprint" onclick="window.print()"><span class="glyphicon glyphicon-print"></span> Print</a>
<a href="#" class="btn btn-danger hideprint" onclick="window.close()"><span class="glyphicon glyphicon-remove"></span> Close</a>
@endsection

@section('scripts')
<script type="text/javascript">
  $('table').tablesort();
  var app = angular.module('main', []);
  app.controller('content-controller', function($scope,$http, $sce) {

  });
  angular.bootstrap(document, ['main']);
</script>
@endsection