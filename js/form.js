// $(alert('hola'))
(function($){
  $(document).on('click','#submit', function(e){
    e.preventDefault();

    nombre = $('#nombre').val();

    $.ajax({
      url: MYJS_var.ajaxurl,
      type: 'post',
      data: {
        nombre: nombre,
        action: 'launch_validate',
      },

      success: function(resultado){

        alert(resultado);

      }


    });

  });

})(jQuery);