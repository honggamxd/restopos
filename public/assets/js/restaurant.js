 $('#customer-table').tablesort();
 $('#list-order-table').tablesort();
 $('#add-table-modal').on('shown.bs.modal', function() {
   $('#select-tablenumber').focus();
 });
 var app = angular.module('main', []);
 app.controller('content-controller', function($scope, $http, $sce, $window) {
   $scope._token = csrf_token;
   $scope.formdata = {};
   $scope.formdata.settlement = {};
   $scope.table = {};
   $scope.formdata._token = $scope._token;
   shortcut.add("Alt+A", function() {
     show_table();
     $scope.formdata.pax = '';
     $scope.formdata.pax = '';
     $scope.formdata.sc_pwd = '';
     $scope.formdata.guest_name = '';
     show_server();
     $('#add-table-modal').modal('show');
   });
   $scope.formdata = {};
   $scope.formdata.settlement = {};
   $scope.table = {};
   $scope.formdata.restaurant_id = restaurant_id;
   $scope.add_table = function() {
     $scope.formerrors = {};
     $scope.submit = true;
     $scope.formdata._token = $scope._token;
     $http({
       method: 'POST',
       url: '/api/restaurant/table/customer/add',
       data: $.param($scope.formdata),
       headers: {
         'Content-Type': 'application/x-www-form-urlencoded'
       }
     }).then(function(response) {
       console.log(response.data);
       show_table();
       $scope.submit = false;
       $("#add-table-modal").modal("hide");
       $.notify("A Customer has been added to the list.");
     }, function(rejection) {
       if (rejection.status != 422) {
         request_error(rejection.status);
       } else if (rejection.status == 422) {
         var errors = rejection.data;
         $scope.formerrors.sc_pwd = errors.sc_pwd;
         $scope.formerrors.pax = errors.pax;
         $scope.formerrors.server_id = errors['server_id.id'];
       }
       $scope.submit = false;
     });
   }
   $scope.pax_changed = function(data) {
     $scope.formdata.sc_pwd = 0;
   }
   $scope.table = {};
   $scope.has_table = false;

   function show_table(table_data) {
     $scope.table = {};
     $http({
       method: "GET",
       url: "/api/restaurant/table/list/serve",
     }).then(function mySuccess(response) {
       $scope.table = response.data.result;
       if (table_data == null) {
         $scope.formdata.table_id = $scope.table[0];
       } else {
         $scope.formdata.table_id = table_data;
       }
       if (typeof Object.keys($scope.table)[0] === 'undefined') {
         $scope.has_table = false;
       } else {
         $scope.has_table = true;
       }
     }, function(rejection) {
       if (rejection.status != 422) {
         request_error(rejection.status);
       } else if (rejection.status == 422) {
         console.log(rejection.statusText);
       }
     });
   }
   $scope.show_table = function() {
     $scope.formdata.pax = '';
     $scope.formdata.pax = '';
     $scope.formdata.sc_pwd = '';
     $scope.formdata.guest_name = '';
     $('#add-table-modal').modal('show');
     show_table();
     show_server();
   }
   $scope.table_customers = {};

   function show_table_customers() {
     $http({
       method: "GET",
       url: "/api/restaurant/table/customer/list",
     }).then(function mySuccess(response) {
       if (angular.equals($scope.table_customers, {})) {
         $scope.table_customers = response.data.result;
       } else if (angular.equals($scope.table_customers, response.data.result)) {} else {
         $scope.table_customers = response.data.result;
       }
       if (!$('.modal').is(':visible')) {
         setTimeout(show_table_customers, 1500);
       }
     }, function(rejection) {
       if (rejection.status != 422) {
         request_error(rejection.status);
       } else if (rejection.status == 422) {
         console.log(rejection.statusText);
       }
       if (!$('.modal').is(':visible')) {
         setTimeout(show_table_customers, 1500);
       }
     });
   }
   $('.modal').on('hidden.bs.modal', function() {
     show_table_customers();
   });
   $scope.submit = false;
   $('.modal').on('show.bs.modal', function() {
     $('.button').hide();
     $('button').hide();
     setTimeout(function() {
       $('.button').show();
       $('button').show();
     }, 1000);
   })
   show_table_customers();
   $scope.delete_table_customer = function(data) {
     console.log(data.$parent.customer_data.id);
     $scope.formdata._token = $scope._token;
     $http({
       method: 'POST',
       url: '/api/restaurant/table/customer/remove/' + data.$parent.customer_data.id,
       data: $.param($scope.formdata),
       headers: {
         'Content-Type': 'application/x-www-form-urlencoded'
       }
     }).then(function(response) {
       $.notify('A Customer has been removed from the list.', 'info');
     }, function(rejection) {
       if (rejection.status != 422) {
         request_error(rejection.status);
       } else if (rejection.status == 422) {
         var errors = rejection.data;
       }
     });
   };
   $scope.table_name = "";
   $('#add-order-modal').on('shown.bs.modal', function() {
     $('#search-menu').focus();
   });
   $scope.add_order = function(data) {
     $scope.table_customer_id = "";
     $scope.table_name = "";
     if (!data.$parent.customer_data.has_billed_out) {
       $("#add-order-modal").modal("show");
       $scope.table_customer_id = data.$parent.customer_data.id;
       $scope.table_name = data.$parent.customer_data.table_name;
       show_cart(data.$parent.customer_data.id);
       show_menu();
     } else {
       $.notify("The customer has already billed out", 'error');
     }
   }
   $scope.cancellation_orders = function(type, data) {
     if (type == 'before') {
       console.log(data.order);
       console.log($scope.order_detail);
       $('#view-list-order-modal').modal('hide');
       $('#view-order-modal').modal('hide');
       $('#before-bill-out-cancellation-order-modal').modal('show');
     } else if (type == 'after') {
       $scope.bill_preview = {};
       $scope.bill_preview.customer_data = {};
       $scope.bill_preview.discount = {};
       $('#view-list-order-modal').modal('hide');
       $http({
         method: "GET",
         url: "/api/restaurant/table/customer/bill/preview/" + data.$parent.customer_data.id,
       }).then(function mySuccess(response) {
         $scope.bill_preview.items = response.data.result;
         angular.forEach($scope.bill_preview.items, function(value, key) {
           value.quantity_to_cancel = 0;
           value.quantity = $scope.bill_preview.items[key].quantity;
         });
         $scope.bill_preview.total = response.data.total;
         $scope.bill_preview.customer_data = response.data.customer_data;
         $scope.bill_preview.discount = response.data.discount;
         $scope.bill_preview.gross_billing = response.data.gross_billing;
         $scope.bill_preview.net_billing = response.data.net_billing;
         $scope.bill_preview.table_customer_id = data.$parent.customer_data.id;
         $('#after-bill-out-cancellation-order-modal').modal('show');
       }, function(rejection) {
         if (rejection.status != 422) {
           request_error(rejection.status);
         } else if (rejection.status == 422) {
           console.log(rejection.statusText);
         }
       });
     }
   }
   $scope.delete_cancellation_request = function(data) {
     console.log(data.$parent.customer_data.id);
     var formdata = {
       id: data.$parent.customer_data.id,
       _token: $scope._token
     };
     $http({
       method: 'POST',
       url: '/api/restaurant/orders/user-cancellations/delete/' + data.$parent.customer_data.id,
       data: $.param(formdata),
       headers: {
         'Content-Type': 'application/x-www-form-urlencoded'
       }
     }).then(function(response) {
       $.notify('Your request for cancellation of orders has been deleted.', 'info');
       $scope.customer_data.has_cancellation_request = 0;
     }, function(rejection) {
       if (rejection.status != 422) {
         request_error(rejection.status);
       } else if (rejection.status == 422) {
         var errors = rejection.data;
         angular.forEach(errors, function(value, key) {
           $.notify(value[0], 'error');
         });
       }
     });
   }
   $scope.cancel_orders = function(type, data) {
     $scope.submit = true;
     if (type == 'before') {
       /*console.log(data);*/
       var formdata = {
         restaurant_table_customer_id: data.table_customer_id,
         restaurant_order_id: data.order.id,
         items: data.order_detail,
         reason_cancelled: $scope.formdata.reason_cancelled,
         _token: $scope._token
       };
       /*console.log(formdata);*/
       $http({
         method: 'POST',
         url: '/api/restaurant/table/order/cancel/request/before',
         data: $.param(formdata),
         headers: {
           'Content-Type': 'application/x-www-form-urlencoded'
         }
       }).then(function(response) {
         $.notify('A request for cancellations has been sent.');
         $('#before-bill-out-cancellation-order-modal').modal('hide');
         $scope.formdata.reason_cancelled = "";
         $scope.submit = false;
       }, function(rejection) {
         if (rejection.status != 422) {
           request_error(rejection.status);
         } else if (rejection.status == 422) {
           var errors = rejection.data;
           angular.forEach(errors, function(value, key) {
             $.notify(value[0], 'error');
           });
         }
         $scope.submit = false;
       });
     } else if (type == 'after') {
       data.bill_preview.reason_cancelled = $scope.formdata.reason_cancelled;
       data.bill_preview._token = $scope._token;
       $http({
         method: 'POST',
         url: '/api/restaurant/table/order/cancel/request/after',
         data: $.param(data.bill_preview),
         headers: {
           'Content-Type': 'application/x-www-form-urlencoded'
         }
       }).then(function(response) {
         $.notify('A request for cancellations has been sent.');
         $('#after-bill-out-cancellation-order-modal').modal('hide');
         $scope.formdata.reason_cancelled = "";
         $scope.submit = false;
       }, function(rejection) {
         if (rejection.status != 422) {
           request_error(rejection.status);
         } else if (rejection.status == 422) {
           var errors = rejection.data;
           angular.forEach(errors, function(value, key) {
             $.notify(value[0], 'error');
           });
         }
         $scope.submit = false;
       });
     }
   }

   function show_cart(table_customer_id) {
     $scope.table_customer_cart = {};
     $scope.table_customer_total = "";
     $http({
       method: "GET",
       url: "/api/restaurant/table/order/cart/" + table_customer_id,
     }).then(function mySuccess(response) {
       $scope.table_customer_cart = response.data.cart;
       $scope.table_customer_total = response.data.total;
     }, function(rejection) {
       if (rejection.status != 422) {
         request_error(rejection.status);
       } else if (rejection.status == 422) {
         console.log(rejection.statusText);
       }
     });
   }
   $scope.toggle_special_instruction = function(data) {
     var toggle = (data.cart_data.show_special_instruction ? false : true);
     $scope.table_customer_cart["menu_" + data.cart_data.id].show_special_instruction = toggle;
   }
   $scope.toggle_update_quantity = function(data) {
     var toggle = (data.cart_data.show_update_quantity ? false : true);
     $scope.table_customer_cart["menu_" + data.cart_data.id].show_update_quantity = toggle;
   }
   $scope.add_special_instruction = function(data) {
     /*console.log(data.$parent.table_customer_id);*/
     $scope.formdata.special_instruction = data.cart_data.special_instruction;
     $scope.formdata.menu_id = data.cart_data.id;
     $http({
       method: 'POST',
       url: '/api/restaurant/table/order/cart/update/special_instruction/' + data.$parent.table_customer_id,
       data: $.param($scope.formdata),
       headers: {
         'Content-Type': 'application/x-www-form-urlencoded'
       }
     }).then(function(response) {}, function(rejection) {
       if (rejection.status != 422) {
         request_error(rejection.status);
       } else if (rejection.status == 422) {
         var errors = rejection.data;
       }
     });
   }
   $scope.update_quantity = function(data) {
     /*console.log(data.$parent.table_customer_id);*/
     $scope.formdata.quantity = data.cart_data.quantity;
     $scope.formdata.menu_id = data.cart_data.id;
     $scope.table_customer_cart = {};
     $scope.table_customer_total = "";
     $http({
       method: 'POST',
       url: '/api/restaurant/table/order/cart/update/quantity/' + data.$parent.table_customer_id,
       data: $.param($scope.formdata),
       headers: {
         'Content-Type': 'application/x-www-form-urlencoded'
       }
     }).then(function(response) {
       /*show_cart(data.$parent.table_customer_id);*/
       $scope.table_customer_cart = response.data.cart;
       $scope.table_customer_total = response.data.total;
     }, function(rejection) {
       if (rejection.status != 422) {
         request_error(rejection.status);
       } else if (rejection.status == 422) {
         var errors = rejection.data;
       }
     });
   }
   $scope.category_change_menu_list = function() {
     show_menu($scope.select_category);
     $http({
       method: "GET",
       url: "/api/restaurant/menu/subcategory",
       params: {
         category: $scope.select_category
       },
     }).then(function mySuccess(response) {
       $scope.subcategories = response.data;
     }, function(rejection) {
       if (rejection.status != 422) {
         request_error(rejection.status);
       } else if (rejection.status == 422) {
         console.log(rejection.statusText);
       }
     });
   }
   $scope.subcategory_change_menu_list = function() {
     show_menu($scope.select_category, $scope.select_subcategory);
   }
   $scope.menu = {};
   $scope.search_menu = _.debounce(function() {
     show_menu();
   }, 200)

   function show_menu(category, subcategory) {
     $scope.menu = {};
     category = (typeof category !== 'undefined') ? category : 'all';
     subcategory = (typeof subcategory !== 'undefined') ? subcategory : 'all';
     $http({
       method: "GET",
       url: "/api/restaurant/menu/list/orders",
       params: {
         category: category,
         subcategory: subcategory,
         search: $scope.search_string
       },
     }).then(function mySuccess(response) {
       $scope.menu = response.data.result;
     }, function(rejection) {
       if (rejection.status != 422) {
         request_error(rejection.status);
       } else if (rejection.status == 422) {
         console.log(rejection.statusText);
       }
     });
   }
   $scope.make_orders = function(data) {
     $scope.formdata.table_customer_cart = $scope.table_customer_cart;
     $scope.formdata._token = $scope._token;
     $scope.submit = true;
     $http({
       method: 'POST',
       url: '/api/restaurant/table/order/make/' + $scope.table_customer_id,
       data: $.param($scope.formdata),
       headers: {
         'Content-Type': 'application/x-www-form-urlencoded'
       }
     }).then(function(response) {
       $scope.submit = false;
       $("#add-order-modal").modal('hide');
       $('#view-order-modal').modal('show');
       show_order(response.data.id);
     }, function(rejection) {
       if (rejection.status != 422) {
         request_error(rejection.status);
       } else if (rejection.status == 422) {
         var errors = rejection.data;
         $.notify(errors.table_customer_cart[0], 'error');
       }
       $scope.submit = false;
     });
   }
   $scope.preview_order = function(data) {
     $('#view-order-modal').modal('show');
     show_order(data.order_data.id);
   }

   function show_order(id) {
     $scope.order = {};
     $scope.order_detail = {};
     $scope.customer_data = {};
     $http({
       method: "GET",
       url: "/api/restaurant/table/order/view/" + id,
     }).then(function mySuccess(response) {
       $scope.order = response.data.order;
       $scope.order_detail = response.data.order_detail;
       $scope.customer_data = response.data.customer_data;
     }, function(rejection) {
       if (rejection.status != 422) {
         request_error(rejection.status);
       } else if (rejection.status == 422) {
         console.log(rejection.statusText);
       }
     });
   }
   $scope.add_cart = _.debounce(function(data) {
     $scope.add_cart_submit = true;
     $scope.formdata.menu_id = data.menu_data.id;
     $scope.formdata.table_customer_id = $scope.table_customer_id;
     $scope.formdata._token = $scope._token;
     $scope.table_customer_cart = {};
     $scope.table_customer_total = "";
     $http({
       method: 'POST',
       url: '/api/restaurant/table/order/cart',
       data: $.param($scope.formdata),
       headers: {
         'Content-Type': 'application/x-www-form-urlencoded'
       }
     }).then(function(response) {
       $scope.table_customer_cart = response.data.cart;
       $scope.table_customer_total = response.data.total;
       $.notify(data.menu_data.name + " has been placed.");
       $scope.add_cart_submit = false;
     }, function(rejection) {
       if (rejection.status != 422) {
         request_error(rejection.status);
       } else if (rejection.status == 422) {
         var errors = rejection.data;
         $.notify(rejection.data.error, 'error');
         $scope.formdata.date_payment = errors.date_payment;
       }
       $scope.add_cart_submit = false;
     });
   }, 100);
   $scope.remove_item_cart = function(data) {
     $scope.formdata.menu_id = data.cart_data.id;
     $scope.formdata.menu_name = data.cart_data.name;
     $scope.formdata.table_customer_id = data.$parent.table_customer_id;
     $scope.formdata._token = $scope._token;
     $scope.table_customer_cart = {};
     $scope.table_customer_total = "";
     $http({
       method: 'POST',
       url: '/api/restaurant/table/order/remove',
       data: $.param($scope.formdata),
       headers: {
         'Content-Type': 'application/x-www-form-urlencoded'
       }
     }).then(function(response) {
       $scope.table_customer_cart = response.data.cart;
       $scope.table_customer_total = response.data.total;
       $.notify($scope.formdata.menu_name + " has been removed from the list.", "info");
     }, function(rejection) {
       if (rejection.status != 422) {
         request_error(rejection.status);
       } else if (rejection.status == 422) {
         var errors = rejection.data;
         $.notify(rejection.data.error, 'error');
         $scope.formdata.date_payment = errors.date_payment;
       }
     });
   };
   $scope.orders = {};
   $scope.view_orders = function(data) {
     $scope.orders = {};
     $scope.table_name = "";
     $scope.table_customer_id = "";
     $scope.customer_data = {};
     $http({
       method: "GET",
       url: "/api/restaurant/table/customer/orders/" + data.customer_data.id,
     }).then(function mySuccess(response) {
       $('#view-list-order-modal').modal('show');
       $scope.orders = response.data.result;
       $scope.table_name = data.customer_data.table_name;
       $scope.table_customer_id = data.customer_data.id;
       $scope.customer_data = data.customer_data;
     }, function(rejection) {
       if (rejection.status != 422) {
         request_error(rejection.status);
       } else if (rejection.status == 422) {
         console.log(rejection.statusText);
       }
     });
   }
   $scope.bill_out = function(data) {
     $scope.bill_preview = {};
     $scope.bill_preview.customer_data = {};
     $scope.max = 0;
     if (data.$parent.customer_data.has_billed_out) {
       $http({
         method: "GET",
         url: "/api/restaurant/table/customer/bill/preview/" + data.$parent.customer_data.id,
       }).then(function mySuccess(response) {
         $scope.bill_preview.items = response.data.result;
         angular.forEach($scope.bill_preview.items, function(value, key) {
           value.quantity_to_cancel = 0;
           value.quantity = $scope.bill_preview.items[key].quantity;
         });
         $scope.bill_preview.total = response.data.total;
         $scope.bill_preview.customer_data = response.data.customer_data;
         $scope.bill_preview.customer_data.pax = response.data.customer_data.pax;
         $scope.bill_preview.customer_data.sc_pwd = response.data.customer_data.sc_pwd;
         $scope.bill_preview.discount = response.data.discount;
         $scope.bill_preview.gross_billing = response.data.gross_billing;
         $scope.bill_preview.net_billing = response.data.net_billing;
         $scope.bill_preview.table_customer_id = data.$parent.customer_data.id;
         $scope.max = data.$parent.customer_data.pax;
         $('#bill-preview-modal').modal('show');
         if ($scope.bill_preview.customer_data.has_cancellation_request == '1') {
           $.notify('Cannot make a bill, this customer has an existing cancellation request.', 'error');
         }
       }, function(rejection) {
         if (rejection.status != 422) {
           request_error(rejection.status);
         } else if (rejection.status == 422) {
           console.log(rejection.statusText);
         }
       });
     } else {
       $scope.bill_out_submit = true;
       $scope.formdata._token = $scope._token;
       $http({
         method: 'POST',
         url: '/api/restaurant/table/customer/bill/preview/' + data.$parent.customer_data.id,
         data: $.param($scope.formdata),
         headers: {
           'Content-Type': 'application/x-www-form-urlencoded'
         }
       }).then(function(response) {
         $scope.bill_out_submit = false;
         $scope.bill_preview = {};
         $scope.bill_preview.customer_data = {};
         $scope.bill_preview.items = response.data.result;
         angular.forEach($scope.bill_preview.items, function(value, key) {
           value.quantity_to_cancel = 0;
           value.quantity = $scope.bill_preview.items[key].quantity;
         });
         $scope.bill_preview.total = response.data.total;
         $scope.bill_preview.customer_data = response.data.customer_data;
         $scope.bill_preview.discount = response.data.discount;
         $scope.bill_preview.gross_billing = response.data.gross_billing;
         $scope.bill_preview.net_billing = response.data.net_billing;
         $scope.bill_preview.table_customer_id = data.$parent.customer_data.id;
         $scope.max = data.$parent.customer_data.pax;
         $('#bill-preview-modal').modal('show');
       }, function(rejection) {
         if (rejection.status != 422) {
           request_error(rejection.status);
         } else if (rejection.status == 422) {
           var errors = rejection.data;
           $scope.formdata.date_payment = errors.date_payment;
         }
         $scope.bill_out_submit = false;
       });
     }
   }
   $scope.settlement_cancelled_orders = function(data) {
     $scope.cancelled_orders = {};
     $scope.table_customer_id = "";
     $http({
       method: "GET",
       url: "/api/restaurant/orders/cancellations/accept/" + data.$parent.customer_data.id,
     }).then(function mySuccess(response) {
       $scope.cancelled_orders = response.data.cancelled_orders;
       $scope.table_customer_id = response.data.table_customer_id;
       $("#settlement-cancelled-order-modal").modal("show");
     }, function(rejection) {
       if (rejection.status != 422) {
         request_error(rejection.status);
       } else if (rejection.status == 422) {
         console.log(rejection.statusText);
       }
     });
   }
   $scope.settle_cancelled_orders = function(data) {
     console.log(data);
     $scope.submit = true;
     var formdata = {
       items: data.cancelled_orders,
       table_customer_id: data.table_customer_id,
       _token: $scope._token
     };
     $http({
       method: 'POST',
       url: '/api/restaurant/orders/cancellations/settle/' + data.table_customer_id,
       data: $.param(formdata),
       headers: {
         'Content-Type': 'application/x-www-form-urlencoded'
       }
     }).then(function(response) {
       $.notify('Cancelled Orders has been settled.');
       $('#settlement-cancelled-order-modal').modal('hide');
       $scope.submit = false;
     }, function(rejection) {
       if (rejection.status != 422) {
         request_error(rejection.status);
       } else if (rejection.status == 422) {
         var errors = rejection.data;
         angular.forEach(errors, function(value, key) {
           $.notify(value[0], 'error');
         });
       }
       $scope.submit = false;
     });
   }
   $scope.compute_net_total = function() {
     $scope.bill_preview.discount.amount_disount = (typeof $scope.bill_preview.discount.amount_disount !== 'undefined') ? $scope.bill_preview.discount.amount_disount : 0;
     $scope.bill_preview.discount.total = ($scope.bill_preview.discount.amount_disount + ($scope.bill_preview.total * $scope.bill_preview.discount.percent_disount * 0.01));
     $scope.bill_preview.gross_billing = $scope.bill_preview.total - $scope.bill_preview.discount.total;
     $scope.bill_preview.discount.sc_pwd_discount = $scope.bill_preview.gross_billing * $scope.bill_preview.customer_data.sc_pwd / $scope.bill_preview.customer_data.pax / 1.12 * .2;
     $scope.bill_preview.discount.sc_pwd_vat_exemption = $scope.bill_preview.gross_billing * $scope.bill_preview.customer_data.sc_pwd / $scope.bill_preview.customer_data.pax / 1.12 * .12;
     $scope.bill_preview.net_billing = $scope.bill_preview.gross_billing - $scope.bill_preview.discount.sc_pwd_discount - $scope.bill_preview.discount.sc_pwd_vat_exemption;
     $scope.bill_preview.net_billing = $scope.bill_preview.gross_billing - $scope.bill_preview.discount.sc_pwd_discount - $scope.bill_preview.discount.sc_pwd_vat_exemption;
     console.log($scope.bill_preview);
   }
   $scope.make_bill = function(data) {
     $scope.bill = {};
     $scope.submit = false;
     $scope.formdata = data.bill_preview;
     $scope.formdata._token = $scope._token;
     $http({
       method: 'POST',
       url: '/api/restaurant/table/customer/bill/make/' + data.bill_preview.table_customer_id,
       data: $.param($scope.formdata),
       headers: {
         'Content-Type': 'application/x-www-form-urlencoded'
       }
     }).then(function(response) {
       $scope.submit = false;
       $scope.bill = response.data.result;
       $scope.bill.table_name = response.data.table_name;
       $("#bill-preview-modal").modal("hide");
       $("#view-bill-modal").modal("show");
     }, function(rejection) {
       if (rejection.status != 422) {
         request_error(rejection.status);
       } else if (rejection.status == 422) {
         var errors = rejection.data;
         $.notify(errors.items[0], 'error');
       }
       $scope.submit = false;
     });
   }
   $scope.bill_preview_total = function(data) {
     data.$parent.bill_preview.total = 0;
     angular.forEach(data.$parent.bill_preview.items, function(value, key) {
       data.$parent.bill_preview.total = data.$parent.bill_preview.total + (Math.abs(value.quantity_to_bill) * value.price);
     });
     $scope.compute_net_total();
   }
   $scope.view_bills = function(data) {
     $scope.bill = {};
     var id = data.$parent.$parent.customer_data.id;
     $http({
       method: "GET",
       url: "/api/restaurant/table/customer/bill/list/" + id,
     }).then(function mySuccess(response) {
       $scope.bill = response.data.result;
       $scope.bill.table_name = response.data.table_name;
       $("#view-bill-modal").modal("show");
     }, function(rejection) {
       if (rejection.status != 422) {
         request_error(rejection.status);
       } else if (rejection.status == 422) {
         console.log(rejection.statusText);
       }
     });
   }
   $('#settlement').dropdown();
   $scope.total_payment = 0;
   $scope.add_payment = function(data) {
     console.log(data);
     $scope.formdata.settlements_amount = {
       cash: 0,
       credit: 0,
       debit: 0,
       cheque: 0,
       guest_ledger: 0,
       send_bill: 0,
       free_of_charge: 0,
       bod: 0,
       manager_meals: 0,
       sales_office: 0,
       representation: 0,
     };
     $scope.formdata.net_billing = data.bill_data.net_billing;
     $scope.bill_id = data.bill_data.id;
     $scope.check_number = data.bill_data.check_number;
     $("#payment-modal").modal("show");
     $('#settlement').dropdown('clear');
     $scope.formdata.settlement = {};
     $scope.formdata.excess = excess($scope);
     $scope.valid_payment = valid_payment($scope);
     $scope.formdata.settlements_payment = {};
     $scope.formdata.settlements_payment.cash = false;
     $scope.formdata.settlements_payment.credit = false;
     $scope.formdata.settlements_payment.debit = false;
     $scope.formdata.settlements_payment.cheque = false;
     $scope.formdata.settlements_payment.guest_ledger = false;
     $scope.formdata.settlements_payment.send_bill = false;
     $scope.formdata.settlements_payment.free_of_charge = false;
     $scope.formdata.settlements_payment.bod = false;
     $scope.formdata.settlements_payment.manager_meals = false;
     $scope.formdata.settlements_payment.sales_office = false;
     $scope.formdata.settlements_payment.representation = false;
   }
   $scope.delete_bill = function function_name(data) {
     console.log(data.bill_data);
     alertify.prompt('Check #: ' + data.$parent.bill_data.check_number, 'Reason to delete:', '', function(evt, value) {
       if (value.trim() != "") {
         var formdata = {};
         formdata.deleted_comment = value;
         formdata.bill_data = data.bill_data;
         formdata._token = $scope._token;
         $scope.submit = true;
         $http({
           method: 'POST',
           url: '/api/restaurant/table/customer/bill/delete/' + data.$parent.bill_data.id,
           data: $.param(formdata),
           headers: {
             'Content-Type': 'application/x-www-form-urlencoded'
           }
         }).then(function(response) {
           $("#view-bill-modal").modal("hide");
           $scope.formdata.deleted_comment = '';
           $.notify('Check # ' + data.$parent.bill_data.check_number + ' has been deleted');
           $scope.submit = false;
         }, function(rejection) {
           if (rejection.status != 422) {
             request_error(rejection.status);
           } else if (rejection.status == 422) {
             var errors = rejection.data;
             angular.forEach(errors, function(value, key) {
               $.notify(value[0], 'error');
             });
           }
           $scope.submit = false;
         });
       } else {
         $.notify('Reason to delete message is required.', 'error');
       }
     }, function() {});
   }
   $("#search-menu").autocomplete({
     source: "/api/restaurant/menu/search/name?type=order",
     select: function(event, ui) {
       var data = {};
       data.menu_data = {};
       data.menu_data.id = ui.item.id;
       data.menu_data.name = ui.item.value;
       $scope.add_cart(data);
       $scope.search_string = '';
     }
   });
   $scope.make_payment = function(data) {
     $scope.bill = {};
     $scope.formerrors = {};
     $scope.formdata._token = $scope._token;
     $scope.submit = true;
     $http({
       method: 'POST',
       url: '/api/restaurant/table/customer/payment/make/' + data.$parent.bill_id,
       data: $.param($scope.formdata),
       headers: {
         'Content-Type': 'application/x-www-form-urlencoded'
       }
     }).then(function(response) {
       $.notify('The Payment has been saved.');
       $scope.submit = false;
       $scope.bill = response.data.result;
       $scope.bill.table_name = response.data.table_name;
       $scope.formdata.settlement = {};
       $("#payment-modal").modal("hide");
       $scope.formerrors = {};
     }, function(rejection) {
       if (rejection.status != 422) {
         request_error(rejection.status);
       } else if (rejection.status == 422) {
         var errors = rejection.data;
         $scope.formerrors = errors;
       }
       $scope.submit = false;
     });
   }
   $scope.formdata.settlements_payment = {};
   $scope.formdata.settlements_amount = {
     cash: 0,
     credit: 0,
     debit: 0,
     cheque: 0,
     guest_ledger: 0,
     send_bill: 0,
     free_of_charge: 0
   };
   $scope.input_payment = function() {
     $scope.formdata.excess = excess($scope);
     $scope.valid_payment = valid_payment($scope);
   }
   $scope.edit_table_customer = function(data) {
     $('#edit-table-modal').modal('show');
     $scope.formdata.customer_data = data.customer_data;
     $scope.formdata.pax = data.customer_data.pax;
     $scope.formdata.sc_pwd = data.customer_data.sc_pwd;
     $scope.formdata.guest_name = data.customer_data.guest_name;
     show_table(data.customer_data.table_data);
   }
   $scope.edit_table = function(data) {
     $scope.submit = true;
     $scope.formdata._token = $scope._token;
     $http({
       method: 'PUT',
       url: '/api/restaurant/table/customer/update/' + data.formdata.customer_data.id,
       data: $.param($scope.formdata),
       headers: {
         'Content-Type': 'application/x-www-form-urlencoded'
       }
     }).then(function(response) {
       $('#edit-table-modal').modal('hide');
       $scope.submit = false;
       $.notify("The information of customer has been updated.");
     }, function(rejection) {
       if (rejection.status != 422) {
         request_error(rejection.status);
       } else if (rejection.status == 422) {
         var errors = rejection.data;
         $scope.formerrors = errors;
       }
       $scope.submit = false;
     });
   }

   function show_server() {
     $scope.formdata.server_id = null;
     $scope.server = {};
     $http({
       method: "GET",
       params: {
         restaurant_id: restaurant_id
       },
       url: "/api/restaurant/server/list",
     }).then(function mySuccess(response) {
       $scope.server = response.data.result;
     }, function(rejection) {
       if (rejection.status != 422) {
         request_error(rejection.status);
       } else if (rejection.status == 422) {
         console.log(rejection.statusText);
       }
     });
   }

   function excess($scope) {
     var excess = ($scope.formdata.settlements_amount.cash + $scope.formdata.settlements_amount.credit + $scope.formdata.settlements_amount.debit + $scope.formdata.settlements_amount.cheque + $scope.formdata.settlements_amount.guest_ledger + $scope.formdata.settlements_amount.send_bill + $scope.formdata.settlements_amount.free_of_charge + $scope.formdata.settlements_amount.bod + $scope.formdata.settlements_amount.manager_meals + $scope.formdata.settlements_amount.sales_office + $scope.formdata.settlements_amount.representation) - $scope.formdata.net_billing;
     return (excess >= 0 ? excess : 0);
   }

   function valid_payment(argument) {
     $scope.total_payment = ($scope.formdata.settlements_amount.cash + $scope.formdata.settlements_amount.credit + $scope.formdata.settlements_amount.debit + $scope.formdata.settlements_amount.cheque + $scope.formdata.settlements_amount.guest_ledger + $scope.formdata.settlements_amount.send_bill + $scope.formdata.settlements_amount.free_of_charge + $scope.formdata.settlements_amount.bod + $scope.formdata.settlements_amount.manager_meals + $scope.formdata.settlements_amount.sales_office + $scope.formdata.settlements_amount.representation);
     return ($scope.total_payment >= $scope.formdata.net_billing ? true : false);
   }
   $scope.settlements_payment = function(data) {
     $scope.formdata.settlements_payment = {};
     if ($scope.formdata.settlement.includes('cash')) {
       $scope.formdata.settlements_payment.cash = true;
     } else {
       $scope.formdata.settlements_payment.cash = false;
       $scope.formdata.settlements_amount.cash = 0;
       $scope.formdata.excess = excess($scope);
       $scope.valid_payment = valid_payment($scope);
     }
     if ($scope.formdata.settlement.includes('credit')) {
       $scope.formdata.settlements_payment.credit = true;
     } else {
       $scope.formdata.settlements_payment.credit = false;
       $scope.formdata.settlements_amount.credit = 0;
       $scope.formdata.excess = excess($scope);
       $scope.valid_payment = valid_payment($scope);
     }
     if ($scope.formdata.settlement.includes('debit')) {
       $scope.formdata.settlements_payment.debit = true;
     } else {
       $scope.formdata.settlements_payment.debit = false;
       $scope.formdata.settlements_amount.debit = 0;
       $scope.formdata.excess = excess($scope);
       $scope.valid_payment = valid_payment($scope);
     }
     if ($scope.formdata.settlement.includes('cheque')) {
       $scope.formdata.settlements_payment.cheque = true;
     } else {
       $scope.formdata.settlements_payment.cheque = false;
       $scope.formdata.settlements_amount.cheque = 0;
       $scope.formdata.excess = excess($scope);
       $scope.valid_payment = valid_payment($scope);
     }
     if ($scope.formdata.settlement.includes('guest_ledger')) {
       $scope.formdata.settlements_payment.guest_ledger = true;
     } else {
       $scope.formdata.settlements_payment.guest_ledger = false;
       $scope.formdata.settlements_amount.guest_ledger = 0;
       $scope.formdata.excess = excess($scope);
       $scope.valid_payment = valid_payment($scope);
     }
     if ($scope.formdata.settlement.includes('send_bill')) {
       $scope.formdata.settlements_payment.send_bill = true;
     } else {
       $scope.formdata.settlements_payment.send_bill = false;
       $scope.formdata.settlements_amount.send_bill = 0;
       $scope.formdata.excess = excess($scope);
       $scope.valid_payment = valid_payment($scope);
     }
     if ($scope.formdata.settlement.includes('free_of_charge')) {
       $scope.formdata.settlements_payment.free_of_charge = true;
     } else {
       $scope.formdata.settlements_payment.free_of_charge = false;
       $scope.formdata.settlements_amount.free_of_charge = 0;
       $scope.formdata.excess = excess($scope);
       $scope.valid_payment = valid_payment($scope);
     }
     if ($scope.formdata.settlement.includes('bod')) {
       $scope.formdata.settlements_payment.bod = true;
     } else {
       $scope.formdata.settlements_payment.bod = false;
       $scope.formdata.settlements_amount.bod = 0;
       $scope.formdata.excess = excess($scope);
       $scope.valid_payment = valid_payment($scope);
     }
     if ($scope.formdata.settlement.includes('manager_meals')) {
       $scope.formdata.settlements_payment.manager_meals = true;
     } else {
       $scope.formdata.settlements_payment.manager_meals = false;
       $scope.formdata.settlements_amount.manager_meals = 0;
       $scope.formdata.excess = excess($scope);
       $scope.valid_payment = valid_payment($scope);
     }
     if ($scope.formdata.settlement.includes('sales_office')) {
       $scope.formdata.settlements_payment.sales_office = true;
     } else {
       $scope.formdata.settlements_payment.sales_office = false;
       $scope.formdata.settlements_amount.sales_office = 0;
       $scope.formdata.excess = excess($scope);
       $scope.valid_payment = valid_payment($scope);
     }
     if ($scope.formdata.settlement.includes('representation')) {
       $scope.formdata.settlements_payment.representation = true;
     } else {
       $scope.formdata.settlements_payment.representation = false;
       $scope.formdata.settlements_amount.representation = 0;
       $scope.formdata.excess = excess($scope);
       $scope.valid_payment = valid_payment($scope);
     }
   }
 });
 app.directive('focusMe', ['$timeout', '$parse', function($timeout, $parse) {
   return {
     link: function(scope, element, attrs) {
       var model = $parse(attrs.focusMe);
       scope.$watch(model, function(value) {
         if (value === true) {
           $timeout(function() {
             element[0].focus();
           });
         }
       });
       element.bind('blur', function() {
         scope.$apply(model.assign(scope, false));
       });
     }
   };
 }]);
 angular.bootstrap(document, ['main']);