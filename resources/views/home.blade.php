@extends('layouts.main')

@section('title', '')

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
.modal-lg.order{
  width: 95vw;
}

#complete-order-table{
  width: 100%;
  border-collapse: collapse;
}
#complete-order-table tr td,#complete-order-table tr th{
  border: 1px solid black;
  padding: 0px 2px 0px 2px;
}

.order-table{
  width: 100%;
  border-collapse: collapse;
  margin: 0;
}



</style>
@endsection
@section('breadcrumb')
<div class="active section">Dashboard</div>
@endsection
@section('content')
<div class="col-sm-12">
ADMIN DASHBOARD
</div>
@endsection

@section('modals')


@endsection

@push('scripts')
<script type="text/javascript">
  
  app.controller('content-controller', function($scope,$http, $sce, $window) {

  });


  
</script>
@endpush