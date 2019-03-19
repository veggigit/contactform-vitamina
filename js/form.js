// $(alert('hola'))
(function ($) {
  $(document).on('click', '#submit', function (e) {
    e.preventDefault();

    // key values form input
    nombre = $('#nombre');
    email = $('#email');
    body = $('#body');
    ajax = $('#ajax');

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

        alert(jqXHR.responseJSON.data);
        // clean
        nombre.val('');
        email.val('');
        body.val('');

      })

      .fail(function (jqXHR, textStatus, errorThrown) {

        if (jqXHR.responseJSON.nombre_error) {
          alert(jqXHR.responseJSON.nombre_error);
        }

      })



  });

})(jQuery);