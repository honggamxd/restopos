@extends('layouts.pdf')

@section('title', 'Receiving Report')

@section('css')
<style type="text/css">
  table{
    width: 100%;
  }
</style>
@endsection

@section('content')
  <center>
    <img style="width: 150pt" src="{{public_path().('/assets/images/logo.png')}}">
  </center>
  <p style="text-align: center;" class="sub-heading">
    RECEIVING REPORT<br><br>
  </p>
  <br>
  <table class="table-bordered">
    <thead>
      <tr>
        <th style="width: 50%">
          <table style="width: 100%;">
            <tr>
              <td style="border:0;text-align: left;width: 40%;">Requesting</td>
              <td style="border:0;text-align: left;" rowspan="2">
                asdasdasd
              </td>
            </tr>
            <tr>
              <td style="border:0;text-align: left;">Department</td>
            </tr>
          </table>
        </th>
        <th style="width: 50%">
          <table style="width: 100%;">
            <tr>
              <td style="border:0;text-align: left;width: 40%;">Date & TIme</td>
              <td style="border:0;text-align: left;" rowspan="2">
                asdasdasd
              </td>
            </tr>
            <tr>
              <td style="border:0;text-align: left;">Received</td>
            </tr>
          </table>
        </th>
      </tr>
      <tr>
        <th style="width: 50%">
          <table style="width: 100%;">
            <tr>
              <td style="border:0;text-align: left;width: 40%;">Supplier's</td>
              <td style="border:0;text-align: left;" rowspan="2">
                asdasdasd
              </td>
            </tr>
            <tr>
              <td style="border:0;text-align: left;">Name/Company</td>
            </tr>
          </table>
        </th>
        <th style="width: 50%">
          <table style="width: 100%;">
            <tr>
              <td style="border:0;text-align: left;width: 40%;">Supplier's</td>
              <td style="border:0;text-align: left;" rowspan="2">
                asdasdasd
              </td>
            </tr>
            <tr>
              <td style="border:0;text-align: left;">Address</td>
            </tr>
          </table>
        </th>
      </tr>
    </thead>
  </table>
  <table class="table-bordered">
    <thead>
      <tr>
        <th style="text-align: center;">Particulars / Item Description</th>
        <th style="text-align: center;">QTY</th>
        <th style="text-align: center;">UOM</th>
        <th style="text-align: center;">Unit Price</th>
        <th style="text-align: right;">Total Price</th>
        <th style="text-align: center;">Remarks</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th style="text-align: center;">DESCRIPTION</th>
        <th style="text-align: center;">1</th>
        <th style="text-align: center;">PC</th>
        <th style="text-align: center;">224.00</th>
        <th style="text-align: right;">224.00</th>
        <th style="text-align: center;">Remarks</th>
      </tr>
    </tbody>
    <tbody>
      <tr>
        <th style="text-align: center;"></th>
        <th style="text-align: center;"></th>
        <th style="text-align: center;"></th>
        <th style="text-align: right;">TOTAL P</th>
        <th style="text-align: right;"></th>
        <th style="text-align: right;"></th>
      </tr>
    </tbody>
  </table>
  <br>
  <table style="width: 100%">
    <tr>
      <td style="width: 33%">
        <div style="width: 75%">
          Received By:<br><br><br>
          <p style="border-bottom: 1pt solid black"></p>
        </div>
      </td>
      <td style="width: 33%;">
        <div style="width: 75%;margin-right: auto;margin-left: auto;">
          Checked By:<br><br><br>
          <p style="border-bottom: 1pt solid black"></p>
        </div>
      </td>
      <td style="width: 33%;">
        <div style="width: 75%;float: right;">
          Checked By:<br><br><br>
          <p style="border-bottom: 1pt solid black"></p>
        </div>
      </td>
    </tr>
  </table>

@endsection

@push('scripts')
<script type="text/javascript">  
</script>
@endpush