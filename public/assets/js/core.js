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