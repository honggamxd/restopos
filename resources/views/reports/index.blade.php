@extends('layouts.main')

@section('title', 'Reports')

@section('css')
<style type="text/css">

</style>
@endsection
@section('breadcrumb')
<div class="active section">Reports</div>
@endsection
@section('content')
 
<div class="col-sm-2">
  <label>Navigation:</label>
  <ul class="nav nav-pills nav-stacked">
    <li class="active"><a data-toggle="pill" href="#all">Order Slip Summary</a></li>
    <li><a data-toggle="pill" href="#purchases">Purchases</a></li>
    <li><a data-toggle="pill" href="#issuances">Issuances</a></li>
    <li><a data-toggle="pill" href="#menu_popularity">Menu Popularity</a></li>
    <li><a data-toggle="pill" href="#search_invoice_number">Search Invoice Number</a></li>
  </ul>
</div>
<div class="col-sm-4">
  <div class="tab-content">
    <div id="all" class="tab-pane fade in active">
      <form class="form" method="get" action="/reports/view/all">
        <h1 style="text-align: center;">Order Slip Summary</h1>
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

    <div id="purchases" class="tab-pane fade">
      <form class="form" method="get" action="/reports/view/purchases">
        <h1 style="text-align: center;">Purchases Reports</h1>
        <div class="form-group">
          <label>Date From</label>
          <input type="text" id="purchases-date-from" name="date_from" class="form-control" placeholder="Date From" value="{{date('m/d/Y')}}" readonly>
        </div>

        <div class="form-group">
          <label>Date To</label>
          <input type="text" id="purchases-date-to" name="date_to" class="form-control" placeholder="Date To" value="{{date('m/d/Y')}}" readonly>
        </div>

        <div class="form-group">
          <button type="submit" class="btn btn-primary btn-block">View Report</button>
        </div>
      </form>
    </div>

    <div id="issuances" class="tab-pane fade">
      <form class="form" method="get" action="/reports/view/issuances">
        <h1 style="text-align: center;">Issuances Reports</h1>
        <div class="form-group">
          <label>Date From</label>
          <input type="text" id="issuances-date-from" name="date_from" class="form-control" placeholder="Date From" value="{{date('m/d/Y')}}" readonly>
        </div>

        <div class="form-group">
          <label>Date To</label>
          <input type="text" id="issuances-date-to" name="date_to" class="form-control" placeholder="Date To" value="{{date('m/d/Y')}}" readonly>
        </div>

        <div class="form-group">
          <button type="submit" class="btn btn-primary btn-block">View Report</button>
        </div>
      </form>
    </div>

    <div id="menu_popularity" class="tab-pane fade">
      <form class="form" method="get" action="/reports/view/menu_popularity">
        <h1 style="text-align: center;">Menu Popularity Reports</h1>
        <div class="form-group">
          <label>Date From</label>
          <input type="text" id="menu_popularity-date-from" name="date_from" class="form-control" placeholder="Date From" value="{{date('m/d/Y')}}" readonly>
        </div>

        <div class="form-group">
          <label>Date To</label>
          <input type="text" id="menu_popularity-date-to" name="date_to" class="form-control" placeholder="Date To" value="{{date('m/d/Y')}}" readonly>
        </div>

        <div class="form-group">
          <button type="submit" class="btn btn-primary btn-block">View Report</button>
        </div>
      </form>
    </div>

    <div id="search_invoice_number" class="tab-pane fade">
      <form class="form" method="get" action="/reports/view/search_invoice_number">
        <h1 style="text-align: center;">Search Invoice Number</h1>
        <div class="form-group">
          <label>Search</label>
          <input type="text" id="search_invoice_number-date-from" name="invoice_number" class="form-control" placeholder="Invoice Number">
        </div>


        <div class="form-group">
          <button type="submit" class="btn btn-primary btn-block">Search</button>
        </div>
      </form>
    </div>


    
  </div>
</div>
@endsection

@section('modals')

@endsection

@push('scripts')
<script type="text/javascript">
  $(document).ready(function() {
    $("#sales-date-from,#sales-date-to").datepicker();
    $("#all-date-from,#all-date-to").datepicker();
    $("#purchases-date-from,#purchases-date-to").datepicker();
    $("#issuances-date-from,#issuances-date-to").datepicker();
    $("#menu_popularity-date-from,#menu_popularity-date-to").datepicker();
  });
  
  app.controller('content-controller', function($scope,$http, $sce) {

    

  });
  
</script>
@endpush