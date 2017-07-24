@extends('layouts.main')

@section('title', 'Restaurant POS')

@section('css')
<style type="text/css">

</style>
@endsection
@section('breadcrumb')
<a class="section hideprint" href="/reports">Reports</a>
<i class="right angle icon divider"></i>
<a class="section hideprint" href="/reports">Restaurant</a>
<i class="right angle icon divider"></i>
<div class="active section">Sales Report</div>
@endsection
@section('content')
 
<div class="col-sm-12">
  <div>
    <div class="checkbox">
      <label><input type="checkbox" ng-model="show_sales">Show Sales</label>
    </div>
    <div class="checkbox">
      <label><input type="checkbox" ng-model="show_settlements">Show Settlements</label>
    </div>
  </div>
  
  <div class="table-responsive">
    <table class="ui unstackable striped celled structured table">
      <thead>
        <tr>
          <th rowspan="2" class="center aligned middle aligned">Date</th>
          <th rowspan="2" class="center aligned middle aligned">Check #</th>
          <th rowspan="2" class="center aligned middle aligned"># of Pax</th>
          @foreach ($categories as $category)
            <th rowspan="2" class="center aligned middle aligned" ng-show="show_sales">{{$category}}</th>
          @endforeach
          <th rowspan="2" class="center aligned middle aligned" ng-show="show_sales">Total Ammount</th>
          <th class="center aligned middle aligned" ng-show="show_settlements" colspan="{{ count($settlements)+3 }}">Mode of Payments / Settle</th>
          <th rowspan="2" class="center aligned middle aligned" ng-show="show_settlements">Excess</th>
        </tr>
        <tr>
          @foreach ($settlements as $settlement)
            <th class="center aligned middle aligned" ng-show="show_settlements">{{ settlements($settlement) }}</th>
          @endforeach
          <th class="center aligned middle aligned" ng-show="show_settlements">Cancelled / Void</th>
          <th class="center aligned middle aligned" ng-show="show_settlements">BOD Charge</th>
          <th class="center aligned middle aligned" ng-show="show_settlements">Staff Charge</th>
        </tr>
      </thead>
      <tbody ng-cloak>
        <tr ng-repeat="bill_data in bills">
          <td class="center aligned middle aligned">@{{bill_data.date_}}</td>
          <td class="center aligned middle aligned"><a href="/restaurant/bill/@{{bill_data.id}}" target="_blank"><p>@{{bill_data.id}}</p></a></td>
          <td class="center aligned middle aligned">@{{bill_data.pax}}</td>
          @foreach ($categories as $category)
            <td class="right aligned middle aligned" ng-show="show_sales"> {{bill_data.<?php echo $category; ?> |currency:""}}</td>
          @endforeach
          <td class="right aligned middle aligned" ng-show="show_sales"> @{{bill_data.total |currency:""}}</td>
          @foreach ($settlements as $settlement)
            <td class="right aligned middle aligned" ng-show="show_settlements"> {{bill_data.<?php echo $settlement; ?> |currency:""}}</td>
          @endforeach
          <td class="right aligned middle aligned" ng-show="show_settlements"></td>
          <td class="right aligned middle aligned" ng-show="show_settlements"></td>
          <td class="right aligned middle aligned" ng-show="show_settlements"></td>
          <td class="right aligned middle aligned" ng-show="show_settlements">@{{bill_data.excess |currency:""}}</td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
@endsection

@section('modals')

@endsection

@section('scripts')
<script type="text/javascript">
  $(document).ready(function() {
    $("#date-from,#date-to").datepicker();
  });
  // $('.ui.checkbox').checkbox('enable');
  var app = angular.module('main', []);
  app.controller('content-controller', function($scope,$http, $sce) {
    $scope.show_sales = true;
    $scope.show_settlements = true;
    show_reports();

    function show_reports() {
      $http({
          method : "GET",
          url : "/api/reports/restaurant/all",
      }).then(function mySuccess(response) {
          $scope.bills = response.data.result;
      }, function myError(response) {
          console.log(response.statusText);
      });
    }

  });
  angular.bootstrap(document, ['main']);
</script>
@endsection