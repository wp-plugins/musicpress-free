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
	 * Ajax booking form email send
	 * @since  1.2
	 */
	
	$('.mup-booking-form-submit').click(function() {

		   	var musicpress_booking_nonce 	= $("#mup_booking_form_nonce").val();
		   	var musicpress_booking_name 	= $('#mup-booking-name').val();
		   	var musicpress_booking_email 	= $('#mup-booking-email').val();
		   	var musicpress_booking_date 	= $('#mup-booking-date').val();
		   	var musicpress_booking_website 	= $('#mup-booking-website').val();
		   	var musicpress_booking_comment 	= $('#mup-booking-message').val();
		   	
		   	var success = $('.mup-success');
		    var error   = $('.mup-error');

		    error.find(".message").empty();

		    var data = {
		    		action: 'mup_send_booking_form',
		            nonce: musicpress_booking_nonce,
		            'name':  musicpress_booking_name,
		    		'email': musicpress_booking_email,
		    		'date': musicpress_booking_date,
		    		'website': musicpress_booking_website,
		    		message: musicpress_booking_comment
		    }

		    // Send the Ajax request
        	$.post(musicpressAjax.ajaxurl, data, function(response) {

        		var result = jQuery.parseJSON(response);
        		
        		$.each(result, function(key, value){ 

        			if(value == 'success') {
        				error.hide();
		            	success.slideDown(100);
        				success.delay(4000).fadeOut();

	        		} 
	        		else {
	        			error.find(".message").append(value + '<br />');
			            error.fadeIn(100);		
	        			
	        		}


        		})	

			});	
	});
	

});