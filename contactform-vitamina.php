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
        $ajax = $_POST['key_ajax'];

        if ($nombre == '') :

            http_response_code(400);
            echo json_encode(array('success' => false, 'nombre_error' => 'favor ingresa tu nombre'));



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
