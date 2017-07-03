@extends('layouts.main')

@section('title', 'Restaurant POS')

@section('css')
<style type="text/css">

@media (min-width: 992px){
  .modal-lg {
      width: 1200px;
  }  
}



</style>
@endsection
@section('content')
<div class="col-sm-4">
  <button class="btn btn-primary" onclick="$('#add-order-modal').modal('show')">Add Table</button>
  <div class="table-responsive">
    <table class="table table-hover">
      <thead>
        <tr>
          <th>Table</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>001</td>
          <td>
            <div class="btn-group">
              <button class="btn btn-primary">Order</button>
              <button class="btn btn-danger">Remove</button>
            </div>
          </td>
        </tr>
        <tr class="info">
          <td>002</td>
          <td>
            <div class="btn-group">
              <button class="btn btn-primary">Order</button>
              <button class="btn btn-success">Bill out</button>
              <button class="btn btn-danger">Cancel Order</button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>  
</div>
<div class="col-sm-8">

</div>
@endsection

@section('modals')

<div id="add-order-modal" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Items</h4>
      </div>
      <div class="modal-body">
        <form action="/items" method="post" class="form-horizontal" id="add-items-form">
        {{ csrf_field() }}
        <div class='form-group ui-widget'>
          <label for="category" class='col-sm-2'>Category:</label>
          <div class='col-sm-10'>
            <input type='text' class='form-control search' id='category' name='category' placeholder='Category' autocomplete='off'>
            <p class="help-block" id="category-help-block"></p>
          </div>
        </div>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" form="add-items-form">Save</button>
      </div>
    </div>

  </div>
</div>


@endsection