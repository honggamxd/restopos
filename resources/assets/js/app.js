
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('angular');
require('semantic-ui/dist/components/dropdown');
require('semantic-ui/dist/components/transition');
require('semantic-ui/dist/components/sidebar');
require('semantic-ui/dist/components/popup');
require('semantic-ui/dist/components/checkbox');

require('./notify');
require('./tablesort');
require('./shortcut');
require('jquery-ui-dist/jquery-ui');
require('./jquery-ui-timepicker-addon');

window.alertify = require('alertifyjs/build/alertify');
window.Angular = require('angular');
window.app = Angular.module('main', [require('angular-sanitize')]);
window.moment = require('moment/moment');;


$(document).ready(function(){
  $('.ui.dropdown').dropdown({
    forceSelection: false
  });
  $("#menu").click(function(e) {
    $('.ui.sidebar').sidebar('show');
    $(".navbar").css("visibility","hidden");
  });
    $('.ui.sidebar').sidebar({
      onHide: function() {
        $(".navbar").css("visibility","visible");
        // $(".navbar").animate({visibility: "visible"});
      }
    });
  $("#time-display").html(moment().format("h:mm:ss A"));
  setInterval(function(){
    $("#time-display").html(moment().format("h:mm:ss A"));
  }, 100);
});

window.request_error = function(error_code) {
    if(error_code==401){
        alertify.alert('Error','Session has expired. Please login again.');
        setTimeout(function(){
            location.reload();
        }, 3000);
    }else if(error_code==401){
        $.notify("Server Error Try Again.",'warn');
    }else if(error_code>=500){
        $.notify("Server Error Try Again.",'error');
    }
}

app.filter('isEmpty', function () {
    var bar;
    return function (obj) {
        for (bar in obj) {
            if (obj.hasOwnProperty(bar)) {
                return false;
            }
        }
        return true;
    };
});

app.directive('focusMe', ['$timeout', '$parse', function ($timeout, $parse) {
    return {
        link: function (scope, element, attrs) {
            var model = $parse(attrs.focusMe);
            scope.$watch(model, function (value) {
                if (value === true) {
                    $timeout(function () {
                        element[0].focus();
                    });
                }
            });
            element.bind('blur', function () {
                scope.$apply(model.assign(scope, false));
            });
        }
    };
}]);

app.config(['$httpProvider', function ($httpProvider) {

    let token = document.head.querySelector('meta[name="csrf-token"]');
    if (token) {
        $httpProvider.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
    } else {
        console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
    }
    $httpProvider.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded';
    $httpProvider.defaults.headers.put['Content-Type'] = 'application/x-www-form-urlencoded';
    $httpProvider.defaults.headers.patch['Content-Type'] = 'application/x-www-form-urlencoded';
}]);


window.isEmpty = function(obj) {

    // null and undefined are "empty"
    if (obj == null) return true;

    // Assume if it has a length property with a non-zero value
    // that that property is correct.
    if (obj.length > 0)    return false;
    if (obj.length === 0)  return true;

    // If it isn't an object at this point
    // it is empty, but it can't be anything *but* empty
    // Is it empty?  Depends on your application.
    if (typeof obj !== "object") return true;

    // Otherwise, does it have any properties of its own?
    // Note that this doesn't handle
    // toString and valueOf enumeration bugs in IE < 9
    for (var key in obj) {
        if (hasOwnProperty.call(obj, key)) return false;
    }

    return true;
}

app.filter('chkNull',function(){
    return function(input){
        if(!(angular.equals(input,null)))
            return input;
        else
            return 0;
    };
});


$.notify.defaults(

  {
    // whether to hide the notification on click
    clickToHide: true,
    // whether to auto-hide the notification
    autoHide: true,
    // if autoHide, hide after milliseconds
    autoHideDelay: 5000,
    // show the arrow pointing at the element
    arrowShow: true,
    // arrow size in pixels
    arrowSize: 5,
    // position defines the notification position though uses the defaults below
    position: 'bottom left',
    // default positions
    elementPosition: 'top right',
    globalPosition: 'top right',
    // default style
    style: 'bootstrap',
    // default class (string or [string])
    className: 'success',
    // show animation
    showAnimation: 'slideDown',
    // show animation duration
    showDuration: 400,
    // hide animation
    hideAnimation: 'slideUp',
    // hide animation duration
    hideDuration: 200,
    // padding between element and notification
    gap: 2
  }
);