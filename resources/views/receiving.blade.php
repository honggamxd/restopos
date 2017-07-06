@extends('layouts.main')

@section('title', 'Restaurant POS')

@section('css')
<style type="text/css">

</style>
@endsection
@section('content')
<div class="col-sm-9">
  <div class="row">
    <div class="col-sm-6">
      <label>Search Item</label>
      <div class="ui icon input fluid">
        <i class="search icon"></i>
        <input type="text" placeholder="Search">
      </div>  
    </div>
    <div class="col-sm-6">
      <label>Search Category</label>
      <div class="ui icon input fluid">
        <i class="search icon"></i>
        <input type="text" placeholder="Search">
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-12">
      <div class="table-responsive">
        <table class="table table-condensed">
          <thead>
            <tr>
              <td>Category</td>
              <td>Item</td>
              <td>Stocks</td>
              <td>Qty</td>
              <td>Cost Price</td>
              <td></td>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Inv Category</td>
              <td>Inv Item 1</td>
              <td>1</td>
              <td>
                <div class="ui input">
                  <input type="number" placeholder="" value="20">
                </div>
              </td>
              <td>
                <div class="ui input">
                  <input type="number" placeholder="" value="15">
                </div>
              </td>
              <td><button type="button" class="btn btn-danger">&times;</button></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<div class="col-sm-3">
  <div class="form-group">
    <label>PO Number</label>
    <input type="text" name="" placeholder="PO Number" class="form-control">
  </div>
  <div class="form-group">
    <label>Comments</label>
    <textarea placeholder="Comments" class="form-control"></textarea>
  </div>
  <div class="form-group">
    <label>Controls</label>
    <button class="btn btn-primary btn-block">Save</button>
    <button class="btn btn-danger btn-block">Cancel</button>
  </div>
</div>
@endsection

@section('modals')

@endsection

@section('scripts')
<script type="text/javascript">
  $('table').tablesort();
</script>
@endsection