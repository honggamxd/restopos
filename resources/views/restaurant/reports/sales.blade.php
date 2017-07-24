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
  <div class="table-responsive">
    <table class="ui unstackable table">
      <thead>
        <tr>
          <th>Date</th>
          <th>Check #</th>
          <th># of Pax</th>
          <th ng-repeat="category_name in category">@{{category_name}}</th>
        </tr>
      </thead>
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
  var app = angular.module('main', []);
  app.controller('content-controller', function($scope,$http, $sce) {
    show_category();
    function show_category() {
      $http({
          method : "GET",
          url : "/restaurant/menu/category/1",
      }).then(function mySuccess(response) {
          // console.log(response.data.result)
          $scope.category = response.data.result;
      }, function myError(response) {
          console.log(response.statusText);
      });
    }
  });
  angular.bootstrap(document, ['main']);
</script>
@endsection