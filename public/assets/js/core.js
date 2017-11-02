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
  }, 500);
});

function error_505(error_message) {
  alertify.error(error_message);
}
$(document).on('keydown', function(e) {
    if(e.ctrlKey && (e.key == "p" || e.charCode == 16 || e.charCode == 112 || e.keyCode == 80) ){
        e.cancelBubble = true;
        e.preventDefault();

        e.stopImmediatePropagation();
    }  
});
function isEmpty(obj) {

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