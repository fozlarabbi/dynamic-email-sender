<?php
/*
 * Plugin Name:       Dynamic Sender
 * Description:        Easily change the sender email and name for all outgoing emails from your website. This plugin allows you to fully customize the sender information, making it easy to personalize your communications with customers and improve your brand recognition. 
 * Version:           1.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Md Fozla Rabbi
 * Author URI:        https://fozlarabbi.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 */

add_action( 'admin_menu', 'dynamic_sender_menu' );

function dynamic_sender_menu() {
    add_options_page( 'Dynamic Sender', 'Dynamic Sender', 'manage_options', 'dynamic_sender', 'dynamic_sender_options' );
}

function dynamic_sender_options() {
    if ( !current_user_can( 'manage_options' ) )  {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }
    // Form fields for Sender Name and Sender Email
    $sender_name = get_option( 'dynamic_sender_name' );
    $sender_email = get_option( 'dynamic_sender_email' );

    echo '<div class="wrap">';
    echo '<h1>Dynamic Sender</h1>';
    echo '<form method="post" action="options.php">';
    settings_fields( 'dynamic_sender_options' );
    do_settings_sections( 'dynamic_sender_options' );
    wp_nonce_field( 'dynamic_sender_options', 'dynamic_sender_options_nonce' );
    echo '<table class="form-table">';
    echo '<tr valign="top">';
    echo '<th scope="row">Sender Name</th>';
    echo '<td><input type="text" name="dynamic_sender_name" value="' . esc_attr( sanitize_text_field( $sender_name ) ) . '" /></td>';
    echo '</tr>';
    echo '<tr valign="top">';
    echo '<th scope="row">Sender Email</th>';
    echo '<td><input type="text" name="dynamic_sender_email" value="' . esc_attr( sanitize_text_field( $sender_email ) ) . '" /></td>';
    echo '</tr>';
    echo '</table>';
    submit_button();
    echo '</form>';
    echo '</div>';
}

// Register settings
add_action( 'admin_init', 'dynamic_sender_settings' );

function dynamic_sender_settings() {
    register_setting( 'dynamic_sender_options', 'dynamic_sender_name' , 'sanitize_text_field' );
    register_setting( 'dynamic_sender_options', 'dynamic_sender_email', 'sanitize_text_field' );
}

// Apply filters for the sender name and email
add_filter( 'wp_mail_from_name', 'dynamic_sender_name' );
function dynamic_sender_name( $original_email_from ) {
    return get_option( 'dynamic_sender_name', $original_email_from );
}

add_filter( 'wp_mail_from', 'dynamic_sender_email' );
function dynamic_sender_email( $original_email_address ) {
    return get_option( 'dynamic_sender_email', $original_email_address );
}

