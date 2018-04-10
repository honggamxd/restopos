@extends('layouts.pdf')

@section('title', 'Capital Expenditure Request Form')

@section('css')
<style type="text/css">
  table{
    width: 100%;
  }
</style>
@endsection

@section('content')
  
  <center>
    <img style=";width: 150pt" src="{{public_path().('/assets/images/logo.png')}}">
  </center>
  <p style="text-align: left;margin-left: 72%" class="sub-heading">
    {{-- No.  <span>{{ $capital_expenditure_request_number_formatted }}</span><br> --}}
    Ref PR no.:  {{ $inventory_purchase_request['purchase_request_number_formatted'] }}<br>
  </p>
  <br>
  <table style="width: 100%" class="table-bordered">
    <tr>
      <th style="text-align: center;font-size: 10pt" colspan="2">
        CAPITAL EXPENDITURE REQUEST FORM
      </th>
    </tr>
    <tr>
      <th style="width: 50%">
        BUDGET NO: <span>{{ $capital_expenditure_request_number_formatted }}</span>
      </th>
      <th style="width: 50%;">
        DEPARTMENT: <span>{{ $department }}</span>
      </th>
    </tr>
    <tr>
      <th style="width: 50%;">
        BUDGET DESCRIPTION: <span>{{ $budget_description }}</span>
      </th>
      <th style="width: 50%">
        DATE: <span>{{ $capital_expenditure_request_date_formatted }}</span>
      </th>
    </tr>
    <tr>
      <th style="width: 50%">
        REQUESTED BY: <span>{{ $requested_by_name }}</span>
      </th>
      <th style="width: 50%;text-align: center" rowspan="3">
        SOURCE OF FUNDS: (please put check)<br>
        with budget (please attached approved budget template)<br>
        if none, please specify allocation below:
      </th>
    </tr>
    <tr>
      <th style="width: 50%">
        POSITION: <span>{{ $requested_by_position }}</span>
      </th>
    </tr>
    <tr>
      <th style="width: 50%">
        BUDGET AMOUNT: <span>{{ number_format($budget_amount,2) }}</span>
      </th>
    </tr>
    <tr>
      <th style="width: 50%">
        <div style="width: 100%;height: 27pt">
          
        </div>
      </th>
      <th style="width: 50%;text-align: center">
        <div style="width: 100%;height: 27pt">
          <span>{{ $source_of_funds }}</span>
        </div>
      </th>
    </tr>
    <tr>
      <th style="text-align: center;font-size: 10pt" colspan="2">
        BRIEF PROJECT DESCRIPTION AND JUSTIFICATION
      </th>
    </tr>
    <tr>
      <th style="text-align: center" colspan="2">
        <div style="width: 100%;height: 27pt">
          <span>{{ $brief_project_description }}</span>
        </div>
      </th>
    </tr>
    <tr>
      <th style="text-align: center;font-size: 10pt" colspan="2">
        PLEASE COMPLETE THE SECTION
      </th>
    </tr>
  </table>
  <table class="table-bordered">
    <thead>
      <tr>
        <th style="text-align: center;">Quantity</th>
        <th style="text-align: center;">UOM</th>
        <th style="text-align: center;">Description</th>
        <th style="text-align: center;">Unit Price</th>
        <th style="text-align: center;">Amount</th>
      </tr>
    </thead>
    <tbody>
      <?php $total = 0; ?>
      @foreach($details['data'] as $item)
      <?php $total += ($item['unit_price'] * $item['quantity']); ?>
      <tr>
        <th style="text-align: center;"><span>{{ $item['quantity'] }}</span></th>
        <th style="text-align: center;"><span>{{ $item['inventory_item']['unit_of_measure'] }}</span></th>
        <th style="text-align: center;"><span>{{ $item['inventory_item']['item_name'] }}</span></th>
        <th style="text-align: right;"><span>{{ number_format($item['unit_price'],2) }}</span></th>
        <th style="text-align: right;"><span>{{ number_format(($item['unit_price'] * $item['quantity']),2) }}</span></th>
      </tr>
      @endforeach
    </tbody>
    <tbody>
      <tr>
        <th style="text-align: center;"></th>
        <th style="text-align: center;"></th>
        <th style="text-align: center;"></th>
        <th style="text-align: right;">TOTAL P</th>
        <th style="text-align: right;"><span>{{ number_format($total,2) }}</span></th>
      </tr>
    </tbody>
    <tfoot>
      <tr>
        <th style="text-align: center;font-size: 10pt" colspan="5">
          ROUTING SIGNATURIES
        </th>
      </tr>
    </tfoot>
  </table>
  <table style="width: 100%;">
    <tr>
      <td style="width: 20%;border-left: 1pt solid black;">
        <div style="width: 100%;">
          <br><br><br>
          <p style="text-align: left"> Requested By:</p>
          <p style="text-align: center;padding-top: 1pt;">&nbsp;</p>
        </div>
      </td>
      <td style="width: 40%;">
        <div style="width: 80%;margin-left: auto;margin-right: auto">
          <br><br><br>
          <p style="border-bottom: 1pt solid black;text-align: center"><span>{{ $requested_by_name }}</span></p>
          <p style="text-align: center;padding-top: 1pt;"><span>{{ $requested_by_position }}</span></p>
        </div>
      </td>
      <td style="width: 30%;border-right: 1pt solid black">
        <div style="width: 80%;margin-left: auto;margin-right: auto">
          <br><br><br>
          <p style="border-bottom: 1pt solid black;text-align: center"><span>{{ $requested_by_date_formatted }}</span></p>
          <p style="text-align: center;padding-top: 1pt;"><span>Date</span></p>
        </div>
      </td>
    </tr>
    <tr>
      <td style="width: 20%;border-left: 1pt solid black;">
        <div style="width: 100%;">
          <br><br><br>
          <p style="text-align: left"> Approved By:</p>
          <p style="text-align: center;padding-top: 1pt;">&nbsp;</p>
        </div>
      </td>
      <td style="width: 40%;">
        <div style="width: 80%;margin-left: auto;margin-right: auto">
          <br><br><br>
          <p style="border-bottom: 1pt solid black;text-align: center"><span>{{ $approved_by_1_name }}</span></p>
          <p style="text-align: center;padding-top: 1pt;"><span>{{ $approved_by_1_position }}</span></p>
        </div>
      </td>
      <td style="width: 30%;border-right: 1pt solid black">
        <div style="width: 80%;margin-left: auto;margin-right: auto">
          <br><br><br>
          <p style="border-bottom: 1pt solid black;text-align: center"><span>{{ $approved_by_1_date_formatted }}</span></p>
          <p style="text-align: center;padding-top: 1pt;"><span>Date</span></p>
        </div>
      </td>
    </tr>
    <tr>
      <td style="width: 20%;border-left: 1pt solid black;">
        <div style="width: 100%;">
          <br><br><br>
          <p style="text-align: left"> Verified as Funded by:</p>
          <p style="text-align: center;padding-top: 1pt;">&nbsp;</p>
        </div>
      </td>
      <td style="width: 40%;">
        <div style="width: 80%;margin-left: auto;margin-right: auto">
          <br><br><br>
          <p style="border-bottom: 1pt solid black;text-align: center"><span>{{ $verified_as_funded_by_name }}</span></p>
          <p style="text-align: center;padding-top: 1pt;"><span>{{ $verified_as_funded_by_position }}</span></p>
        </div>
      </td>
      <td style="width: 30%;border-right: 1pt solid black">
        <div style="width: 80%;margin-left: auto;margin-right: auto">
          <br><br><br>
          <p style="border-bottom: 1pt solid black;text-align: center"><span>{{ $verified_as_funded_by_date_formatted }}</span></p>
          <p style="text-align: center;padding-top: 1pt;"><span>Date</span></p>
        </div>
      </td>
    </tr>
    <tr>
      <td style="width: 20%;border-left: 1pt solid black;">
        <div style="width: 100%;">
          <br><br><br>
          <p style="text-align: left"> Approved By:</p>
          <p style="text-align: center;padding-top: 1pt;">&nbsp;</p>
          <br>
          <br>
        </div>
      </td>
      <td style="width: 40%;">
        <div style="width: 80%;margin-left: auto;margin-right: auto">
          <br><br><br>
          <p style="border-bottom: 1pt solid black;text-align: center"><span>{{ $approved_by_2_name }}</span></p>
          <p style="text-align: center;padding-top: 1pt;"><span>{{ $approved_by_2_position }}</span></p>
          <br>
          <br>
        </div>
      </td>
      <td style="width: 30%;border-right: 1pt solid black">
        <div style="width: 80%;margin-left: auto;margin-right: auto">
          <br><br><br>
          <p style="border-bottom: 1pt solid black;text-align: center"><span>{{ $approved_by_2_date_formatted }}</span></p>
          <p style="text-align: center;padding-top: 1pt;"><span>Date</span></p>
          <br>
          <br>
        </div>
      </td>
    </tr>
  </table>
  <table class="table-bordered">
    <tr>
      <th style="text-align: center;font-size: 10pt" colspan="2">
        TO BE COMPLETED BY FINANCE
      </th>
    </tr>
  </table>

@endsection

@push('scripts')
<script type="text/javascript">  
</script>
@endpush