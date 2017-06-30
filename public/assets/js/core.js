$(document).ready(function(){
  $('.ui.dropdown').dropdown({
    forceSelection: false
  });
  $(document).on("click",".btn-add-items",function(e) {
    $('#add-items-modal').modal('show');
  });
  $(document).on("click",".btn-add-customers",function(e) {
    $('#add-customers-modal').modal('show');
  });
  $(document).on("click",".btn-add-salesman",function(e) {
    $('#add-salesman-modal').modal('show');
  });
  $(document).on("click",".btn-add-suppliers",function(e) {
    $('#add-suppliers-modal').modal('show');
  });
  $(document).on("click",".btn-add-users",function(e) {
    $('#add-users-modal').modal('show');
  });
  $(document).on("click","#app-settings",function(e) {
    $('#settings-modal').modal('show');
  });
  $(document).on("submit","#settings-form",function(e) {
    e.preventDefault();
    $.ajax({
      url: $("#settings-form").attr("action"),
      data: new FormData(this),
      processData: false,
      contentType: false,
      method: "POST",
      cache: false,
      beforeSend: function() {
        $('button[form="settings-form"]').prop("disabled",true);
      },
      success: function(data) {
        alertify.success("Application Settings are updated.");
        $("#settings-modal").modal("hide");
      },
      error: function(data) {
        console.log(data);
        if(data.status = 422){
          var errors = data.responseJSON;
          $("#app_company_name-help-block").html(errors.company_name);
          $("#app_address-help-block").html(errors.address);
          $("#app_contact_number-help-block").html(errors.contact_number);
          $("#app_logo-help-block").html(errors.logo);
        }
      },
      complete: function() {
        $('button[form="settings-form"]').prop("disabled",false);
      }
    });
  })
});