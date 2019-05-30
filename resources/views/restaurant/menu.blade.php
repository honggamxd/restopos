@extends('layouts.main')

@section('title', 'Restaurant Menu')

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
<div class="active section">{{App\Restaurant::find(Auth::user()->restaurant_id)->name}} Menu</div>
@endsection
@section('content')
<div class="col-sm-12">
<h1 style="text-align: center;">{{App\Restaurant::find(Auth::user()->restaurant_id)->name}} Menu</h1>
<br><br>
  <form class="form-horizontal ui form">
  {{ csrf_field() }}
    <div class="field">
      <div class="four fields">
        <div class="field">
          <label>Category</label>
          <select class="ui dropdown fluid" ng-change="category_change_menu_list(this)" ng-model="select_category" ng-init="select_category='all'">
            <option value="all">All Menu</option>
            @foreach($categories as $category)
            <option value="{{$category}}">{{$category}}</option>
            @endforeach
          </select>
        </div>
        <div class="field">
          <label>Subcategory</label>
          <select class="form-control" ng-change="subcategory_change_menu_list(this)" ng-model="select_subcategory" ng-options="item for item in subcategories">
          <option value="">All Subcategories</option>
          </select>
        </div>

        <div class="field">
          <label>Menu Search</label>
          <div class="ui icon input focus">
            <input type="text" placeholder="Search..." id="search-menu" ng-keyup="search_menu()" ng-model="search_string">
            <i class="search icon"></i>
          </div>
        </div>
        <div class="field">
          <label>&nbsp;</label>
          <button type="button" class="ui primary button fluid" ng-click="open_add_menu_form()">Add Menu</button>
        </div>
      </div>
    </div>
  </form>
  <div class="table-responsive">
    <table class="ui sortable compact table unstackable" id="menu-table">
      <thead class="full-width">
        <tr>
          <th class="center aligned">Category</th>
          <th class="center aligned">Subcategory</th>
          <th class="center aligned">Item</th>
          <th class="center aligned">Price</th>
        </tr>
      </thead>
      <tbody ng-cloak>
        <tr ng-repeat="(index, menu_data) in menu">
          <td class="center aligned middle aligned" ng-bind="menu_data.category" ng-cloak></td>
          <td class="center aligned middle aligned" ng-bind="menu_data.subcategory" ng-cloak></td>
          <td class="center aligned middle aligned" ng-bind="menu_data.name" ng-cloak></td>
          <td class="center aligned middle aligned" ng-cloak>@{{menu_data.price|currency:""}}</td>
          <td class="center aligned middle aligned" style="width: 12vw">
            <div class="ui toggle checkbox">
              <input type="checkbox" name="public" ng-change="available_to_menu(this)" ng-model="menu_data.is_prepared">
              <label ng-if="menu_data.is_prepared">Available</label>
              <label ng-if="!menu_data.is_prepared">Unavailable</label>
            </div>
          </td>
          <td class="center aligned middle aligned" ng-cloak>
            <a href="javascript:void(0);" ng-click="edit_menu(this)">Edit</a>
            <b ng-if="!menu_data.is_prepared"> | </b>
            <a href="javascript:void(0);" ng-if="!menu_data.is_prepared" ng-click="delete_menu(menu_data,index)">Delete</a>
          </td>
        </tr>
      </tbody>
      <tfoot>
        <tr ng-if="menu | isEmpty">
          <td colspan="20" style="text-align: center;">
            <h1 ng-if="loading">
              <img src="{{asset('assets/images/loading.gif')}}" style="height: 70px;">
              <br>
              LOADING
            </h1>
            <h1>
              <span ng-if="!loading" ng-cloak>NO DATA</span>
              <span ng-if="loading" ng-cloak></span>
            </h1>
          </td>
        </tr>
      </tfoot>
    </table>
    <div ng-bind-html="pages" class="text-center" ng-cloak></div>
  </div>
</div>

@endsection

