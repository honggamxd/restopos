@extends('layouts.pdf')

@section('title', 'Stock Issuance')

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
  <br>
  <p style="text-align: left;margin-left: 72%" class="sub-heading">
    No. {{ $stock_issuance_number_formatted }}<br>
    Ref RR no.:  {{ isset($inventory_receiving_report['receiving_report_number_formatted']) ? $inventory_receiving_report['receiving_report_number_formatted'] : "" }}<br>
  </p>
  <br>
  <br>
  <table style="width: 100%" class="table-bordered">
    <tr>
      <th style="width: 50%">
        REQUESTING DEPARTMENT:  <span>{{ $requesting_department }}</span>
      </th>
      <th style="width: 50%;">
        DATE:  <span>{{ $stock_issuance_date_formatted }}</span>
      </th>
    </tr>
    <tr>
      <th style="width: 50%">
        REQUEST CHARGEABLE TO:  <span>{{ $request_chargeable_to }}</span>
      </th>
      <th style="width: 50%;">
        SUPPLIER ADDRESS:  <span>{{ $supplier_address }}</span>
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
      <?php $total = 0; ?>
      @foreach($details['data'] as $item)
      <?php $total += ($item['unit_price'] * $item['quantity']); ?>
      <tr>
        <th style="text-align: center;"> <span>{{ $item['quantity'] }}</span></th>
        <th style="text-align: center;"> <span>{{ $item['inventory_item']['unit_of_measure'] }}</span></th>
        <th style="text-align: center;"> <span>{{ $item['inventory_item']['item_name'] }}</span></th>
        <th style="text-align: right;"> <span>{{ number_format($item['unit_price'],2) }}</span></th>
        <th style="text-align: right;"> <span>{{ number_format(($item['unit_price'] * $item['quantity']),2) }}</span></th>
        <th style="text-align: center;"> <span>{{ $item['remarks'] }}</span></th>
      </tr>
      @endforeach
    </tbody>
    <tbody>
      <tr>
        <th style="text-align: center;"></th>
        <th style="text-align: center;"></th>
        <th style="text-align: center;"></th>
        <th style="text-align: right;">TOTAL</th>
        <th style="text-align: right;"> <span>{{ number_format($total,2) }}</span></th>
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
          <p style="border-bottom: 1pt solid black;text-align: center">{{ $issued_by_name!=null ? $issued_by_name : "&nbsp;" }}</p>
          <p style="text-align: center;padding-top: 2pt;"></p>
        </div>
      </td>
      <td style="width: 25%">
        <div style="width: 80%;">
            Stock Received By:<br><br><br><br><br>
            <p style="border-bottom: 1pt solid black;text-align: center">{{ $received_by_name!=null ? $received_by_name : "&nbsp;" }}</p>
            <p style="text-align: center;padding-top: 2pt;"></p>
        </div>
      </td>
      <td style="width: 25%;">
        <div style="width: 80%;margin-right: auto;margin-left: auto">
          Approved By:<br><br><br><br><br>
          <p style="border-bottom: 1pt solid black;text-align: center">{{ $approved_by_name!=null ? $approved_by_name : "&nbsp;" }}</p>
          <p style="text-align: center;padding-top: 2pt;"></p>
        </div>
      </td>
      <td style="width: 25%;">
        <div style="width: 80%;float: right;">
          Posted by:<br><br><br><br><br>
          <p style="border-bottom: 1pt solid black;text-align: center">{{ $posted_by_name!=null ? $posted_by_name : "&nbsp;" }}</p>
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