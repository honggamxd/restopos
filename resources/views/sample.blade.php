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
}
#complete-order-table tr td,#complete-order-table tr th{
  border: 1px solid black;
  padding: 0px 2px 0px 2px;
}

</style>
@endsection
@section('content')
<div class="col-sm-6">
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
              <a href="/bill" class="btn btn-success">Bill out</a>
              <a href="/billperitem" class="btn btn-info">Bill out per Item</a>
              <button class="btn btn-default">View Orders</button>
              <button class="btn btn-danger">Cancel Order</button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>  
</div>
<div class="col-sm-6">

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
                    <td></td>
                    <td>Qty</td>
                    <td>Price</td>
                    <td class="right aligned">
                    </td>
                  </tr>
                  <tr>
                    <td>Menu 1</td>
                    <td>2</td>
                    <td>20.00</td>
                    <td class="right aligned">
                      <button class="btn btn-success"><i class="fa fa-arrow-right" aria-hidden="true"></i> Add to List</button>
                    </td>
                  </tr>
                  <tr>
                    <td>Menu 2</td>
                    <td>2</td>
                    <td>20.00</td>
                    <td class="right aligned">
                      <button class="btn btn-success"><i class="fa fa-arrow-right" aria-hidden="true"></i> Add to List</button>
                    </td>
                  </tr>
                  <tr>
                    <td>Menu 3</td>
                    <td>3</td>
                    <td>30.00</td>
                    <td class="right aligned">
                      <button class="btn btn-success"><i class="fa fa-arrow-right" aria-hidden="true"></i> Add to List</button>
                    </td>
                  </tr>
                  <tr>
                    <td>Menu 4</td>
                    <td>4</td>
                    <td>40.00</td>
                    <td class="right aligned">
                      <button class="btn btn-success"><i class="fa fa-arrow-right" aria-hidden="true"></i> Add to List</button>
                    </td>
                  </tr>
                  <tr>
                    <td>Menu 5</td>
                    <td>5</td>
                    <td>50.00</td>
                    <td class="right aligned">
                      <button class="btn btn-success"><i class="fa fa-arrow-right" aria-hidden="true"></i> Add to List</button>
                    </td>
                  </tr>
                  <tr>
                    <td>Menu 6</td>
                    <td>6</td>
                    <td>60.00</td>
                    <td class="right aligned">
                      <button class="btn btn-success"><i class="fa fa-arrow-right" aria-hidden="true"></i> Add to List</button>
                    </td>
                  </tr>
                  <tr>
                    <td>Menu 7</td>
                    <td>7</td>
                    <td>70.00</td>
                    <td class="right aligned">
                      <button class="btn btn-success"><i class="fa fa-arrow-right" aria-hidden="true"></i> Add to List</button>
                    </td>
                  </tr>
                  <tr>
                    <td>Menu 8</td>
                    <td>8</td>
                    <td>80.00</td>
                    <td class="right aligned">
                      <button class="btn btn-success"><i class="fa fa-arrow-right" aria-hidden="true"></i> Add to List</button>
                    </td>
                  </tr>
                  <tr>
                    <td>Menu 9</td>
                    <td>9</td>
                    <td>90.00</td>
                    <td class="right aligned">
                      <button class="btn btn-success"><i class="fa fa-arrow-right" aria-hidden="true"></i> Add to List</button>
                    </td>
                  </tr>
                  <tr>
                    <td>Menu 10</td>
                    <td>10</td>
                    <td>100.00</td>
                    <td class="right aligned">
                      <bu tton class="btn btn-success"><i class="fa fa-arrow-right" aria-hidden="true"></i> Add to List</button>
                    </td>
                  </tr>
                  <tr>
                    <td>Menu 11</td>
                    <td>11</td>
                    <td>110.00</td>
                    <td class="right aligned">
                      <button class="btn btn-success"><i class="fa fa-arrow-right" aria-hidden="true"></i> Add to List</button>
                    </td>
                  </tr>
                </tbody>
                <tfoot>

                </tfoot>
              </table>
            </div>
          </div>
          <div class="col-sm-6">
            <div id="printarea">
            <table id="complete-order-table">
              <thead>
                <tr>
                  <th style="text-align: center;">Order 1</th>
                  <th style="text-align: center;">Table 1</th>
                </tr>
                <tr>
                  <th style="text-align: center;">Menu</th>
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
              <a href="/order" class="btn btn-primary"><span class="glyphicon glyphicon-print"></span> Print</a>
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