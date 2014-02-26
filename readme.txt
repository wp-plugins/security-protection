=== Security-protection ===
Contributors: webvitaly
Donate link: http://web-profile.com.ua/donate/
Tags: brute-force, bruteforce, login, register, reset-password, security, protection, protector, protect, block, bot, defender, guardian
Requires at least: 3.0
Tested up to: 3.8.1
Stable tag: 1.0
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.html

Protection from login, register and reset-password brute-force attacks.

== Description ==

[Security-protection](http://web-profile.com.ua/wordpress/plugins/security-protection/ "Plugin page") |
[Donate](http://web-profile.com.ua/donate/ "Support the development")

Important: delete username 'admin' if you have it on your site. More than 90% of brute-force attacks try to crack the 'admin' username.

Top 10 most commonly used and worst passwords. Do not use them:

* 123456
* password
* qwerty
* abc123
* 111111
* 123123
* 000000
* admin123
* iloveyou
* letmein


Plugin is easy to use: just install it and it just works.
Need [more info about the plugin](http://wordpress.org/plugins/security-protection/faq/)?


= Useful: =
* ["Anti-spam" - block spam in comments](http://wordpress.org/plugins/anti-spam/ "no spam, no captcha")
* ["Page-list" - show list of pages with shortcodes](http://wordpress.org/plugins/page-list/ "list of pages with shortcodes")
* ["activetab" - responsive clean theme](http://wordpress.org/themes/activetab "responsive clean and light theme")


== Installation ==

1. install and activate the plugin on the Plugins page
2. enjoy life without login, register and reset-password brute-force attacks

== Frequently Asked Questions ==

= How does Security-protection plugin work? =

Plugin adds cookie on login screen and checks if this cookie exists in the POST request.
If cookie does not exist than it is brute-force request and the login attempt is blocked even if username and password are correct.
Plugin redirects the brute-force bot to admin section to emulate that the password is cracked and some brute-forcers stop their attacks after such redirect :)

= How to test what brute-force attacks are blocked? =

You may enable sending info about blocked brute-force attacks to admin email.
Edit [security-protection.php](http://plugins.trac.wordpress.org/browser/security-protection/trunk/security-protection.php) file and find "$securityprotection_send_brute_force_log_to_admin" and make it "true".


== Changelog ==

= 1.0 - 2014-02-25 =
* initial release - Protect from login, register and reset-password brute-force attacks using cookie check