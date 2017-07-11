$(document).ready(function(){
  $('.ui.dropdown').dropdown({
    forceSelection: false
  });
  $("#menu").click(function(e) {
    $('.ui.sidebar').sidebar('show');
  });
  $("#time-display").html(moment().format("h:mm:ss A"));
  setInterval(function(){
    $("#time-display").html(moment().format("h:mm:ss A"));
  }, 500);
  // $('.ui.sidebar').sidebar({
  //     transition: 'overlay'
  // });
});