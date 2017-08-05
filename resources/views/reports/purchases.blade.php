@extends('layouts.main')

@section('title', 'Restaurant POS')

@section('css')
<style type="text/css">

</style>
@endsection
@section('breadcrumb')
<a class="section hideprint" href="/reports">Reports</a>
<i class="right angle icon divider"></i>
<div class="active section">Purchases</div>
@endsection
@section('content')
 
<div class="col-sm-12">
  <div>
    <div class="checkbox">
      <!-- <label><input type="checkbox" ng-model="show_paging" ng-change="toggle_paging()">Paging</label> -->
    </div>
  </div>
  <h1 style="text-align: center;">Purchases Report<br><small><b>Date From:</b> {{date("F d, Y",strtotime($date_from))}} <b>Date To:</b> {{date("F d, Y",strtotime($date_to))}} </small></h1>
  <div class="table-responsive">
  <table class="ui unstackable striped celled structured table">
    <thead>
      <tr>
        <th class="center aligned middle aligned">ID</th>
        <th class="center aligned middle aligned">Date</th>
        <th class="center aligned middle aligned">PO #</th>
        <th class="center aligned middle aligned">Cost</th>
        <th class="center aligned middle aligned">Comments</th>
      </tr>
    </thead>
    <tbody>
      <tr ng-repeat="purchase_data in purchases">
        <td class="center aligned middle aligned"><a href="/purchase/view/@{{purchase_data.id}}">@{{purchase_data.id}}</a></td>
        <td class="center aligned middle aligned">@{{purchase_data.date_}}</td>
        <td class="center aligned middle aligned">@{{purchase_data.po_number}}</td>
        <td class="right aligned middle aligned">@{{purchase_data.total|currency:""}}</td>
        <td class="center aligned middle aligned">@{{purchase_data.comments}}</td>
      </tr>
    </tbody>
    <tfoot>
      <tr>
        <th class="center aligned middle aligned" colspan="3">TOTAL</th>
        <th class="right aligned middle aligned">@{{footer.total|currency:""}}</th>
        <th class="center aligned middle aligned"></th>
      </tr>
    </tfoot>
  </table>
  </div>
  <div ng-bind-html="paging" class="text-center" ng-show="show_paging"></div>
  <a href="/reports/print/purchases?{{$_SERVER['QUERY_STRING']}}" target="_blank">View Printable Version</a>
</div>
@endsection

@section('modals')

@endsection

@section('scripts')
<script type="text/javascript">
  $(document).ready(function() {
  });
  var app = angular.module('main', ['ngSanitize']);
  app.controller('content-controller', function($scope,$http, $sce) {
    $scope.show_paging = true;
    $(document).on("click",".paging",function(e) {
      show_reports(e.target.id);
    });
    show_reports();
    function show_reports(page=1) {
      $http({
          method : "GET",
          url : "/api/reports/general/purchases",
          params: {
            "date_from":"{{$date_from}}",
            "date_to":"{{$date_to}}",
            "paging":$scope.show_paging,
            "page":page,
            "display_per_page":50,
          }
      }).then(function mySuccess(response) {
          $scope.purchases = response.data.result;
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