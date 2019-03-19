// $(alert('hola'))
(function ($) {
  $(document).on('click', '#submit', function (e) {
    e.preventDefault();

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
        $('.error').text('');
        nombre.val('');
        email.val('');
        body.val('');

        if (jqXHR.responseJSON.success_save) {

          var ss = jqXHR.responseJSON.success_save;
          $('#save_status').append(ss);

        }

      })

      .fail(function (jqXHR, textStatus, errorThrown) {

        // clean
        $('.error').text('');
        $('#save_status').text('');

        if (jqXHR.responseJSON.nombre_error) {
          var en = jqXHR.responseJSON.nombre_error;
          $('#show_nombre_error').append(en);

        } else if (jqXHR.responseJSON.email_error) {
          var ee = jqXHR.responseJSON.email_error;
          $('#show_email_error').append(ee);

        } else if (jqXHR.responseJSON.body_error) {
          var be = jqXHR.responseJSON.body_error;
          $('#show_body_error').append(be);

        } else if (jqXHR.responseJSON.fail_save) {
          var fs = jqXHR.responseJSON.fail_save;
          $('#save_status').append(fs);

        } else {
          return 1;
        }

      })



  });

})(jQuery);