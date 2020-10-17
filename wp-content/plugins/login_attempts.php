<?php
/**
 * Plugin Name: Login Attempts
 * Description: Erlaubt nur gewisse Anzahl an Logins
 * Author: Tom Rose
 **/


add_filter('authenticate', 'TR_authenticate', 30, 3);    
add_action('wp_login_failed', 'TR_login_failed', 10, 1); 

function TR_authenticate( $user, $username, $password )
{
    if ($data = get_transient('failed_login')) {

        if ($data['tries'] >= 3 ) {
            $timeout = get_option('_transient_timeout_' . 'failed_login');
            $timeleft = $timeout - time();

            return new WP_Error('too_many_tries',  
                sprintf(( '<strong>ERROR</strong>: Too many login attempts. Please try again in %d seconds.' ), $timeleft)
            );
        }
    }

    return $user;
}

function TR_login_failed( $username ) 
{
    if ($data = get_transient('failed_login') ) {
        $data['tries']++;
    } else {
        $data = ['tries' => 1] ;
    }

    set_transient('failed_login', $data, 300);
}
