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
          <button class="ui primary button fluid">Add Menu</button>
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
            <button class="btn btn-default">Edit</button>
          </td>
        </tr>
        <tr>
          <td class="center aligned">Food</td>
          <td class="center aligned">Menu 2</td>
          <td class="center aligned">2</td>
          <td class="right aligned">20.00</td>
          <td class="center aligned">
            <button class="btn btn-default">Edit</button>
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
            <button class="btn btn-success">Use</button>
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



@endsection

@section('scripts')
<script type="text/javascript">
  $('table').tablesort();
</script>
@endsection