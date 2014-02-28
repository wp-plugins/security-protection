<?php

if ( ! function_exists( 'securityprotection_random_string_generator' ) ) :
	function securityprotection_random_string_generator( $readable = 0, $length = 32 ) {
		$random_string = '';
		if( $readable ){ // create readable random string like 'suzuki'
			$characters_b = 'bcdfghjklmnpqrstvwxz';
			$characters_a = 'aeiouy';
			$ab = 'b';
			for( $i = 0; $i < $length; $i++ ) {
				if( $ab == 'b' ){
					$random_string .= $characters_b[ rand( 0, strlen( $characters_b ) - 1 ) ];
					$ab = 'a';
				} else {
					$random_string .= $characters_a[ rand( 0, strlen( $characters_a ) - 1 ) ];
					$ab = 'b';
				}
			}
		} else { // create fully random string like 'q3WLtN'
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			for( $i = 0; $i < $length; $i++ ) {
				$random_string .= $characters[ rand( 0, strlen( $characters ) - 1) ];
			}
		}
		return $random_string;
	}
endif; // end of securityprotection_random_string_generator()


if ( ! function_exists( 'securityprotection_fake_redirect' ) ) :
	function securityprotection_fake_redirect() { // fake admin dashboard redirect
		//header("HTTP/1.0 403 Forbidden"); // correct redirect
		$redirect_to = admin_url();
		wp_safe_redirect($redirect_to); // redirect the brute-force bot to admin section to emulate that the password is cracked and some brute-forcers stop their attacks after such redirect :)
		exit();
	}
endif; // end of securityprotection_fake_redirect()


if ( ! function_exists( 'securityprotection_set_fake_login_cookies' ) ) :
	function securityprotection_set_fake_login_cookies() { // set fake login cookies

		$expiration = time() + 14 * DAY_IN_SECONDS;
		$expire = $expiration + ( 12 * HOUR_IN_SECONDS );
		$secure = '';

		// login cookie names are located in wp-includes/default-constants.php:
		// define('AUTH_COOKIE', 'wordpress_' . COOKIEHASH);
		// define('LOGGED_IN_COOKIE', 'wordpress_logged_in_' . COOKIEHASH);

		$cookie_name_random = securityprotection_random_string_generator();
		$cookie_value_random = securityprotection_random_string_generator();
		$auth_cookie_fake = 'wordpress_'.$cookie_name_random;
		$logged_in_cookie_fake = 'wordpress_logged_in_'.$cookie_name_random;

		setcookie($auth_cookie_fake, $cookie_value_random, $expire, COOKIEPATH, COOKIE_DOMAIN, $secure, true);
		setcookie($logged_in_cookie_fake, $cookie_value_random, $expire, COOKIEPATH, COOKIE_DOMAIN, $secure, true);
	}
endif; // end of securityprotection_set_fake_login_cookies()