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

</style>
@endsection
@section('breadcrumb')
<div class="active section">Inventory</div>
@endsection
@section('content')
<div class="col-sm-6">
  <form action="/items" method="post" class="form-horizontal ui form" id="add-items-form">
  {{ csrf_field() }}
    <div class="field">
      <div class="four fields">
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

        <div class="field">
          <label>Menu Search</label>
          <div class="ui icon input focus">
            <input type="text" placeholder="Search...">
            <i class="search icon"></i>
          </div>
        </div>
        <div class="field">
          <label>&nbsp;</label>
          <button type="button" class="ui primary button fluid" onclick="$('#addmenu-modal').modal('show')">Add Menu</button>
        </div>
      </div>
    </div>
  </form>
  <div class="table-responsive">
    <table class="ui sortable compact table unstackable" id="menu-table">
      <thead class="full-width">
        <tr>
          <th colspan="20" class="center aligned">Menu Inventory</th>
        </tr>
        <tr>
          <th class="center aligned">Category</th>
          <th class="center aligned">Item</th>
          <th class="center aligned">Qty</th>
          <th class="center aligned">Price</th>
          <th class="center aligned"></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="center aligned">Food</td>
          <td class="center aligned">Menu 1</td>
          <td class="center aligned">1</td>
          <td class="right aligned">10.00</td>
          <td class="center aligned">
            <button class="btn btn-default" onclick="$('#editmenu-modal').modal('show')">Edit</button>
          </td>
        </tr>
        <tr>
          <td class="center aligned">Food</td>
          <td class="center aligned">Menu 2</td>
          <td class="center aligned">2</td>
          <td class="right aligned">20.00</td>
          <td class="center aligned">
          <div class="ui toggle checkbox">
            <input type="checkbox" name="public">
            <label>Subscribe to weekly newsletter</label>
          </div>
          </td>
        </tr>
        <tr>
          <td class="center aligned">Food</td>
          <td class="center aligned">Menu 3</td>
          <td class="center aligned">3</td>
          <td class="right aligned">30.00</td>
          <td class="center aligned">
            <button class="btn btn-default">Edit</button>
          </td>
        </tr>
        <tr>
          <td class="center aligned">Food</td>
          <td class="center aligned">Menu 4</td>
          <td class="center aligned">4</td>
          <td class="right aligned">40.00</td>
          <td class="center aligned">
            <button class="btn btn-default">Edit</button>
          </td>
        </tr>
        <tr>
          <td class="center aligned">Food</td>
          <td class="center aligned">Menu 5</td>
          <td class="center aligned">5</td>
          <td class="right aligned">50.00</td>
          <td class="center aligned">
            <button class="btn btn-default">Edit</button>
          </td>
        </tr>
        <tr>
          <td class="center aligned">Food</td>
          <td class="center aligned">Menu 6</td>
          <td class="center aligned">6</td>
          <td class="right aligned">60.00</td>
          <td class="center aligned">
            <button class="btn btn-default">Edit</button>
          </td>
        </tr>
        <tr>
          <td class="center aligned">Beverage</td>
          <td class="center aligned">Menu 7</td>
          <td class="center aligned">7</td>
          <td class="right aligned">70.00</td>
          <td class="center aligned">
            <button class="btn btn-default">Edit</button>
          </td>
        </tr>
        <tr>
          <td class="center aligned">Beverage</td>
          <td class="center aligned">Menu 8</td>
          <td class="center aligned">8</td>
          <td class="right aligned">80.00</td>
          <td class="center aligned">
            <button class="btn btn-default">Edit</button>
          </td>
        </tr>
        <tr>
          <td class="center aligned">Beverage</td>
          <td class="center aligned">Menu 9</td>
          <td class="center aligned">9</td>
          <td class="right aligned">90.00</td>
          <td class="center aligned">
            <button class="btn btn-default">Edit</button>
          </td>
        </tr>
        <tr>
          <td class="center aligned">Beverage</td>
          <td class="center aligned">Menu 10</td>
          <td class="center aligned">10</td>
          <td class="right aligned">100.00</td>
          <td class="center aligned">
            <button class="btn btn-default">Edit</button>
          </td>
        </tr>
        <tr>
          <td class="center aligned">Beverage</td>
          <td class="center aligned">Menu 11</td>
          <td class="center aligned">11</td>
          <td class="right aligned">110.00</td>
          <td class="center aligned">
            <button class="btn btn-default">Edit</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>


