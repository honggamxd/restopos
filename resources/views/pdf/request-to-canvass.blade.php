@extends('layouts.pdf')

@section('title', 'Request to Canvass')

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
  <p style="text-align: center;" class="main-heading">REQUEST TO CANVASS</p>
  <br>
  <br>
  <p style="text-align: left;margin-left: 72%" class="sub-heading">
    No. {{ $request_to_canvass_date_formatted }} <br>
  </p>
  <br>
  <br>
  <table style="width: 100%" class="table-bordered">
    <tr>
      <th style="width: 50%">
        REQUESTING DEPARTMENT: <span>{{ $requesting_department }}</span>
      </th>
      <th style="width: 50%;">
        DATE: <span>{{ $request_to_canvass_date_formatted }}</span>
      </th>
    </tr>
    <tr>
      <th style="width: 50%">
        REASON FOR THE REQUEST: <span>{{ $reason_for_the_request }}</span>
      </th>
      <th style="width: 50%;">
        TYPE OF ITEM REQUESTED TO CANVASS: <span>{{ $type_of_item_requested }}</span>
      </th>
    </tr>
  </table>
  <table class="table-bordered">
    <thead>
      <tr>
        <th style="text-align: center;" rowspan="2">QTY</th>
        <th style="text-align: center;" rowspan="2">Unit of Measure</th>
        <th style="text-align: center;" rowspan="2">Particulars / Item Description</th>
        <th style="text-align: center;" colspan="2"> {{ $vendor_1_name }} </th>
        <th style="text-align: center;" colspan="2"> {{ $vendor_2_name }} </th>
        <th style="text-align: center;" colspan="2"> {{ $vendor_3_name }} </th>
      </tr>
      <tr>
        <th style="text-align: center;">Unit Price</th>
        <th style="text-align: center;">Amount</th>
        <th style="text-align: center;">Unit Price</th>
        <th style="text-align: center;">Amount</th>
        <th style="text-align: center;">Unit Price</th>
        <th style="text-align: center;">Amount</th>
      </tr>
    </thead>
    <tbody>
        @foreach($details['data'] as $item)
        <tr>
          <th style="text-align: center;">{{ $item['quantity'] }}</th>
          <th style="text-align: center;">{{ $item['inventory_item']['unit_of_measure'] }}</th>
          <th style="text-align: center;">{{ $item['inventory_item']['item_name'] }}</th>
          <th style="text-align: right;">{{ number_format($item['vendor_1_unit_price'],2) }}</th>
          <th style="text-align: right;">{{ number_format(($item['vendor_1_unit_price'] * $item['quantity']),2) }}</th>
          <th style="text-align: right;">{{ number_format($item['vendor_2_unit_price'],2) }}</th>
          <th style="text-align: right;">{{ number_format(($item['vendor_2_unit_price'] * $item['quantity']),2) }}</th>
          <th style="text-align: right;">{{ number_format($item['vendor_3_unit_price'],2) }}</th>
          <th style="text-align: right;">{{ number_format(($item['vendor_3_unit_price'] * $item['quantity']),2) }}</th>
        </tr>
      @endforeach
    </tbody>
  </table>
  <br>
  <table style="width: 100%">
    <tr>
      <td style="width: 33%">
        <div style="width: 75%;">
          Requested By:<br><br><br><br><br>
          <p style="border-bottom: 1pt solid black;text-align: center">{{ $requested_by_name }}</p>
          <p style="text-align: center;padding-top: 2pt;"></p>
        </div>
      </td>
      <td style="width: 33%;">
        <div style="width: 75%;margin-right: auto;margin-left: auto">
          Noted By:<br><br><br><br><br>
          <p style="border-bottom: 1pt solid black;text-align: center">{{ $noted_by_name }}</p>
          <p style="text-align: center;padding-top: 2pt;"></p>
        </div>
      </td>
      <td style="width: 33%;">
        <div style="width: 75%;float: right;">
          Canvass by:<br><br><br><br><br>
          <p style="border-bottom: 1pt solid black;text-align: center">{{ $canvass_by_name }}</p>
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