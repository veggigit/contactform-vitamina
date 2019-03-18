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
    // sesion
    session_start();

    // Validate Requests
    if (!empty($_POST['key_nombre']) && !empty($_POST['key_body']) && is_email($_POST['key_email']) ) :

        if(isset($_SESSION['form_session'])) :
            
            wp_send_json_error ($_SESSION['form_session'], 400);

        else:

        // saneamos
        $nombre = sanitize_text_field($_POST['key_nombre']);
        $email = sanitize_email($_POST['key_email']);
        $body = sanitize_textarea_field($_POST['key_body']);

        $array = array($nombre, $email, $body);

        // accion after succes validate
        do_action('after_validate', $array);

        //creamos sesion para impedir doble submit
        $_SESSION['form_session'] = 'Ya recibimos su mensaje! Si desea enviar otro mensaje favor intente más tarde';

        wp_send_json_success ('ok', 200);

        endif;

    elseif (!is_email($_POST['key_email'])) :

        wp_send_json_error('Favor ingrese un email', 400);

    else :

        wp_send_json_error('Favor completel', 400);

    endif;

    die();
}
add_action('wp_ajax_nopriv_launch', 'use_fomrsript');
add_action('wp_ajax_launch', 'use_fomrsript');


// ** AFTER VALIDATE HOOK ** //
function save_contact($array) 
{
    global $wpdb;

    $data = ([
        'nombre' => $array[0],
        'email' => $array[1],
        'mensaje' => $array[2],
        'fecha' => current_time('mysql')
    ]);

    $wpdb->insert('contactos', $data);

    wp_mail('estebancajina@gmail.com', 'bla', 'bla');

}
add_action('after_validate', 'save_contact');