<div class="col-sm-6">
  <form action="/items" method="post" class="form-horizontal ui form" id="add-items-form">
  {{ csrf_field() }}
    <div class="field">
      <div class="six fields">
        <div class="field">
          <label>Item Search</label>
          <div class="ui icon input focus">
            <input type="text" placeholder="Search...">
            <i class="search icon"></i>
          </div>
        </div>

      </div>
    </div>
  </form>
  <div class="table-responsive">
    <table class="ui sortable compact table unstackable" id="menu-table">
      <thead class="full-width">
        <tr>
          <th colspan="20" class="center aligned">Kitchen Inventory</th>
        </tr>
        <tr>
          <th class="center aligned">Category</th>
          <th class="center aligned">Item</th>
          <th class="center aligned">Unit</th>
          <th class="center aligned">Qty</th>
          <th class="center aligned">Cost Price</th>
          <th class="center aligned"></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="center aligned">Inv Category</td>
          <td class="center aligned">Inv Item 1</td>
          <td class="center aligned">Inv Unit</td>
          <td class="center aligned">1</td>
          <td class="right aligned">10.00</td>
          <td class="center aligned">
            <button class="btn btn-success" onclick="$('#useinventory-modal').modal('show')">Use</button>
          </td>
        </tr>
        <tr>
          <td class="center aligned">Inv Category</td>
          <td class="center aligned">Inv Item 2</td>
          <td class="center aligned">Inv Unit</td>
          <td class="center aligned">2</td>
          <td class="right aligned">20.00</td>
          <td class="center aligned">
            <button class="btn btn-success">Use</button>
          </td>
        </tr>
        <tr>
          <td class="center aligned">Inv Category</td>
          <td class="center aligned">Inv Item 3</td>
          <td class="center aligned">Inv Unit</td>
          <td class="center aligned">3</td>
          <td class="right aligned">30.00</td>
          <td class="center aligned">
            <button class="btn btn-success">Use</button>
          </td>
        </tr>
        <tr>
          <td class="center aligned">Inv Category</td>
          <td class="center aligned">Inv Item 4</td>
          <td class="center aligned">Inv Unit</td>
          <td class="center aligned">4</td>
          <td class="right aligned">40.00</td>
          <td class="center aligned">
            <button class="btn btn-success">Use</button>
          </td>
        </tr>
        <tr>
          <td class="center aligned">Inv Category</td>
          <td class="center aligned">Inv Item 5</td>
          <td class="center aligned">Inv Unit</td>
          <td class="center aligned">5</td>
          <td class="right aligned">50.00</td>
          <td class="center aligned">
            <button class="btn btn-success">Use</button>
          </td>
        </tr>
        <tr>
          <td class="center aligned">Inv Category</td>
          <td class="center aligned">Inv Item 6</td>
          <td class="center aligned">Inv Unit</td>
          <td class="center aligned">6</td>
          <td class="right aligned">60.00</td>
          <td class="center aligned">
            <button class="btn btn-success">Use</button>
          </td>
        </tr>
        <tr>
          <td class="center aligned">Inv Category</td>
          <td class="center aligned">Inv Item 7</td>
          <td class="center aligned">Inv Unit</td>
          <td class="center aligned">7</td>
          <td class="right aligned">70.00</td>
          <td class="center aligned">
            <button class="btn btn-success">Use</button>
          </td>
        </tr>
        <tr>
          <td class="center aligned">Inv Category</td>
          <td class="center aligned">Inv Item 8</td>
          <td class="center aligned">Inv Unit</td>
          <td class="center aligned">8</td>
          <td class="right aligned">80.00</td>
          <td class="center aligned">
            <button class="btn btn-success">Use</button>
          </td>
        </tr>
        <tr>
          <td class="center aligned">Inv Category</td>
          <td class="center aligned">Inv Item 9</td>
          <td class="center aligned">Inv Unit</td>
          <td class="center aligned">9</td>
          <td class="right aligned">90.00</td>
          <td class="center aligned">
            <button class="btn btn-success">Use</button>
          </td>
        </tr>
        <tr>
          <td class="center aligned">Inv Category</td>
          <td class="center aligned">Inv Item 10</td>
          <td class="center aligned">Inv Unit</td>
          <td class="center aligned">10</td>
          <td class="right aligned">100.00</td>
          <td class="center aligned">
            <button class="btn btn-success">Use</button>
          </td>
        </tr>
        <tr>
          <td class="center aligned">Inv Category</td>
          <td class="center aligned">Inv Item 11</td>
          <td class="center aligned">Inv Unit</td>
          <td class="center aligned">11</td>
          <td class="right aligned">110.00</td>
          <td class="center aligned">
            <button class="btn btn-success">Use</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>




