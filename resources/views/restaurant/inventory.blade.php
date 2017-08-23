@extends('layouts.main')

@section('title', '')

@section('css')

@endsection
@section('breadcrumb')
<div class="active section">{{Session::get('users.user_data')->restaurant}} Inventory</div>
@endsection
@section('content')
<h1 style="text-align: center;">{{Session::get('users.user_data')->restaurant}} Inventory</h1>
<div class="col-sm-12">
  <div class="table-responsive">
    <table class="ui unstackable table">
      <thead>
        <tr>
          <th class="center aligned middle aligned">Item</th>
          <th class="center aligned middle aligned">Unit</th>
          <th class="center aligned middle aligned">Quantity</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="center aligned middle aligned"></td>
          <td class="center aligned middle aligned"></td>
          <td class="center aligned middle aligned"></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
@endsection

@section('modals')

@endsection

@section('scripts')
<script type="text/javascript">
  var app = angular.module('main', []);
  app.controller('content-controller', function($scope,$http, $sce, $window) {

  });
  angular.bootstrap(document, ['main']);
</script>
@endsection