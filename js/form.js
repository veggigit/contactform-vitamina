// $(alert('hola'))
(function ($) {
  $(document).on('click', '#submit', function (e) {
    e.preventDefault();

    // btn loading -> see  ajax .always bellow
    $('#submit span').text('Espere ');
    $('#submit .zmdi').removeAttr('style');

    // key values form input
    var nombre = $('#nombre');
    var email = $('#email');
    var body = $('#body');
    var ajax = $('#ajax');

    $.ajax({
      url: MYJS_var.ajaxurl,
      type: 'POST',
      dataType: 'json',
      data:
      {
        action: 'launch',
        key_nombre: nombre.val(),
        key_email: email.val(),
        key_body: body.val(),
        key_ajax: ajax.val()
      }
    })

      .done(function (data, textStatus, jqXHR) {

        // clean
        $('.form-control').removeClass('is-invalid');
        $('.msg-feed').text('');
        nombre.val('');
        email.val('');
        body.val('');

        // SAVE SUCCESS AND MAIL SEND SUCCESS
        if (jqXHR.responseJSON.success_save) {
          var ss = jqXHR.responseJSON.success_save;
          $('#save_status').html("<div class='alert alert-success' role='alert'><h4>Yeah!</h4><span></span></div>");
          $('#save_status .alert span').append(ss);
          
        }

      })

      .fail(function (jqXHR, textStatus, errorThrown) {

        // clean
        $('.msg-feed').text('');
        $('#save_status').text('');
        $('.form-control').removeClass('is-invalid');
        $('.msg-feed').removeClass('invalid-feedback');

        if (jqXHR.responseJSON.nombre_error) {
          var en = jqXHR.responseJSON.nombre_error;
          $('#show_nombre_feed').append(en).addClass('invalid-feedback');
          $('#nombre').addClass('is-invalid');

        } else if (jqXHR.responseJSON.email_error) {
          var ee = jqXHR.responseJSON.email_error;
          $('#show_email_feed').append(ee).addClass('invalid-feedback');
          $('#email').addClass('is-invalid');

        } else if (jqXHR.responseJSON.body_error) {
          var be = jqXHR.responseJSON.body_error;
          $('#show_body_feed').append(be).addClass('invalid-feedback');
          $('#body').addClass('is-invalid');

          // SAVE FAIL. SERVER DOWN, DATABASE PROBLEM CONNECTION OR MAIL()PHP FAIL
        } else if (jqXHR.responseJSON.fail_save) {
          var fs = jqXHR.responseJSON.fail_save;
          $('#save_status').html("<div class='alert alert-warning' role='alert'><h4>Ouups!</h4><span></span></div>");
          $('#save_status .alert span').append(fs);

        } else {
          return 1;
        }

      })

      .always(function(jqXHR){
        // btn loading
        $('#submit .zmdi').attr("style", "display:none;");
        $('#submit span').text('Enviar ');

      })
  



  });

})(jQuery);