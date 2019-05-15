<?php

/*
Plugin Name: contactform-vitamina
Plugin URI: https://github.com/veggigit/contactform-vitamina
Description: contact form para sitio vitaminaproducciones.cl
Version: 1.0
Author: @veggi
Author URI: https://github.com/veggigit
License: GPLv2
*/

/*
* Contact Form Handler
*/

// ** LOAD JQUERY ** //
function load_jquery()
{
    wp_enqueue_script('JQUERY_js', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js', array(), '3.3.1', true);
}
add_action('wp_enqueue_scripts', 'load_jquery');


// ** LOAD MY SCRIPT ** //
function ajax_script()
{
    wp_enqueue_script('MYJS', plugin_dir_url(__FILE__) . 'js/form.js', array('JQUERY_js'), '1.0.0', true);
    wp_localize_script('MYJS', 'MYJS_var', ['ajaxurl' => admin_url('admin-ajax.php')]);
}
add_action('wp_enqueue_scripts', 'ajax_script');


// ** FORM SCRIPT ** //
function use_fomrsript()
{

    if (isset($_POST['key_ajax'])) :

        $nombre = sanitize_text_field($_POST['key_nombre']);
        $email = sanitize_email($_POST['key_email']);
        $body = sanitize_textarea_field($_POST['key_body']);

        // data for email and db
        $array = array($nombre, $email, $body);

        if ($nombre == '') :

            http_response_code(400);
            echo json_encode(array('success' => false, 'nombre_error' => 'Favor ingresa tu nombre'));

        elseif (!is_email($email)) :

            http_response_code(400);
            echo json_encode(array('success' => false, 'email_error' => 'Email no válido'));

        elseif ($body == '') :

            http_response_code(400);
            echo json_encode(array('success' => false, 'body_error' => 'Favor ingresar mensaje'));

        else :
            // conectamos a la base de datos
            do_action('after_validate', $array);

        endif;

    endif;

    die();

    // do_action('after_validate', $array);
}
add_action('wp_ajax_nopriv_launch', 'use_fomrsript');
add_action('wp_ajax_launch', 'use_fomrsript');


// ** AFTER VALIDATE HOOK ** //
function save_contact($array)
{
    global $wpdb;

    // data for save on data base. this incommming from post. see use_form_script function (last line)
    $datatodb = array(
        'nombre' => $array[0],
        'email' => $array[1],
        'mensaje' => $array[2],
        'fecha' => current_time('mysql')
    );

    // email data
    $to = "estebancajina@gmail.com";
    $subject = "Nuevo contacto desde web";
    $message = "<h3>Tienes un nuevo contacto desde vitaminaproducciones.cl</h3></br>De: $array[1]</br>Mensaje: $array[2]";
    $headers = array('Content-Type: text/html; charset=UTF-8');

    if ( $wpdb->insert('contactos', $datatodb) && mail($to, $subject, $message, $headers) ) :

        http_response_code(200);
        echo json_encode(array('success' => true, 'success_save' => 'Tus datos fueron almacendados, pronto un ejecutivo se contactará con UD.'));

    else :

        http_response_code(400);
        echo json_encode(array('success' => false, 'fail_save' => 'Tuvimos un problema con nuestro servidor, porfavor intenta más tarde :C'));

    endif;
}
add_action('after_validate', 'save_contact');
