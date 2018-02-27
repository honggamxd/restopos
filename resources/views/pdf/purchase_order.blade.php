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
  <p style="text-align: center;" class="main-heading">Malagos Garden Resort Inc.</p>
  <p style="text-align: center;" class="content">
    Malagos, Calinan Davao City<br>
    Tel. No. 221-1545 • Fax No. 221-1395<br>
    VAT Reg. TIN 002-914-548-000
  </p>
  <br>
  <p style="text-align: center;" class="sub-heading">
    PURCHASE ORDER<br>
  </p>
  <br>
  <table style="width: 100%">
    <tr>
      <td style="width: 50%">
        <div style="width: 75%">
          PO #:
        </div>
      </td>
      <td style="width: 50%;">
        <div style="width: 75%;float: right;">
          Date:
        </div>
      </td>
    </tr>
  </table>
  <table style="width: 100%">
    <tr>
      <td>To:</td>
      <td style="border-bottom: 1pt solid black;width: 100%"></td>
    </tr>
    <tr>
      <td>Address:</td>
      <td style="border-bottom: 1pt solid black"></td>
    </tr>
    <tr>
      <td>TIN:</td>
      <td style="border-bottom: 1pt solid black"></td>
    </tr>
    <tr>
      <td colspan="2">
        Please deliver to us the following on account.
      </td>
    </tr>
  </table>
  <table class="table-bordered">
    <thead>
      <tr>
        <th style="text-align: center;">QTY</th>
        <th style="text-align: center;">UNIT</th>
        <th style="text-align: center;">DESCRIPTION</th>
        <th style="text-align: center;">Unit Price</th>
        <th style="text-align: right;">Amount</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th style="text-align: center;">1</th>
        <th style="text-align: center;">PC</th>
        <th style="text-align: center;">DESCRIPTION</th>
        <th style="text-align: center;">224.00</th>
        <th style="text-align: right;">224.00</th>
      </tr>
    </tbody>
    <tbody>
      <tr>
        <th style="text-align: center;"></th>
        <th style="text-align: center;"></th>
        <th style="text-align: center;"></th>
        <th style="text-align: right;">TOTAL P</th>
        <th style="text-align: right;"></th>
      </tr>
    </tbody>
  </table>
  <table style="width: 100%">
    <tr>
      <th>Terms:</th>
      <th style="border-bottom: 1pt solid black;width: 100%"></th>
    </tr>
    <tr>
      <th>Purpose:</th>
      <th style="border-bottom: 1pt solid black"></th>
    </tr>
  </table>
  <br>
  <table style="width: 100%">
    <tr>
      <td style="width: 50%">
        <div style="width: 75%">
          <br><br><br>
          <p style="border-bottom: 1pt solid black"></p>
          <p style="text-align: center;padding-top: 2pt;">REQUISITIONER</p>
        </div>
      </td>
      <td style="width: 50%;">
        <div style="width: 75%;float: right;">
          Approved:<br><br><br>
          <p style="border-bottom: 1pt solid black"></p>
          <p style="text-align: center;padding-top: 2pt;">MANAGER</p>
        </div>
      </td>
    </tr>
  </table>

@endsection

@push('scripts')
<script type="text/javascript">  
</script>
@endpush