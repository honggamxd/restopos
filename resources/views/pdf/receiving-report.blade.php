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
  
  <img style="position:absolute;right:0.5in;width: 150pt" src="{{public_path().('/assets/images/logo.png')}}">
  <br>
  <br>
  <br>
  <p style="text-align: center;" class="main-heading">RECEIVING REPORT</p>
  <br>
  <br>
  <p style="text-align: left;margin-left: 72%" class="sub-heading">
    No. {{ $receiving_report_number_formatted }}<br>
    Ref PO no.: {{ $inventory_purchase_order['purchase_order_number_formatted'] }}<br>
    Ref RS no.: {{ $inventory_purchase_order['inventory_purchase_request']['purchase_request_number_formatted'] }}<br>
  </p>
  <br>
  <br>
  <table style="width: 100%" class="table-bordered">
    <tr>
      <th style="width: 50%">
        REQUESTING DEPARTMENT: <span>{{ $requesting_department }}</span>
      </th>
      <th style="width: 50%;">
        DATE & TIME RECEIVED: <span>{{ $receiving_report_date_formatted }}</span>
      </th>
    </tr>
    <tr>
      <th style="width: 50%">
        REQUEST CHARGEABLE TO: <span>{{ $request_chargeable_to }}</span>
      </th>
      <th style="width: 50%;">
        SUPPLIER ADDRESS: <span>{{ $supplier_address }}</span>
      </th>
    </tr>
    <tr>
      <th style="width: 50%">
        SUPPLIER NAME: <span>{{ $supplier_name }}</span>
      </th>
      <th style="width: 50%;">
        SUPPLIER CONTACT NUMBER: <span>{{ $supplier_contact_number }}</span>
      </th>
    </tr>
  </table>
  <table class="table-bordered">
    <thead>
      <tr>
        <th style="text-align: center;">Quantity</th>
        <th style="text-align: center;">Unit of Measure</th>
        <th style="text-align: center;" style="width: 2000%">Particulars / Item Description</th>
        <th style="text-align: center;">Unit Price</th>
        <th style="text-align: right;">Amount</th>
        <th style="text-align: center;">Remarks</th>
      </tr>
    </thead>
    <tbody>
      <?php $total= 0; ?>
      @foreach($details['data'] as $item)
      <?php $total += ($item['unit_price'] * $item['quantity']); ?>
      <tr>
        <th style="text-align: center;">{{ $item['quantity'] }}</th>
        <th style="text-align: center;">{{ $item['inventory_item']['unit_of_measure'] }}</th>
        <th style="text-align: center;">{{ $item['inventory_item']['item_name'] }}</th>
        <th style="text-align: right;">{{ number_format($item['unit_price'],2) }}</th>
        <th style="text-align: right;">{{ number_format(($item['unit_price'] * $item['quantity']),2) }}</th>
        <th style="text-align: center;">{{ $item['remarks'] }}</th>
      </tr>
      @endforeach
    </tbody>
    <tbody>
      <tr>
        <th style="text-align: center;"></th>
        <th style="text-align: center;"></th>
        <th style="text-align: center;"></th>
        <th style="text-align: right;">TOTAL P</th>
        <th style="text-align: right;">{{ number_format($total,2) }}</th>
        <th style="text-align: center;"></th>
      </tr>
    </tbody>
  </table
  <br>
  <table style="width: 100%">
    <tr>
      <td style="width: 33%">
        <div style="width: 75%;">
          Received By:<br><br><br><br><br>
          <p style="border-bottom: 1pt solid black;text-align: center">{{ $received_by_name }}</p>
          <p style="text-align: center;padding-top: 2pt;"></p>
        </div>
      </td>
      <td style="width: 33%;">
        <div style="width: 75%;margin-right: auto;margin-left: auto">
          Noted By:<br><br><br><br><br>
          <p style="border-bottom: 1pt solid black;text-align: center">{{ $checked_by_name }}</p>
          <p style="text-align: center;padding-top: 2pt;"></p>
        </div>
      </td>
      <td style="width: 33%;">
        <div style="width: 75%;float: right;">
          Posted by:<br><br><br><br><br>
          <p style="border-bottom: 1pt solid black;text-align: center">{{ $posted_by_name }}</p>
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