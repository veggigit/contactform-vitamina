// $(alert('hola'))
(function ($) {
  $(document).on('click', '#submit', function (e) {
    e.preventDefault();

    nombre = $('#nombre').val();
    email = $('#email').val();
    body = $('#body').val();

    $.ajax({
      url: MYJS_var.ajaxurl,
      type: 'POST',
      dataType: 'json',
      data:
      {
        action: 'launch',
        key_nombre: nombre,
        key_email: email,
        key_body: body,
      }
    })

      .done(function (data, textStatus, jqXHR) {

        alert(jqXHR.responseJSON.data)

      })

      .fail(function (jqXHR, textStatus, errorThrown) {

        alert(jqXHR.responseJSON.data)

      })



  });

})(jQuery);
