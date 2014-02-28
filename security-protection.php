<?php
/*
Plugin Name: Security-protection
Plugin URI: http://wordpress.org/plugins/security-protection/
Description: Protection from login, register and reset-password brute-force attacks.
Version: 1.1
Author: webvitaly
Author URI: http://web-profile.com.ua/wordpress/plugins/
License: GPLv3
*/


$securityprotection_send_brute_force_log_to_admin = false; // if true, than info about blocked brute-force attacks will be sent to admin email

$securityprotection_login_cookie_check = true; // if true, than cookie will be set on login screen


include( 'security-protection-functions.php' );


if ( ! function_exists( 'securityprotection_hooks' ) ) :

	function securityprotection_hooks() {
		add_action( 'init', 'securityprotection_set_login_cookie' );
		add_action( 'login_init', 'securityprotection_login' );
	}

	securityprotection_hooks();


	function securityprotection_set_login_cookie() {
		global $securityprotection_login_cookie_check;
		if( $securityprotection_login_cookie_check ) {
			if( strtoupper( $_SERVER['REQUEST_METHOD']) == 'GET' and !isset( $_COOKIE['wordpress_secprot_cookie'] ) ) {
				setcookie( 'wordpress_secprot_cookie', '1', time()+60*60*24*30, COOKIEPATH, COOKIE_DOMAIN ); // set cookie for a month
				$_COOKIE['wordpress_secprot_cookie'] = '1';
			}
		}
	}


	function securityprotection_login() {
		global $securityprotection_send_brute_force_log_to_admin, $securityprotection_login_cookie_check;

		if( $securityprotection_login_cookie_check ) {
			if( strtoupper( $_SERVER['REQUEST_METHOD'] ) == 'POST' and !isset( $_COOKIE['wordpress_secprot_cookie'] ) ) {

				if ( $securityprotection_send_brute_force_log_to_admin ) { // if sending email to admin is enabled
					$securityprotection_admin_email = get_option('admin_email');  // admin email

					if ( !empty( $_SERVER['HTTP_CLIENT_IP'] ) ) { //check ip from share internet
						$ip = $_SERVER['HTTP_CLIENT_IP'];
					} elseif ( !empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) { // to check ip is pass from proxy, also could be used ['HTTP_X_REAL_IP ']
						$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
					} else {
						$ip = $_SERVER['REMOTE_ADDR'];
					}

					$securityprotection_message_brute_force_info = '';
					$securityprotection_message_brute_force_info .= 'IP : ' . $ip . "\r\n";

					$securityprotection_message_brute_force_info .= 'HTTP_USER_AGENT : ' . $_SERVER['HTTP_USER_AGENT'] . "\r\n";
					$securityprotection_message_brute_force_info .= 'REQUEST_URI : ' . $_SERVER['REQUEST_URI'] . "\r\n";
					$securityprotection_message_brute_force_info .= 'HTTP_REFERER : ' . $_SERVER['HTTP_REFERER'] . "\r\n\r\n";
					//$securityprotection_message_brute_force_info .= 'SERVER_PROTOCOL : ' . $_SERVER['SERVER_PROTOCOL'] . "\r\n";
					//$securityprotection_message_brute_force_info .= 'REDIRECT_STATUS : ' . $_SERVER['REDIRECT_STATUS'] . "\r\n\r\n";


					$securityprotection_message_brute_force_info .= 'POST vars:'."\r\n"; // lets see what POST vars brute-forcers try to submit
					foreach ( $_POST as $key => $value ) {
						$securityprotection_message_brute_force_info .= '$_POST['.$key. '] = '.$value."\r\n"; // .chr(13).chr(10)
					}
					$securityprotection_message_brute_force_info .= "\r\n\r\n";

					/*$securityprotection_message_brute_force_info .= 'SERVER vars:'."\r\n"; // lets see what SERVER vars brute-forcers try to submit
					foreach ( $_SERVER as $key => $value ) {
						$securityprotection_message_brute_force_info .= '$_SERVER['.$key. '] = '.$value."\r\n"; // .chr(13).chr(10)
					}
					$securityprotection_message_brute_force_info .= "\r\n\r\n";*/

					/*$securityprotection_message_brute_force_info .= 'ENV vars:'."\r\n"; // lets see what ENV vars brute-forcers try to submit
					foreach ( $_ENV as $key => $value ) {
						$securityprotection_message_brute_force_info .= '$_ENV['.$key. '] = '.$value."\r\n"; // .chr(13).chr(10)
					}
					$securityprotection_message_brute_force_info .= "\r\n\r\n";*/

					$securityprotection_message_brute_force_info .= 'COOKIE vars:'."\r\n"; // lets see what COOKIE vars brute-forcers try to submit
					foreach ( $_COOKIE as $key => $value ) {
						$securityprotection_message_brute_force_info .= '$_COOKIE['.$key. '] = '.$value."\r\n"; // .chr(13).chr(10)
					}
					$securityprotection_message_brute_force_info .= "\r\n\r\n";

					$securityprotection_message_append = '-----------------------------'."\r\n";
					$securityprotection_message_append .= 'This is brute-force log blocked by Security-protection plugin - wordpress.org/plugins/security-protection/' . "\r\n";
					$securityprotection_message_append .= 'You may edit "security-protection.php" file and disable this notification.' . "\r\n";
					$securityprotection_message_append .= 'You should find "$securityprotection_send_brute_force_log_to_admin" and make it equal to "false".' . "\r\n";


					$securityprotection_message = '';

					$securityprotection_message .= $securityprotection_message_brute_force_info; // post, cookie and other data

					$securityprotection_message .= $securityprotection_message_append;


					$securityprotection_subject = 'Login brute-force on site ['.get_bloginfo( 'name' ).']'; // email subject
					@wp_mail( $securityprotection_admin_email, $securityprotection_subject, $securityprotection_message ); // send log info to admin email

				}

				// many brute-force attacks are waiting for redirect or WordPress login cookies
				// if we will fake redirect and login cookies than many brute-forcers will stop their attacks

				securityprotection_set_fake_login_cookies(); // set fake login cookies

				securityprotection_fake_redirect(); // fake admin dashboard redirect

			}

		}

	}

endif; // end of securityprotection_hooks()


if ( ! function_exists( 'securityprotection_plugin_meta' ) ) :
	function securityprotection_plugin_meta( $links, $file ) { // add 'Plugin page' and 'Donate' links to plugin meta row
		if ( strpos( $file, 'security-protection.php' ) !== false ) {
			$links = array_merge( $links, array( '<a href="http://web-profile.com.ua/wordpress/plugins/security-protection/" title="Plugin page">Security-protection</a>' ) );
			$links = array_merge( $links, array( '<a href="http://web-profile.com.ua/donate/" title="Support the development">Donate</a>' ) );
		}
		return $links;
	}
	add_filter( 'plugin_row_meta', 'securityprotection_plugin_meta', 10, 2 );
endif; // end of securityprotection_plugin_meta()