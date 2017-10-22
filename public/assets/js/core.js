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
