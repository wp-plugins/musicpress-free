<?php if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
session_start();
/**
 *
 *
 *	Fan Email processing 
 *
 * 
 */
set_time_limit(5000);

/**
 * Send email preview using AJAX call
 * @since 1.0
 */
function mup_send_test_email(){


  $response = new WP_Ajax_Response();
  
  /**
   * Check Permissions
   */
  if( current_user_can( 'manage_options' ) && check_ajax_referer( 'mup_email_nonce', 'nonce', false ) ) {


    $subject = strip_tags( htmlspecialchars( $_POST['subject'] ) );
    $content = strip_tags( htmlspecialchars( $_POST['content'] ) );

    if( empty( $subject ) ) {

       $response->add( array(
          'data'  =>  'error',
          'supplemental' => array(
            'message' => __( 'Subject blank', 'mup' )
            )
          ));

      $response->send();

    }

    if( empty( $content ) ) {

       $response->add( array(
          'data'  =>  'error',
          'supplemental' => array(
            'message' => __( 'Content blank', 'mup' )
            )
          ));

      $response->send();

    }

    //Validate the email 
    $send_to  = $_POST['email'];

    if( empty( $send_to ) ) {

      $response->add( array(
          'data'  =>  'error',
          'supplemental' => array(
            'message' => __( 'Email address not entered.', 'mup' )
            )
          ));

      $response->send();
    }

    if( !is_email( $send_to ) ) {
      
      $response->add( array(
          'data'  =>  'error',
          'supplemental' => array(
            'message' => __( 'Email address not valid.', 'mup' )
            )
          ));

      $response->send();
    }

    $send_to  = sanitize_email( $_POST['email'] );


    $allowed    = wp_kses_allowed_html( 'post' );
    $protocols  = wp_allowed_protocols();
    $email      = wp_kses( $content, $allowed, $protocols );


    /**
     * Build email 
     * @since 1.0
     */
    $headers  = "From: ". $send_to ."\r\n";
    $headers .= "Reply-To: ". $send_to ."\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "X-Priority: 1\n";
    $headers .= "Content-Type: text/plain; charset=\"utf-8\"\r\n";
    
    
      if( wp_mail( $send_to, $subject, $email, $headers ) ) { 
        
        $response->add( array(
          'data'  =>  'success',
          'supplemental' => array(
            'message' => __( 'Email sent. Please check your inbox.', 'mup' )
            )
          ));
        $response->send();
      }
      else {   
         
         $response->add( array(
          'data'  =>  'error',
          'supplemental' => array(
            'message' => __( 'Email could not be sent. Please check your email settings.', 'mup' )
            )
          ));
        $response->send();
      }
   
  }
  else {

     $response->add( array(
          'data'  =>  'error',
          'supplemental' => array(
            'message' => __( 'Unable to verify form submission.', 'mup' )
            )
          ));

      $response->send();

  } 

  wp_die(); 

}
add_action( 'wp_ajax_nopriv_mup_send_test_email', 'mup_send_test_email' ); 
add_action( 'wp_ajax_mup_send_test_email', 'mup_send_test_email' );


/**
 * Email all the fans from the CPT
 * @since
 */
function mup_email_fans() {

  /**
   * Get all the fans - get the fan total and work out the math for the progress bar. 
   * For each fan email sent add another to the current progress by adding the value via jquery 
   */

global $wpdb;
unset($_SESSION['counter']);
  
  /**
   * Check Permissions
   */
  if( current_user_can( 'manage_options' ) && check_ajax_referer( 'mup_email_fans_nonce', 'nonce', false ) ) {  

    // Set the vars
    $email_count = $_POST['fan_count'];
    $subject = sanitize_text_field( $_POST['subject'] );
    
    $content = strip_tags( htmlspecialchars( $_POST['content'] ) );

    if( empty( $subject ) ) {

       echo json_encode( 'empty-subject' );
      die();

    }

    if( empty( $content ) ) {

      echo json_encode( 'empty-content' );
      die();

    }

    $email_count_update = $email_count / $email_count;


    // Get all the fan emails
    $result = $wpdb->get_results( "SELECT post_title FROM $wpdb->posts WHERE post_type = 'fan' AND post_status = 'publish'" );

    // Declare session as 1 incase pickup is quicker than interval check
    $_SESSION['counter'] = '1';

    if( $result ) {

     foreach ($result as $fan) {

        /**
         * Mail the fan with the content from the input form
         */

            $to = $fan->post_title;
            $headers = "From: " . bloginfo( 'name' ) ." <". get_option( 'admin_email' ) .">\r\n";
            $headers .= "Reply-To: ". get_option( 'admin_email' ) . "\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/plain; charset=\"utf-8\"\r\n";
            $message = $content;
             
            $mup_send_email = wp_mail( $to, $subject, $message, $headers );

            if( $mup_send_email ) {

                $email_count_update++; 

                // Explicitly write and close the session for good measure
                session_write_close();

                session_start();
                $_SESSION['counter'] = $email_count_update;

              // If all emails have been sent
              if( $email_count_update == $email_count ) {
                
                echo json_encode( 'success' );
                die();
              
              }             

            }
            else {
              echo json_encode( 'error' );
              die();
            }
            
                

        }

      }
      else {
        // No fan exists
        echo json_encode( 'empty-fan' );
        die();
      }
    
    }

  wp_die(); 

}
add_action( 'wp_ajax_nopriv_mup_email_fans', 'mup_email_fans' ); 
add_action( 'wp_ajax_mup_email_fans', 'mup_email_fans' );


/**
 * Update the progress bar
 * @since 1.0
 */
function mup_update_progress_bar() {

  /**
   * Check Permissions
   */
  if( current_user_can( 'manage_options' ) ) {

      $count = $_SESSION['counter'];
      echo $count;
  }
  
  wp_die();       

}

add_action( 'wp_ajax_nopriv_mup_update_progress_bar', 'mup_update_progress_bar' ); 
add_action( 'wp_ajax_mup_update_progress_bar', 'mup_update_progress_bar' );


