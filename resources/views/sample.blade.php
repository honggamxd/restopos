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
<div class="col-sm-12">
  <div class="table-responsive">
    <table class="ui unstackable celled table">
      <thead>
        <tr>
          <th class="center aligned">Table</th>
          <th class="center aligned">Time</th>
          <th class="center aligned">Pax</th>
          <th class="center aligned">Total</th>
          <th class="center aligned"><button class="ui basic primary button" onclick="$('#add-table-modal').modal('show')">Add Table</button></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td style="width: 30vw;" class="center aligned">001</td>
          <td class="center aligned">10:00 AM</td>
          <td class="center aligned">3 <i class="fa fa-users" aria-hidden="true"></i></td>
          <td class="center aligned">10,000.00</td>
          <td style="width: 26vw;">
            <div class="ui buttons">
              <button class="ui basic green button" onclick="$('#add-order-modal').modal('show')"><i class="fa fa-file-text-o" aria-hidden="true"></i> Order</button>
              <button class="ui basic red button"><i class="fa fa-trash-o" aria-hidden="true"></i> Remove</button>
            </div>
          </td>
        </tr>
        <tr>
          <td class="center aligned">002</td>
          <td class="center aligned">10:00 AM</td>
          <td class="center aligned">3 <i class="fa fa-users" aria-hidden="true"></i></td>
          <td class="center aligned">10,000.00</td>
          <td>
            <div class="ui buttons">
              <button class="ui basic green button" onclick="$('#add-order-modal').modal('show')"><i class="fa fa-file-text-o" aria-hidden="true"></i> Order</button>
              <a href="/bill" class="ui basic violet button"><i class="fa fa-calculator" aria-hidden="true"></i> Bill out</a>
              <button class="ui basic red button"><i class="fa fa-trash-o" aria-hidden="true"></i> Cancel Orders</button>
            </div>
            <div class="btn-group">

            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>  
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
        <h4 class="modal-title">Orders</h4>
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
            <table class="ui sortable compact table unstackable">
              <thead>
                <tr>
                  <th style="text-align: center;">Order 1</th>
                  <th style="text-align: center;">Table 1</th>
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