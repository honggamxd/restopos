@extends('layouts.main')

@section('title', 'Restaurant POS')

@section('css')
<style type="text/css">

</style>
@endsection
@section('breadcrumb')


@if(Session::get('users.user_data')->privilege=="admin")
  <a class="section hideprint" href="/reports">Reports</a>
  <i class="right angle icon divider"></i>
  <div class="active section">F&B Revenue</div>
@else
  <div class="active section">F&B Revenue</div>
@endif
@endsection
@section('content')
 
<div class="col-sm-12">

  <h1 style="text-align: center;">Food and Beverage Revenue Report<br><small><b>Date From:</b> {{date("F d, Y",strtotime($date_from))}} <b>Date To:</b> {{date("F d, Y",strtotime($date_to))}} </small></h1>
  <div>
    <div class="checkbox">
      <label><input type="checkbox" ng-model="show_sales">Show Sales</label>
    </div>
    <div class="checkbox">
      <label><input type="checkbox" ng-model="show_settlements">Show Settlements</label>
    </div>
<!--     <div class="checkbox">
      <label><input type="checkbox" ng-model="show_paging" ng-change="toggle_paging()">Paging</label>
    </div> -->
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
          <th class="center aligned middle aligned" ng-show="show_settlements" colspan="{{ count($settlements)+3 }}">Mode of Payments / Settlements</th>
          <!-- <th rowspan="2" class="center aligned middle aligned" ng-show="show_settlements">Excess</th> -->
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
            @if($settlement=="cash")
              <td class="right aligned middle aligned" ng-show="show_settlements"> {{bill_data.<?php echo $settlement; ?>-bill_data.excess |currency:""}}</td>
            @else
              <td class="right aligned middle aligned" ng-show="show_settlements"> {{bill_data.<?php echo $settlement; ?> |currency:""}}</td>
            @endif
          @endforeach
          <td class="right aligned middle aligned" ng-show="show_settlements"></td>
          <td class="right aligned middle aligned" ng-show="show_settlements"></td>
          <td class="right aligned middle aligned" ng-show="show_settlements"></td>
          <!-- <td class="right aligned middle aligned" ng-show="show_settlements">@{{bill_data.excess |currency:""}}</td> -->
        </tr>
      </tbody>
      <tfoot ng-cloak>
        <tr ng-cloak>
          <th class="right aligned middle aligned" colspan="2">Total>>></th>
          <th class="center aligned middle aligned">@{{footer.pax}}</th>
          @foreach ($categories as $category)
            <th class="right aligned middle aligned" ng-show="show_sales"> {{footer.<?php echo $category; ?> |currency:""}}</th>
          @endforeach
          <th class="right aligned middle aligned" ng-show="show_sales">@{{footer.total|currency:""}}</th>
          @foreach ($settlements as $settlement)
            <th class="right aligned middle aligned" ng-show="show_settlements"> {{footer.<?php echo $settlement; ?> |currency:""}}</th>
          @endforeach
          <th class="right aligned middle aligned" ng-show="show_settlements"></th>
          <th class="right aligned middle aligned" ng-show="show_settlements"></th>
          <th class="right aligned middle aligned" ng-show="show_settlements"></th>
          <!-- <th class="right aligned middle aligned" ng-show="show_settlements">@{{footer.excess|currency:""}}</th> -->
        </tr>
      </tfoot>
    </table>
  </div>
  <div ng-bind-html="paging" class="text-center" ng-show="show_paging"></div>
  @if(Session::get('users.user_data')->privilege=="restaurant_cashier")
    <a href="/restaurant/reports/print/" target="_blank">View Printable Version</a>
  @else
  <a href="/reports/print/all?{{$_SERVER['QUERY_STRING']}}" target="_blank">View Printable Version</a>
  @endif
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
  var app = angular.module('main', ['ngSanitize']);
  app.controller('content-controller', function($scope,$http, $sce) {
    $scope.show_sales = true;
    $scope.show_paging = true;
    $scope.show_settlements = true;
    $scope.toggle_paging = function() {
      show_reports();
    }
    show_reports();
    $(document).on("click",".paging",function(e) {
      show_reports(e.target.id);
    });
    function show_reports(page=1) {
      $http({
          method : "GET",
          url : "/api/reports/general/f_and_b",
          params: {
            "date_from":"{{$date_from}}",
            "date_to":"{{$date_to}}",
            "paging":$scope.show_paging,
            "page":page,
            "display_per_page":50,
          }
      }).then(function mySuccess(response) {
          $scope.bills = response.data.result;
          $scope.footer = response.data.footer;
          $scope.paging = $sce.trustAsHtml(response.data.paging);
      }, function myError(response) {
          console.log(response.statusText);
      });
    }

  });
  angular.bootstrap(document, ['main']);
</script>
@endsection