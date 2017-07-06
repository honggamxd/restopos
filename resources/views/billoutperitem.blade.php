@extends('layouts.main')

@section('title', 'Restaurant POS')

@section('css')
<style type="text/css">
@media (min-width: 768px){
  .modal-lg {
    width: 760px;
  }  
}
@media (min-width: 992px){
  .modal-lg {
    width: 990px;
  }  
}

#menu-table tbody{
  max-height: 40vh;
  overflow: auto;
  display: block;
}

#menu-table tr>td{
  width: 100%;
}

.complete-order-table{
  width: 100%;
  border-collapse: collapse;
  margin: 0;
}
.complete-order-table tr td,.complete-order-table tr th{
  border: 1px solid black;
  padding: 0px 2px 0px 2px;
}
.complete-order-table tfoot tr td,.complete-order-table tfoot tr th{
  border: 0px;
  padding: 0px 2px 0px 2px;
}
@media print{
  .hidetoprint, a{
    display: none !important;
  }
  .type_payment{
    border: 0px !important;
  }
  .footer{
    page-break-after: always;
  }
  select {
      -webkit-appearance: none;
      -moz-appearance: none;
      text-indent: 1px;
      text-overflow: '';
  }
}

</style>
@endsection
@section('content')
<center>
Company Name<br>
Address<br>
Contact Number<br>
</center>
<br>
Table 1<br>
Date Time<br>
<table class="complete-order-table">
<thead>
  <tr>
    <th style="text-align: center;">Items</th>
    <th style="text-align: center;">Qty</th>
    <th style="text-align: center;">Price</th>
    <th style="text-align: center;">Total</th>
  </tr>
</thead>
<tbody>
  <tr>
    <td style="text-align: center;">Menu 1</td>
    <td style="text-align: center;">1</td>
    <td style="text-align: right;">10.00</td>
    <td style="text-align: right;">10.00</td>
  </tr>
</tbody>
<tfoot>
  <tr>
    <th style="text-align: right;" colspan="3">Total:</th>
    <th style="text-align: right;">10.00</th>
  </tr>
  <tr>
    <th style="text-align: right;" colspan="2"><button type="button" class="btn btn-primary hidetoprint" onclick="$('#payments-modal').modal('show')">Add Payment</button></th>
    <th style="text-align: right;">Guest Ledger</th>
    <th style="text-align: right;">10.00</th>
  </tr>
  <tr>
    <th style="text-align: right;" colspan="3">Change:</th>
    <th style="text-align: right;">0.00</th>
  </tr>
</tfoot>
</table>
<br>
<center class="footer">Footer Message</center>

<center>
Company Name<br>
Address<br>
Contact Number<br>
</center>
<br>
Table 1<br>
Date Time<br>
<table class="complete-order-table">
<thead>
  <tr>
    <th style="text-align: center;">Items</th>
    <th style="text-align: center;">Qty</th>
    <th style="text-align: center;">Price</th>
    <th style="text-align: center;">Total</th>
  </tr>
</thead>
<tbody>
  <tr>
    <td style="text-align: center;">Menu 2</td>
    <td style="text-align: center;">2</td>
    <td style="text-align: right;">20.00</td>
    <td style="text-align: right;">20.00</td>
  </tr>
</tbody>
<tfoot>
  <tr>
    <th style="text-align: right;" colspan="3">Total:</th>
    <th style="text-align: right;">20.00</th>
  </tr>
  <tr>
    <th style="text-align: right;" colspan="2"><button type="button" class="btn btn-primary hidetoprint" onclick="$('#payments-modal').modal('show')">Add Payment</button></th>
    <th style="text-align: right;">Free of Charge</th>
    <th style="text-align: right;">20.00</th>
  </tr>
  <tr>
    <th style="text-align: right;" colspan="3">Change:</th>
    <th style="text-align: right;">0.00</th>
  </tr>
</tfoot>
</table>
<br>
<center class="footer">Footer Message</center>



<a href="#" class="btn btn-primary" onclick="window.print()"><span class="glyphicon glyphicon-print"></span> Print</a>
@endsection

@section('modals')
<div id="payments-modal" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Payments</h4>
      </div>
      <div class="modal-body">
        <form action="/items" method="post" id="add-items-form">
        {{ csrf_field() }}
        <div class="form-group">
          <label>Settlement</label>
          <select class="form-control">
            <option>Cash</option>
            <option>Credit</option>
            <option>Debit</option>
            <option>Guest Ledger</option>
            <option>Free of Charge</option>
          </select>
        </div>

        <div class="form-group">
          <label>Amount</label>
          <input type="text" name="" placeholder="Amount" class="form-control">
        </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" form="add-items-form">Confirm</button>
      </div>
    </div>

  </div>
</div>

@endsection
@section('scripts')
<script type="text/javascript">
  $('table').tablesort();
</script>
@endsection