@section('modals')
<div id="add-menu-modal" class="modal fade" data-backdrop="static" data-keyboard="false" role="dialog" tabindex="-1" ng-controller="add_menu-controller">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Menu</h4>
      </div>
      <div class="modal-body">
        <form id="add-items-form" ng-submit="add_menu()">
        <input type="hidden" name="restaurant_id" ng-model="formdata.restaurant_id">
        
        <div class="form-group">
          <label>Category</label>
          <select name="category" class="form-control" ng-model="formdata.category" required>
            <option value="">Select Category</option>
            @foreach ($categories as $category)
              <option value="{{$category}}">{{$category}}</option>
            @endforeach
          </select>
          <p class="help-block" ng-bind="formerrors.category[0]"></p>
        </div>

        <div class="form-group">
          <label>Subcategory</label>
          <input type="text" name="subcategory" id="add-item-subcategory" placeholder="Subcategory" class="form-control" ng-model="formdata.subcategory" required>
          <p class="help-block" ng-bind="formerrors.subcategory[0]"></p>
        </div>

        <div class="form-group">
          <label>Name</label>
          <input type="text" name="name" placeholder="Name" class="form-control" ng-model="formdata.name" required>
          <p class="help-block" ng-bind="formerrors.name[0]"></p>
        </div>

        <div class="form-group">
          <label>Price</label>
          <input type="number" min="0" step="0.01" name="price" placeholder="Price" class="form-control" ng-model="formdata.price" required>
          <p class="help-block" ng-bind="formerrors.price[0]"></p>
        </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="ui default button" data-dismiss="modal">Cancel</button>
        <button type="submit" class="ui primary button" form="add-items-form" ng-disabled="submit" ng-class="{'loading':submit}">Save</button>
      </div>
    </div>
  </div>
</div>

