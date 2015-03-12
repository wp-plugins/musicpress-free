jQuery(document).ready(function($) {
	/**
	 * Send test email preview
	 * @since  1.0
	 */
    $('#mup-test').click(function() {

    	$(".test-box .spinner").show();

        // Declare the variables
    	var mup_email 	 = $(".test-box #mup-test-email").val();
    	var mup_subject  = $("#mup-email-form #subject").val();
    	var mup_content  = $("#mup-email-form textarea").val();
        var mup_nonce    = $(".test-box #mup_test_email_nonce").val();

        // Send the Ajax request
    	$.ajax({
    		url: mupAjax.ajaxurl,
    		type: 'POST',
    		data: {
    			action: 'mup_send_test_email',
                nonce: mup_nonce,
    			'email': mup_email, 
		        'subject': mup_subject,
		        'content': mup_content
    		},
    		cache: false,
    		success: function(data) {


                var status  = $(data).find('response_data').text();
                var message = $(data).find('supplemental message').text();
               
	    		if(status == 'success') {
                	$(".test-box .spinner").hide();
			    	$(".test-email-success").slideDown();
                    $(".test-email-success p.message").text(message);
                    $(".test-email-success").delay(4000).fadeOut();
            	}
                else {
                    $(".test-email-error").slideDown();
                    $(".test-box .spinner").hide();
                    $(".test-email-error p.message").text(message);
                    $(".test-email-error").delay(4000).fadeOut();
                }
			}
		});

    });

    /**
     * Send Email Ajax function 
     * @since 1.0
     */
    $(".mup-sending").hide();

    var mup_interval = null;
    
    function mup_interval_checker(){                 
                
                var mup_email_fans_nonce  = $("#mup_email_fans_nonce").val();

                var data = {
                    'action': 'mup_update_progress_bar',
                    nonce: 'mup_email_fans_nonce'
                };

                $.post(mupAjax.ajaxurl, data, function(response) {
                    var result = jQuery.parseJSON(response);
                    console.log(result);
                    $("#mup-progressbar").val(response);
                });
                
    };

    $("#mup-email-fans").click(function() {

        $(".mup-email-window").slideUp();  
        $(".mup-notify").show();  
        $(".mup-sending").show();
        mup_interval = setInterval(mup_interval_checker,750);

        // Disable the buttons 
        $("#save-email").hide();
        $("#mup-email-fans").hide();
        $("#mup-test").attr('disabled','disabled');
        
        // Declare the variables
        var mup_subject  = $("#mup-email-form #subject").val();
        var mup_content  = $("#mup-email-form textarea").val();
        var mup_fan_count  = $("#mup-progressbar").prop('max');
        var mup_email_fans_nonce  = $("#mup_email_fans_nonce").val();

        // Build the data array
        var data = {
                action: 'mup_email_fans',
                nonce: mup_email_fans_nonce,
                'fan_count': mup_fan_count,
                'subject': mup_subject,
                'content': mup_content
            }

        // Send the Ajax request
        $.post(mupAjax.ajaxurl, data, function(response) {

            var result = jQuery.parseJSON(response);

            if(result == 'success') {
                clearInterval(mup_interval);
                console.log(response);
                $("#mup-progressbar").attr('value', 100);
                $(".send-email-success").delay(1000).slideDown();
                $(".send-email-success").delay(2750).fadeOut();

                // Switch back to content view
                $(".mup-notify").delay(4500).fadeOut(); 
                $(".mup-sending").delay(4500).slideUp();
                $(".mup-email-window").delay(4000).slideDown();    
                    
                // Enable the buttons 
                $("#mup-test").delay(5000).prop('disabled', false);
                $("#save-email").delay(5000).show();
                $("#mup-email-fans").delay(5000).show();   

            }
            else if(result == 'error') {
                clearInterval(mup_interval);
                $(".send-email-error").slideDown();
                 $(".send-email-error").delay(4000).fadeOut();
            }
            else {
                $("#mup-progressbar").attr('value', result);
            }
         

        }); // Ends the post function
        
                
    }); // Ends the click function 

});