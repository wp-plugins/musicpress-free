jQuery(document).ready(function($) {

	/**
	 * Ajax subscribe email form widget post 
	 * @since  1.0
	 */
	
	$('.musicpress-subscribe-button').click(function() {

		   	var musicpress_nonce = $(this).parent().find("#musicpress_nonce").val();
		   	var musicpress_email = $(this).parent().find('#musicpress_email').val();
		   	var musicpress_country = geoplugin_countryName();

		   	var $success = $(this).parent().find('.fan-success');
		    var $error   = $(this).parent().find('.fan-error');
		    

			$.ajax({
		    		url: musicpressAjax.ajaxurl,
		    		type: 'POST',
		    		data: {
		    			action: 'musicpress_subscribe_fan',
		                nonce: musicpress_nonce,
		    			email: musicpress_email,
		    			'country': musicpress_country
		    		},
		    		cache: false,
		    		success: function(data) {
		                var status  = $(data).find('response_data').text();
		                var message = $(data).find('supplemental message').text();
		               
			    		if(status == 'success') {
			    			// console.log(message);
			    			$success.find(".message").text(message);
		                    $success.slideDown();
        					$success.delay(4000).fadeOut();
		            	}
		                else {
		                    // console.log(message);
		                    $error.find(".message").text(message);
		                    $error.slideDown();
        					$error.delay(4000).fadeOut();
		                }
					}
			});	
	});

	/**
	 * Ajax gig listing widget pagination
	 * @since  1.0
	 */
	

});