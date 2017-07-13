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

#complete-order-table{
  width: 100%;
  border-collapse: collapse;
  margin: 0;
}
#complete-order-table tr td,#complete-order-table tr th{
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
@section('breadcrumb')
<a class="section" href="/restaurant">Restaurant</a>
<i class="right angle icon divider"></i>
<div class="active section">Order</div>
@endsection
@section('content')
<table id="complete-order-table">
<thead>
  <tr>
    <th style="text-align: center;">Order 1</th>
    <th style="text-align: center;">Table 1</th>
  </tr>
  <tr>
    <th style="text-align: center;">Items</th>
    <th style="text-align: center;">Qty</th>
  </tr>
</thead>
<tbody>
  <tr>
    <td style="text-align: center;">Menu 1</td>
    <td style="text-align: center;">1</td>
  </tr>
  <tr>
    <td style="text-align: center;">Menu 2</td>
    <td style="text-align: center;">2</td>
  </tr>
</tbody>
</table>
<a href="#" class="btn btn-primary" onclick="window.print()"><span class="glyphicon glyphicon-print"></span> Print</a>
@endsection


@section('scripts')
<script type="text/javascript">
  $('table').tablesort();
</script>
@endsection