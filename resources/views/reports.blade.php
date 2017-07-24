@extends('layouts.main')

@section('title', 'Restaurant POS')

@section('css')
<style type="text/css">

</style>
@endsection
@section('breadcrumb')
<a class="section hideprint" href="/reports">Reports</a>
<i class="right angle icon divider"></i>
<div class="active section">Restaurant</div>
@endsection
@section('content')
 
<div class="col-sm-2">
  <label>Navigation:</label>
  <ul class="nav nav-pills nav-stacked">
    <li class="active"><a data-toggle="pill" href="#sales">Sales</a></li>
    <li><a data-toggle="pill" href="#settlements">Settlements</a></li>
    <li><a data-toggle="pill" href="#all">All Reports</a></li>
  </ul>
</div>
<div class="col-sm-4">
  <div class="tab-content">
    <div id="sales" class="tab-pane fade in active">
      <form class="form" method="get" action="/reports/restaurant/sales">
        <h1 style="text-align: center;">Sales Reports</h1>
        <div class="form-group">
          <label>Date From</label>
          <input type="text" id="sales-date-from" name="date_from" class="form-control" placeholder="Date From" value="{{date('m/d/Y')}}" readonly>
        </div>

        <div class="form-group">
          <label>Date To</label>
          <input type="text" id="sales-date-to" name="date_to" class="form-control" placeholder="Date To" value="{{date('m/d/Y')}}" readonly>
        </div>

        <div class="form-group">
          <button type="submit" class="btn btn-primary btn-block">View Report</button>
        </div>
      </form>
    </div>
    <div id="settlements" class="tab-pane fade">
      <form class="form" method="get" action="/reports/restaurant/settlements">
        <h1 style="text-align: center;">Settlements Reports</h1>
        <div class="form-group">
          <label>Date From</label>
          <input type="text" id="settlements-date-from" name="date_from" class="form-control" placeholder="Date From" value="{{date('m/d/Y')}}" readonly>
        </div>

        <div class="form-group">
          <label>Date To</label>
          <input type="text" id="settlements-date-to" name="date_to" class="form-control" placeholder="Date To" value="{{date('m/d/Y')}}" readonly>
        </div>

        <div class="form-group">
          <button type="submit" class="btn btn-primary btn-block">View Report</button>
        </div>
      </form>
    </div>
    <div id="all" class="tab-pane fade">
      <form class="form" method="get" action="/reports/restaurant/all">
        <h1 style="text-align: center;">All Reports</h1>
        <div class="form-group">
          <label>Date From</label>
          <input type="text" id="all-date-from" name="date_from" class="form-control" placeholder="Date From" value="{{date('m/d/Y')}}" readonly>
        </div>

        <div class="form-group">
          <label>Date To</label>
          <input type="text" id="all-date-to" name="date_to" class="form-control" placeholder="Date To" value="{{date('m/d/Y')}}" readonly>
        </div>

        <div class="form-group">
          <button type="submit" class="btn btn-primary btn-block">View Report</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('modals')

@endsection

@section('scripts')
<script type="text/javascript">
  $(document).ready(function() {
    $("#sales-date-from,#sales-date-to").datepicker();
    $("#all-date-from,#all-date-to").datepicker();
    $("#settlements-date-from,#settlements-date-to").datepicker();
  });
  var app = angular.module('main', []);
  app.controller('content-controller', function($scope,$http, $sce) {

  });
  angular.bootstrap(document, ['main']);
</script>
@endsection