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

</style>
@endsection
@section('content')
<div class="col-sm-4">
  <button class="btn btn-primary" onclick="$('#add-table-modal').modal('show')">Add Table</button>
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
              <button class="btn btn-primary" onclick="$('#add-order-modal').modal('show')">Order</button>
              <button class="btn btn-danger">Remove</button>
            </div>
          </td>
        </tr>
        <tr class="info">
          <td>002</td>
          <td>
            <div class="btn-group">
              <button class="btn btn-primary" onclick="$('#add-order-modal').modal('show')">Order</button>
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

<div id="add-table-modal" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Table</h4>
      </div>
      <div class="modal-body">
        <form action="/items" method="post" class="form-horizontal" id="add-items-form">
        {{ csrf_field() }}
        <div class="form-group">
          <div class="col-sm-12">
            <select class="form-control">
              <option value="">Table Number</option>
              <option value="003">003</option>
              <option value="004">004</option>
              <option value="005">005</option>
              <option value="006">006</option>
            </select>
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


<div id="add-order-modal" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Table</h4>
      </div>
      <div class="modal-body">
        <form action="/items" method="post" class="form-horizontal ui form" id="add-items-form">
        {{ csrf_field() }}
        <div class="row">
          <div class="col-sm-6">
            <div class="field">
              <div class="two fields">
                <div class="field">
                  <label>Category</label>
                  <select class="ui dropdown fluid">
                    <option value="food">Food</option>
                    <option value="beverage">Beverage</option>
                  </select>
                </div>
                <div class="field">
                  <label>Subcategory</label>
                  <select class="ui dropdown fluid">
                    <option value="food">Food</option>
                    <option value="beverage">Beverage</option>
                  </select>
                </div>
              </div>

              <div class="field">
                <label>Menu Search</label>
                <div class="ui fluid icon input focus">
                  <input type="text" placeholder="Search...">
                  <i class="search icon"></i>
                </div>
              </div>

            <div class="table-responsive">
              <table class="ui sortable compact table" id="menu-table">
                <thead class="full-width">
                  <tr>
                    <th colspan="2" class="center aligned">Menu</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Menu 1</td>
                    <td class="right aligned">
                      <button class="btn btn-success"><i class="fa fa-arrow-right" aria-hidden="true"></i> Add to List</button>
                    </td>
                  </tr>
                  <tr>
                    <td>Menu 2</td>
                    <td class="right aligned">
                      <button class="btn btn-success"><i class="fa fa-arrow-right" aria-hidden="true"></i> Add to List</button>
                    </td>
                  </tr>
                  <tr>
                    <td>Menu 3</td>
                    <td class="right aligned">
                      <button class="btn btn-success"><i class="fa fa-arrow-right" aria-hidden="true"></i> Add to List</button>
                    </td>
                  </tr>
                  <tr>
                    <td>Menu 4</td>
                    <td class="right aligned">
                      <button class="btn btn-success"><i class="fa fa-arrow-right" aria-hidden="true"></i> Add to List</button>
                    </td>
                  </tr>
                  <tr>
                    <td>Menu 5</td>
                    <td class="right aligned">
                      <button class="btn btn-success"><i class="fa fa-arrow-right" aria-hidden="true"></i> Add to List</button>
                    </td>
                  </tr>
                  <tr>
                    <td>Menu 6</td>
                    <td class="right aligned">
                      <button class="btn btn-success"><i class="fa fa-arrow-right" aria-hidden="true"></i> Add to List</button>
                    </td>
                  </tr>
                  <tr>
                    <td>Menu 7</td>
                    <td class="right aligned">
                      <button class="btn btn-success"><i class="fa fa-arrow-right" aria-hidden="true"></i> Add to List</button>
                    </td>
                  </tr>
                  <tr>
                    <td>Menu 8</td>
                    <td class="right aligned">
                      <button class="btn btn-success"><i class="fa fa-arrow-right" aria-hidden="true"></i> Add to List</button>
                    </td>
                  </tr>
                  <tr>
                    <td>Menu 9</td>
                    <td class="right aligned">
                      <button class="btn btn-success"><i class="fa fa-arrow-right" aria-hidden="true"></i> Add to List</button>
                    </td>
                  </tr>
                  <tr>
                    <td>Menu 10</td>
                    <td class="right aligned">
                      <button class="btn btn-success"><i class="fa fa-arrow-right" aria-hidden="true"></i> Add to List</button>
                    </td>
                  </tr>
                  <tr>
                    <td>Menu 11</td>
                    <td class="right aligned">
                      <button class="btn btn-success"><i class="fa fa-arrow-right" aria-hidden="true"></i> Add to List</button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
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

@section('scripts')
<script type="text/javascript">
  $('table').tablesort();
</script>
@endsection