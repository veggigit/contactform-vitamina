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
add_action( 'wp_enqueue_scripts', 'load_jquery' );

function load_jquery()
{
    wp_enqueue_script( 'JQUERY_js', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js', array(), '3.3.1', true );
}

// mi form script
add_action( 'wp_enqueue_scripts', 'my_form_script' );

function my_form_script() 
{
    wp_enqueue_script( 'MYJS', plugin_dir_url( __FILE__ ).'js/form.js', array( 'JQUERY_js'), '1.0.0', true );

    wp_localize_script('MYJS', 'MYJS_var', ['ajaxurl' => admin_url('admin-ajax.php')]);
}

// function validate
add_action('wp_ajax_nopriv_launch_validate', 'validate');
add_action('wp_ajax_launch_validate', 'validate');

function validate(){

    $get_nombre = ($_POST['nombre']);
    
    if (empty($get_nombre) || !isset($get_nombre)) : 

        echo "favor complete los campos";

    else:
        
        echo "$get_nombre";

    endif;

    wp_die();
}

