/*
Security-protection plugin
http://wordpress.org/plugins/security-protection/
*/

jQuery(function($){

	$('.secprot-group').hide(); // hide inputs from users

	var code = $('.secprot-group-code .secprot-answer').text();
	$('.secprot-group-code .secprot-control').val( code ); // answer the captcha instead of the user

	var dynamic_control = '<input type="hidden" name="secprot-code" class="secprot-control secprot-control-code" value="asd321" />';

	$.each($('.woocommerce form'), function(index, woocommerce_form) { // add input for every WooCommerce form if there are more than 1 form
		if ($(woocommerce_form).find('.secprot-control-code').length == 0) { // check if input already exist
			$(woocommerce_form).append(dynamic_control);
		}
	});

});