<div id="edit-menu-modal" class="modal fade" data-backdrop="static" data-keyboard="false" role="dialog" tabindex="-1" ng-controller="add_menu-controller">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit Menu</h4>
      </div>
      <div class="modal-body">
        <form id="edit-items-form" ng-submit="update_menu()">
        <input type="hidden" name="restaurant_id" ng-model="formdata.restaurant_id">
        
        <div class="form-group">
          <label>Category</label>
          <select name="category" class="form-control" ng-model="formdata.category" required>
            <option value="">Select Category</option>
            @foreach ($categories as $category)
              <option value="{{$category}}">{{$category}}</option>
            @endforeach
          </select>
          <p class="help-block" ng-bind="formerrors.category[0]"></p>
        </div>

        <div class="form-group">
          <label>Subcategory</label>
          <input type="text" name="subcategory" id="edit-item-subcategory" placeholder="Subcategory" class="form-control" ng-model="formdata.subcategory" required>
          <p class="help-block" ng-bind="formerrors.subcategory[0]"></p>
        </div>

        <div class="form-group">
          <label>Name</label>
          <input type="text" name="name" placeholder="Name" class="form-control" ng-model="formdata.name" required>
          <p class="help-block" ng-bind="formerrors.name[0]"></p>
        </div>

        <div class="form-group">
          <label>Price</label>
          <input type="number" min="0" step="0.01" name="price" placeholder="Price" class="form-control" ng-model="formdata.price" required>
          <p class="help-block" ng-bind="formerrors.price[0]"></p>
        </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="ui default button" data-dismiss="modal">Cancel</button>
        <button type="submit" class="ui primary button" form="edit-items-form" ng-disabled="submit" ng-class="{'loading':submit}">Save</button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script type="text/javascript">
  $('table').tablesort();

  // $('.ui.checkbox').checkbox('enable');
  
  app.controller('add_menu-controller', function($scope,$http, $sce) {
    angular.element('.ui.checkbox').checkbox('enable');
  });


  app.controller('content-controller', function($scope,$http, $sce) {
    $scope.formdata = {};
    $scope.formerrors = {};
    $scope.formdata.restaurant_id = {{Auth::user()->restaurant_id}};
    $("#search-menu").autocomplete({
        source: "/api/restaurant/menu/search/name",
        select: function(event, ui) {
            $scope.search_string = ui.item.value;
            show_menu();
        }
    });
    $('#add-item-subcategory').autocomplete({
      source: function(request, response) {
              $.ajax({
                  url: '/api/restaurant/menu/subcategory/list',
                  dataType: "json",
                  data: {
                      term : request.term,
                      category : $scope.formdata.category
                  },
                  success: function(data) {
                      response(data);
                  }
              });
          },
          delay: 300
    });
    $('#edit-item-subcategory').autocomplete({
      source: function(request, response) {
              $.ajax({
                  url: '/api/restaurant/menu/subcategory/list',
                  dataType: "json",
                  data: {
                      term : request.term,
                      category : $scope.formdata.category
                  },
                  success: function(data) {
                      response(data);
                  }
              });
          },
          delay: 300
    });
    show_menu();
    $scope.search_menu = function(){
      show_menu();
    }
    function show_menu(myUrl,category='all',subcategory='all') {
      myUrl = (typeof myUrl !== 'undefined') && myUrl !== "" ? myUrl : '/api/restaurant/menu/list/list';
      $scope.loading = true;
      $scope.menu = {};
      $scope.pages = "";
      $http({
        method : "GET",
        url : myUrl,
        params: {
          category: category,
          subcategory: subcategory,
          search: $scope.search_string,
        },
      }).then(function mySuccess(response) {
        $scope.menu = response.data.result.data;
        $scope.pages = $sce.trustAsHtml(response.data.pagination);
        $scope.loading = false;
      }, function myError(rejection) {
          if(rejection.status != 422){
            request_error(rejection.status);
          }else if(rejection.status == 422){
            var errors = rejection.data;
          }
        $scope.loading = false;
      });
    }

    $scope.category_change_menu_list = function() {
      show_menu("",$scope.select_category);
      $http({
          method : "GET",
          url : "/api/restaurant/menu/subcategory",
          params: {
            category: $scope.select_category
          },
      }).then(function mySuccess(response) {
          $scope.subcategories = response.data;
          // console.log(response.data);
      }, function(rejection) {
        if(rejection.status != 422){
          request_error(rejection.status);
        }else if(rejection.status == 422){ 
          console.log(rejection.statusText);
        }
      });
    }

    $scope.open_add_menu_form = function(){
      $scope.formdata.category = "";
      $scope.formdata.subcategory = "";
      $scope.formdata.name = "";
      $scope.formdata.price = "";
      $('#add-menu-modal').modal('show')
    }

    $scope.subcategory_change_menu_list = function() {
      show_menu("",$scope.select_category,$scope.select_subcategory);
    }

    $scope.add_menu = function() {
      $scope.submit = true;
      $scope.formerrors = {};
      $http({
        method: 'POST',
        url: '/api/restaurant/menu',
        data: $.param($scope.formdata),
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
      })
      .then(function(response) {
        $scope.submit = false;
        $.notify($scope.formdata.name+" is added.");
        $scope.formdata.category = "";
        $scope.formdata.subcategory = "";
        $scope.formdata.name = "";
        $scope.formdata.price = "";
        show_menu();
      }, function(rejection) {
        var errors = rejection.data;
        $scope.formerrors = errors;
        $scope.submit = false;
      });
    }
    $(document).on('click','.pagination li a',function(e) {
      e.preventDefault();
      show_menu(e.target.href,$scope.select_category,$scope.select_subcategory);
    });

    $scope.update_menu = function() {
      $scope.submit = true;
      $scope.formerrors = {};
      $http({
        method: 'PUT',
        url: '/api/restaurant/menu',
        data: $.param($scope.formdata),
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
      })
      .then(function(response) {
        $scope.submit = false;
        $.notify($scope.formdata.name+" is updated.");
        $('#edit-menu-modal').modal('hide');
      }, function(rejection) {
        var errors = rejection.data;
        $scope.formerrors = errors;
        $scope.submit = false;
      });
    }

    $scope.edit_menu = function(data) {
      // console.log(data);
      $scope.formdata = data.menu_data;
      $scope.formdata.price = parseInt(data.menu_data.price);
      $('#edit-menu-modal').modal('show');
    }
    $scope.delete_menu = function(data,index) {
      // console.log(index);
      alertify.confirm(
            'Delete Menu',
            'Are you sure you want to delete menu <b>'+data.name+'</b> permanently?',
            function(){
                $scope.remove_menu(data,index);
            },
            function()
            {
                // alertify.error('Cancel')
            }
        );
    }

    $scope.remove_menu = function(data,index) {
      $http({
        method: 'DELETE',
        url: '/api/restaurant/menu/list/'+data.id,
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
      })
      .then(function(response) {
        $scope.submit = false;
        $scope.menu.shift(index,1);
        $.notify(data.name+" has beed deleted.");
      }, function(rejection) {
        var errors = rejection.data;
        $scope.formerrors = errors;
        $scope.submit = false;
      });
    }


    $scope.available_to_menu = function(data) {
      // console.log(data.menu_data.is_prepared);
      $scope.formdata.is_prepared = data.menu_data.is_prepared;
      $http({
        method: 'PUT',
        url: '/api/restaurant/menu/list/'+data.menu_data.id,
        data: $.param($scope.formdata),
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
      })
      .then(function(response) {
        $.notify(data.menu_data.name + " is now " + ($scope.formdata.is_prepared?"available":"unavailable")+ ".",($scope.formdata.is_prepared?"success":"info"));
      }, function(rejection) {
        var errors = rejection.data;
      });
    }
  });

  
</script>
@endpush