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

// load jquey
add_action('wp_enqueue_scripts', 'load_jquery');

function load_jquery()
{
    wp_enqueue_script('JQUERY_js', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js', array(), '3.3.1', true);
}

// mi form script
add_action('wp_enqueue_scripts', 'my_form_script');

function my_form_script()
{
    wp_enqueue_script('MYJS', plugin_dir_url(__FILE__) . 'js/form.js', array('JQUERY_js'), '1.0.0', true);

    wp_localize_script('MYJS', 'MYJS_var', ['ajaxurl' => admin_url('admin-ajax.php')]);
}

// function validate_send
add_action('wp_ajax_nopriv_launch', 'validate_send');
add_action('wp_ajax_launch', 'validate_send');

function validate_send()
{
    // declaramos
    $get_nombre = $_POST['key_nombre'];
    $get_email = $_POST['key_email'];
    $get_body = $_POST['key_body'];

    // VALIDAMOS 
    if (!empty($get_nombre) && !empty($get_body) && is_email($get_email)) :

        // database
        global $wpdb;
        $table = 'contactos';


        // saneamos
        $nombre = sanitize_text_field($get_nombre);
        $email = sanitize_email($get_email);
        $body = sanitize_textarea_field($get_body);

        // datos a insertar
        $data = array(
            'nombre' => $nombre,
            'email' => $email,
            'mensaje' => $body,
            'fecha' => current_time('mysql')
        );

        // insertamos
        $wpdb->insert($table, $data);

        wp_send_json_success('Listo! en breves momentos un ejecutivo se contactrá con UD.', 200);


    elseif (!is_email($get_email)) :

        wp_send_json_error('Favor ingrese un email', 400);

    else :

        wp_send_json_error('Favor completel', 400);

    endif;

    die();
}
