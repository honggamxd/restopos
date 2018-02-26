@extends('layouts.main')

@section('title', 'Purchases - List')

@section('css')
<style type="text/css">

</style>
@endsection
@section('breadcrumb')
<div class="active section">Purchases</div>
<i class="right angle icon divider"></i>
<a class="section" href="/purchase/create">Create</a>
@endsection
@section('content')
<h1 style="text-align: center;">Purchases</h1>
<div class="col-sm-12">
  <form class="form-horizontal ui form">
    <div class="field">
      <div class="six fields">
        <div class="field">
          <label>Search Purchase Order Number</label>
          <div class="ui icon input focus">
            <input type="text" placeholder="Search..." ng-model="search_string" ng-change="search()">
            <i class="search icon"></i>
          </div>
        </div>

      </div>
    </div>
  </form>
  <div class="table-responsive">
    <table class="ui single line unstackable table">
      <thead>
        <tr>
          <th class="center aligned middle aligned">PO #</th>
          <th class="center aligned middle aligned">Date Time</th>
          <th class="center aligned middle aligned">Total Purchases</th>
          <th class="center aligned middle aligned">Comments</th>
        </tr>
      </thead>
      <tbody ng-init="total=0" ng-cloak>
        <tr ng-repeat="item in purchases">
          <td class="center aligned middle aligned">
            <a ng-href="/purchase/view/@{{item.id}}">
              @{{item.po_number}}
            </a></td>
          <td class="center aligned middle aligned">@{{item.created_at}}</td>
          <td class="right aligned middle aligned">@{{item.total|currency:""}}</td>
          <td class="center aligned middle aligned">@{{item.comments}}</td>
        </tr>
      </tbody>
        <tfoot>
          <tr ng-if="purchases | isEmpty">
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
      </table>
      <div ng-bind-html="pages" class="text-center" ng-cloak></div>
  </div>
</div>
@endsection

@section('modals')


@endsection

@push('scripts')
<script type="text/javascript">  
  app.controller('content-controller', function($scope,$http, $sce, $window) {
    $scope.loading = true;
    $scope.search_string = null;
    show_purchases();
    $scope.search = _.debounce(function(){
      show_purchases();
    },250);
    function show_purchases(myUrl) {
      myUrl = (typeof myUrl !== 'undefined') && myUrl !== "" ? myUrl : '/api/purchase/list';
      $scope.loading = true;
      $scope.purchases = {};
      $scope.pages = "";
      $http({
        method : "GET",
        url : myUrl,
        params: {
          search: $scope.search_string,
        },
      }).then(function mySuccess(response) {
        $scope.purchases = response.data.result.data;
        $scope.pages = $sce.trustAsHtml(response.data.pagination);
        $scope.loading = false;
      }, function myError(rejection) {
          if(rejection.status != 422){
            request_error(rejection.status);
          }else if(rejection.status == 422){
            var errors = rejection.data;
          }
        $scope.loading = false;
      });
    }
  });
</script>
@endpush