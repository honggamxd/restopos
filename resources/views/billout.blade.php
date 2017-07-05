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
@media print{
  a{
    display: none !important;
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
    <th style="text-align: right;">30.00</th>
  </tr>
</tfoot>
</table>
<br>
<center>Footer Message</center>
<a href="#" class="btn btn-primary" onclick="window.print()"><span class="glyphicon glyphicon-print"></span> Print</a>
@endsection


@section('scripts')
<script type="text/javascript">
  $('table').tablesort();
</script>
@endsection