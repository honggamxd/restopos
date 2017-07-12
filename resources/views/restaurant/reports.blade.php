@extends('layouts.main')

@section('title', 'Restaurant POS')

@section('css')
<style type="text/css">

</style>
@endsection
@section('content')
 
<div class="col-sm-2">
  <label>Navigation:</label>
  <a href = "#" class = "list-group-item active">Sales Reports</a>
  <a href = "#" class = "list-group-item">Reports Menu</a>
  <a href = "#" class = "list-group-item">Reports Menu</a>
  <a href = "#" class = "list-group-item">Reports Menu</a>
  <a href = "#" class = "list-group-item">Reports Menu</a>
  <a href = "#" class = "list-group-item">Reports Menu</a>
  <a href = "#" class = "list-group-item">Reports Menu</a>
  <a href = "#" class = "list-group-item">Reports Menu</a>
  <a href = "#" class = "list-group-item">Reports Menu</a>
  <a href = "#" class = "list-group-item">Reports Menu</a>
  <a href = "#" class = "list-group-item">Reports Menu</a>
  <a href = "#" class = "list-group-item">Reports Menu</a>
  <a href = "#" class = "list-group-item">Reports Menu</a>
  <a href = "#" class = "list-group-item">Reports Menu</a>
</div>
<div class="col-sm-4">
  <h1 style="text-align: center;">Sales Reports</h1>
  <div class="form-group">
    <label>Date From</label>
    <input type="text" name="" class="form-control" placeholder="Date From">
  </div>

  <div class="form-group">
    <label>Date To</label>
    <input type="text" name="" class="form-control" placeholder="Date To">
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