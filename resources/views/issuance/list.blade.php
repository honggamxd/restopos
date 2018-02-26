@extends('layouts.main')

@section('title', 'Issuance - List')

@section('css')
<style type="text/css">

</style>
@endsection
@section('breadcrumb')
<div class="active section">Issuance</div>
<i class="right angle icon divider"></i>
<a class="section" href="/issuance/create">Create</a>
@endsection
@section('content')
<h1 style="text-align: center;">Issuance</h1>
<div class="col-sm-12">
  <form class="form-horizontal ui form">
    <div class="field">
      <div class="six fields">
        <div class="field">
          <label>Search Issuance Number</label>
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
          <th class="center aligned middle aligned">Issuance #</th>
          <th class="center aligned middle aligned">Date Time</th>
          <th class="center aligned middle aligned">Total Items</th>
          <th class="center aligned middle aligned">Comments</th>
        </tr>
      </thead>
      <tbody ng-init="total=0" ng-cloak>
        <tr ng-repeat="item in issuances">
          <td class="center aligned middle aligned">
            <a ng-href="/issuance/view/@{{item.id}}">
              @{{item.issuance_number}}
            </a></td>
          <td class="center aligned middle aligned">@{{item.created_at}}</td>
          <td class="center aligned middle aligned">@{{item.total}}</td>
          <td class="center aligned middle aligned">@{{item.comments}}</td>
        </tr>
      </tbody>
        <tfoot>
          <tr ng-if="issuances | isEmpty">
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
    show_issuances();
    $scope.search = _.debounce(function(){
      show_issuances();
    },250);
    function show_issuances(myUrl) {
      myUrl = (typeof myUrl !== 'undefined') && myUrl !== "" ? myUrl : '/api/issuance/list';
      $scope.loading = true;
      $scope.issuances = {};
      $scope.pages = "";
      $http({
        method : "GET",
        url : myUrl,
        params: {
          search: $scope.search_string,
        },
      }).then(function mySuccess(response) {
        $scope.issuances = response.data.result.data;
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