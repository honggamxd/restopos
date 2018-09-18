@extends('layouts.main')

@section('title', 'Menu Popularity Report')

@section('css')
<style type="text/css">

</style>
@endsection
@section('breadcrumb')
<a class="section hideprint" href="/reports">Reports</a>
<i class="right angle icon divider"></i>
<div class="active section">Invoice Number Search</div>
@endsection
@section('content')
 
<div class="col-sm-12">
  <div>
    <div class="checkbox">
      <!-- <label><input type="checkbox" ng-model="show_paging" ng-change="toggle_paging()">Paging</label> -->
    </div>
  </div>
  <h1 style="text-align: center;">Invoice Number Search</h1>
  <form class="form-inline">
    <div class="form-group">
      <div class="ui action input form-group">
        <div ng-hide="hide_outlet">
          <input type="text" name="invoice_number" id="invoice_number" class="form-control" placeholder="Search Invoice Number" ng-change="search()" ng-model="search_string">
        </div>
      </div>
    </div>
  </form>
  <br>

  <div class="table-responsive">
  <table class="ui unstackable celled structured table">
    <thead>
      <tr>
        <th rowspan="2" class="center aligned middle aligned">Date</th>
        <th rowspan="2" class="center aligned middle aligned">Outlet</th>
        <th rowspan="2" class="center aligned middle aligned">Check #</th>
        <th rowspan="2" class="center aligned middle aligned">Invoice #</th>
        <th rowspan="2" class="center aligned middle aligned">Guest Name</th>
        <th rowspan="2" class="center aligned middle aligned"># of Pax</th>
        <th rowspan="2" class="center aligned middle aligned"># of SC/PWD</th>
        <th rowspan="2" class="center aligned middle aligned">Server</th>
        <th rowspan="2" class="center aligned middle aligned">Cashier</th>
        <th rowspan="2" class="center aligned middle aligned">NET Amount</th>
      </tr>
    </thead>
    <tbody>
      <tr ng-repeat="item in bills" ng-class="{'warning':item.type=='bad_order'}" ng-cloak>
        <td class="center aligned middle aligned">@{{item.date_}}</td>
          <td class="center aligned middle aligned">@{{item.restaurant_name}}</td>
          <td class="center aligned middle aligned"><a href="/restaurant/bill/@{{item.id}}" target="_blank">
            <p ng-if="item.type=='good_order'">@{{item.check_number}}</p>
            <p ng-if="item.type=='bad_order'" title="@{{item.reason_cancelled}}">@{{item.check_number}}</p>
          </a></td>
          <td class="center aligned middle aligned">@{{item.invoice_number}}</td>
          <td class="center aligned middle aligned">@{{item.guest_name}}</td>
          <td class="center aligned middle aligned">@{{item.pax}}</td>
          <td class="center aligned middle aligned">@{{item.sc_pwd}}</td>
          <td class="center aligned middle aligned">@{{item.server_name}}</td>
          <td class="center aligned middle aligned">@{{item.cashier_name}}</td>
          <td class="right aligned middle aligned">@{{item.net_billing |chkNull|currency:""}}</td>
      </tr>
    </tbody>
    <tfoot>
      <tfoot>
        <tr ng-if="bills | isEmpty">
          <td colspan="20" style="text-align: center;">
            <h1 ng-if="loading">
              <img src="{{asset('assets/images/loading.gif')}}" style="height: 70px;">
              <br>
              LOADING
            </h1>
            <h1>
              <span ng-if="!loading" ng-cloak>NO DATA</span>
              <span ng-if="loading" ng-cloak></span>
            </h1>
          </td>
        </tr>
      </tfoot>

    </tfoot>
  </table>
  </div>
  <div ng-bind-html="pages" class="text-center" ng-cloak></div>
</div>
@endsection

@section('modals')

@endsection

@push('scripts')
<script type="text/javascript">
  $(document).ready(function() {

  });
  
  app.controller('content-controller', function($scope,$http, $sce) {
    $scope.bills = {};
    $scope.search_string = "{{ $search_string }}";
    $scope.loading = true;

    $scope.search = _.debounce(function() {
      $scope.show_results();
    },250);
    

    $scope.show_results = function(myUrl) {
      $scope.loading = true;
      $scope.bills = {};
      myUrl = (typeof myUrl !== 'undefined') && myUrl !== "" ? myUrl : '/api/reports/general/search/invoice_number';
      $http({
       method: "GET",
       url: myUrl,
       params: {
         search_string: $scope.search_string
       },
     }).then(function mySuccess(response) {
       $scope.bills = response.data.result.data;
       $scope.pages = $sce.trustAsHtml(response.data.pagination);
       $scope.loading = false;
     }, function(rejection) {
       $scope.loading = false;
       if (rejection.status != 422) {
         request_error(rejection.status);
       } else if (rejection.status == 422) {
         console.log(rejection.statusText);
       }
     });
    }
    $scope.show_results();

    $(document).on('click','.pagination li a',function(e) {
      e.preventDefault();
      $scope.show_results(e.target.href);
    });

    
  });
  
</script>
@endpush