@endsection

@section('modals')
<div id="useinventory-modal" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Use Inv Item 1</h4>
      </div>
      <div class="modal-body">
        <form action="/items" method="post" id="add-items-form">
        {{ csrf_field() }}
        <div class="form-group">
          <label>Stocks</label>
          <input type="text" name="" placeholder="Stocks" class="form-control" value="1" readonly>
        </div>

        <div class="form-group">
          <label>How Many</label>
          <input type="text" name="" placeholder="Quantity" class="form-control">
        </div>

        <div class="form-group">
          <label>Comments</label>
          <textarea placeholder="Comments" class="form-control"></textarea>
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

<div id="editmenu-modal" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit Menu 1</h4>
      </div>
      <div class="modal-body">
        <form action="/items" method="post" id="add-items-form">
        {{ csrf_field() }}
        <div class="form-group">
          <label>Category</label>
          <input type="text" name="" placeholder="Category" class="form-control" value="Food">
        </div>

        <div class="form-group">
          <label>Subcategory</label>
          <input type="text" name="" placeholder="Subcategory" class="form-control" value="Food">
        </div>


        <div class="form-group">
          <label>Item</label>
          <input type="text" name="" placeholder="Item" class="form-control" value="Menu 1">
        </div>

        <div class="form-group">
          <label>Quantity</label>
          <input type="text" name="" placeholder="Quantity" class="form-control" value="1">
        </div>

        <div class="form-group">
          <label>Price</label>
          <input type="text" name="" placeholder="Price" class="form-control" value="10.00">
        </div>

        <div class="form-group">
          <label>Comments</label>
          <textarea placeholder="Comments" class="form-control"></textarea>
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

<div id="addmenu-modal" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Menu</h4>
      </div>
      <div class="modal-body">
        <form action="/items" method="post" id="add-items-form">
        {{ csrf_field() }}
        <div class="form-group">
          <label>Category</label>
          <input type="text" name="" placeholder="Category" class="form-control">
        </div>

        <div class="form-group">
          <label>Subcategory</label>
          <input type="text" name="" placeholder="Subcategory" class="form-control">
        </div>

        <div class="form-group">
          <label>Item</label>
          <input type="text" name="" placeholder="Item" class="form-control">
        </div>

        <div class="form-group">
          <label>Quantity</label>
          <input type="text" name="" placeholder="Quantity" class="form-control">
        </div>

        <div class="form-group">
          <label>Price</label>
          <input type="text" name="" placeholder="Price" class="form-control">
        </div>

        <div class="form-group">
          <label>Comments</label>
          <textarea placeholder="Comments" class="form-control"></textarea>
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