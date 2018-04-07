@extends('layouts.pdf')

@section('title', 'Purchase Order')

@section('css')
<style type="text/css">
  table{
    width: 100%;
  }
</style>
@endsection

@section('content')
  
  <img style="position:absolute;right:0.5in;width: 150pt" src="{{public_path().('/assets/images/logo.png')}}">
  <br>
  <br>
  <br>
  <p style="text-align: center;" class="main-heading">STOCK ISSUANCE</p>
  <br>
  <br>
  <p style="text-align: left;margin-left: 72%" class="sub-heading">
    No.<br>
    Ref RR no.:<br>
  </p>
  <br>
  <br>
  <table style="width: 100%" class="table-bordered">
    <tr>
      <th style="width: 50%">
        REQUESTING DEPARTMENT: <span></span>
      </th>
      <th style="width: 50%;">
        DATE: <span></span>
      </th>
    </tr>
    <tr>
      <th style="width: 50%">
        REQUEST CHARGEABLE TO: <span></span>
      </th>
      <th style="width: 50%;">
        SUPPLIER ADDRESS: <span></span>
      </th>
    </tr>
  </table>
  <table class="table-bordered">
    <thead>
      <tr>
        <th style="text-align: center;">Quantity</th>
        <th style="text-align: center;">Unit of Measure</th>
        <th style="text-align: center;">Particulars / Item Description</th>
        <th style="text-align: center;">Unit Price</th>
        <th style="text-align: right;">Amount</th>
        <th style="text-align: center;">Remarks</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th style="text-align: center;">1</th>
        <th style="text-align: center;">PC</th>
        <th style="text-align: center;">DESCRIPTION</th>
        <th style="text-align: center;">224.00</th>
        <th style="text-align: right;">224.00</th>
        <th style="text-align: center;"></th>
      </tr>
    </tbody>
    <tbody>
      <tr>
        <th style="text-align: center;"></th>
        <th style="text-align: center;"></th>
        <th style="text-align: center;"></th>
        <th style="text-align: right;">TOTAL</th>
        <th style="text-align: right;"></th>
        <th style="text-align: center;"></th>
      </tr>
    </tbody>
  </table>
  <br>
  <table style="width: 100%">
    <tr>
      <td style="width: 25%">
        <div style="width: 80%;">
          Stock Issued By:<br><br><br><br><br>
          <p style="border-bottom: 1pt solid black"></p>
          <p style="text-align: center;padding-top: 2pt;"></p>
        </div>
      </td>
      <td style="width: 25%">
        <div style="width: 80%;">
            Stock Received By:<br><br><br><br><br>
            <p style="border-bottom: 1pt solid black"></p>
            <p style="text-align: center;padding-top: 2pt;"></p>
        </div>
      </td>
      <td style="width: 25%;">
        <div style="width: 80%;margin-right: auto;margin-left: auto">
          Approved By:<br><br><br><br><br>
          <p style="border-bottom: 1pt solid black"></p>
          <p style="text-align: center;padding-top: 2pt;"></p>
        </div>
      </td>
      <td style="width: 25%;">
        <div style="width: 80%;float: right;">
          Posted by:<br><br><br><br><br>
          <p style="border-bottom: 1pt solid black"></p>
          <p style="text-align: center;padding-top: 2pt;"></p>
        </div>
      </td>
    </tr>
  </table>

@endsection

@push('scripts')
<script type="text/javascript">  
</script>
@endpush