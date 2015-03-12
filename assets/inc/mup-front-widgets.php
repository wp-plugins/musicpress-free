<?php if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/**
 *
 *	
 * Register MusicPress Widgets 
 * @since  1.0
 * 
 */
function musicpress_register_widgets() {
    
    register_widget( 'mup_fan_subscribe_widget' );
    register_widget( 'mup_gig_list_widget' );
    register_widget( 'mup_track_list_widget' );
    
}
add_action( 'widgets_init', 'musicpress_register_widgets' );

/**
 * Fan subscribe widget 
 */
class mup_fan_subscribe_widget extends WP_Widget {

    /**
     * Constructor
     *
     * @return void
     **/
    function mup_fan_subscribe_widget() {
    	parent::__construct( false, 'MusicPress Fan Subscription Widget' );
        $widget_ops = array( 
        	'classname' => 'mup-widget', 
        	'description' => __( 'Adds a input form allowing fans to subscribe.', 'mup' )
        );

        $this->WP_Widget( 'mup_fan_subscribe_widget', 'MusicPress: ' . __( 'Fan Subscribe', 'mup' ), $widget_ops );

    }

    /**
 	 * Displays the widget front end
 	 * @since 1.0
 	 */
    function widget( $args, $instance ) {
        
        extract( $args );

        echo $before_widget;
       	$title = apply_filters( 'widget_title', $instance['title'] );
        $description = isset( $instance['description'] ) ? $instance['description'] : FALSE;
        if( !empty( $title ) ) { echo $before_title . $title . $after_title; }
		?>
		<div id="mup-fan-widget">
			<?php if( !empty ($description ) ) { echo '<p>'. $description . '</p>'; } ?>
	        <input type="email" name="musicpress_email" id="musicpress_email" class="musicpress-email-input"/>
	        <input type="hidden" name="action" value="musicpress_subscribe_fan" />
	        <input type="hidden" name="musicpress_nonce" id="musicpress_nonce" value="<?php echo wp_create_nonce( 'musicpress_nonce' ); ?>" />
			<input type="button" class="musicpress-subscribe-button" id="musicpress-subscribe" value="<?php  _e( 'Subscribe', 'mup'); ?>" />
			<div class="fan-success">
	        	<p class="message"></p> 
	        </div> 
	        <div class="fan-error">
	        	<p class="message"></p> 
	        </div> 
		</div>
		<?php
		echo $after_widget;
    
    }


    /**
     * Update widget data
     **/
    function update( $new_instance, $old_instance ) {

        // update logic goes here
        $instance = $old_instance;

        $instance['title'] 	= strip_tags( $new_instance['title'] );
        $instance['description'] = strip_tags( $new_instance['description'] );


        return $instance;
    }

    /**
     * Display the widget - ADMIN 
     **/
    function form( $instance ) {
    	$defaults = array( 'title' => __( 'Fan Subscription', 'mup' ), 'description' => '' );
        $instance = wp_parse_args( (array) $instance, $defaults );
        $title 	  = $instance['title'];
        $description = $instance['description'];
        ?>
        <p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_attr_e( 'Title:', 'mup' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php esc_attr_e( $title ); ?>">
		<label for="<?php echo $this->get_field_id( 'description' ); ?>"><?php esc_attr_e( 'Text:', 'mup' ); ?></label>
		<textarea class="large-text" name="<?php echo $this->get_field_name( 'description' ); ?>" rows="4"><?php esc_attr_e( $description ); ?></textarea>
		</p>
		<?php 
    }

}

/**
 * List Gigs widget 
 */
class mup_gig_list_widget extends WP_Widget {

    /**
     * Constructor
     *
     * @return void
     **/
    function mup_gig_list_widget() {
    	parent::__construct( false, 'MusicPress Gig listing Widget' );
        $widget_ops = array( 
        	'classname' => 'mup-widget', 
        	'description' => __( 'List your gigs in widget form.', 'mup' )
        );

        $this->WP_Widget( 'mup_gig_list_widget', 'MusicPress: ' . __( 'Show Gigs', 'mup' ), $widget_ops );

    }

    /**
 	 * Displays the widget front end
 	 * @since 1.0
 	 */
    function widget( $args, $instance ) {
        extract( $args );

        echo $before_widget;
       	$title = apply_filters( 'widget_title', $instance['title'] );
        $gig_text = isset( $instance['gig_text'] ) ? $instance['gig_text'] : FALSE;
        if( !empty( $title ) ) { echo $before_title . $title . $after_title; }

        // Get custom pagination length
			$gig_length = get_option( 'mup_gig_list_widget_length' );

			if( !empty( $gig_length ) ) {
					$length = $gig_length;
			}
			else {
					$length = 4;
			}

		?>
		<div id="mup-gigs-widget">
			<?php if( !empty ($gig_text ) ) { echo '<p>'. $gig_text . '</p>'; } 

			$todays = date('F jS, Y');

			$args = array(
				'post_type'			=>	'gig',
				'post_status'		=>	'publish',
				'posts_per_page'	=>	$length,
				'meta_key' 			=>  'gig_date',
				'orderby'			=>	'meta_value',
				'order'				=>	'ASC',
				'meta_query' => array(
			        array(
			            'key' => 'gig_date',
			            'value' => date('Y-m-d'),
			            'compare' => '>=',
			            'type' => 'DATE'
			        )
			    )				
			);

			$gigs = new WP_Query( $args );

			if ( $gigs->have_posts() ) : while ( $gigs->have_posts() ) : $gigs->the_post();

				// Get the date query ready 
				$today = date('Y-m-d');

				$gid = get_the_id();
				
				// Get the gig meta
				$gig_date 		= get_post_meta( $gid, 'gig_date', TRUE );
				$gig_address 	= get_post_meta( $gid, 'gig_address', TRUE );
				$gig_tickets 	= get_post_meta( $gid, 'gig_ticket_url', TRUE );

					// Convert the date to human readable format
					$convert_date = new DateTime( $gig_date );
					$gig_month = date_format( $convert_date,'M' );
					$gig_day   = date_format( $convert_date,'d' ); ?>

					<div class="mup-widget-gig">

					<div class="date">
						<span class="month">
							<?php echo $gig_month; ?>
						</span>
						<span class="day">
							<?php echo $gig_day; ?>
						</span>
					</div>
					<div class="address">
						<span class="title"><?php the_title(); ?></span>
						<?php if( !empty( $gig_address ) ) { ?>
						<br>
						<span class="meta">
						<?php echo $gig_address; ?>
						</span>
						<?php } ?>
						<?php if( !empty( $gig_tickets ) ) { ?>
						<br>
						<span class="link"><a href="<?php echo $gig_tickets; ?>"><?php _e( 'Tickets', 'mup' ); ?></a></span>
						<?php } ?>
					</div>			
				</div>
				<div class="clear"></div>
			<?php endwhile; ?>
			<?php else: ?>
				<p><?php __( 'No gigs have been added yet.', 'mup' ); ?></p>
			<?php endif; ?>
			
		</div>
		<?php
		echo $after_widget;
    
    }


    /**
     * Update the widget
     **/
    function update( $new_instance, $old_instance ) {

        // update logic goes here
        $instance = $old_instance;

        $instance['title'] 	= strip_tags( $new_instance['title'] );
        $instance['gig_text'] = strip_tags( $new_instance['gig_text'] );
        $new_instance['widget_length'] = strip_tags( $new_instance['widget_length'] );
        update_option( 'mup_gig_list_widget_length', $new_instance['widget_length'] );

        return $instance;
    }

    /**
     * Display the widget - ADMIN
     **/
    function form( $instance ) {
    	$defaults = array( 'title' => __( '', 'mup' ), 'gig_text' => '' );
        $instance = wp_parse_args( (array) $instance, $defaults );
        $title 	  = $instance['title'];
        $gig_text = $instance['gig_text'];
        $widget_length = $instance['widget_length'];

        ?>
        <p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_attr_e( 'Title:', 'mup' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php esc_attr_e( $title ); ?>">
		<label for="<?php echo $this->get_field_id( 'gig_text' ); ?>"><?php esc_attr_e( 'Text:', 'mup' ); ?></label>
		<textarea class="large-text" name="<?php echo $this->get_field_name( 'gig_text' ); ?>" rows="4"><?php esc_attr_e( $gig_text ); ?></textarea>
		<label for="<?php echo $this->get_field_id( 'widget_length' ); ?>"><?php esc_attr_e( 'Display number of gigs:', 'mup' ); ?></label> 
		<input class="small-text" maxlength="1" id="<?php echo $this->get_field_id( 'widget_length' ); ?>" name="<?php echo $this->get_field_name( 'widget_length' ); ?>" type="text" value="<?php esc_attr_e( $widget_length ); ?>">
		</p>
		<?php 
    }

}


/**
 * List Tracks widget 
 */
class mup_track_list_widget extends WP_Widget {

    /**
     * Constructor
     *
     * @return void
     **/
    function mup_track_list_widget() {
    	parent::__construct( false, 'MusicPress Track listing Widget' );
        $widget_ops = array( 
        	'classname' => 'mup-widget', 
        	'description' => __( 'List your tracks in widget form.', 'mup' )
        );

        $this->WP_Widget( 'mup_track_list_widget', 'MusicPress: ' . __( 'Show Tracks', 'mup' ), $widget_ops );

    }

    /**
 	 * Displays the widget front end
 	 * @since 1.0
 	 */
    function widget( $args, $instance ) {
        extract( $args );

        echo $before_widget;
       	$title = apply_filters( 'widget_title', $instance['title'] );
        $track_text = isset( $instance['track_text'] ) ? $instance['track_text'] : FALSE;
        if( !empty( $title ) ) { echo $before_title . $title . $after_title; }
		?>
		<div id="mup-tracks-widget">
			<?php if( !empty ($track_text ) ) { echo '<p>'. $track_text . '</p>'; } 
			
			$todays = date('F jS, Y');

			// Get custom pagination length
			$track_length = get_option( 'mup_track_list_widget_length' );

			if( !empty( $track_length ) ) {
					$length = $track_length;
			}
			else {
					$length = 4;
			}

			$args = array(
				'post_type'			=>	'track',
				'post_status'		=>	'publish',
				'posts_per_page'	=>	$length,
				'meta_key' 			=>  'track_release_date',
				'orderby'			=>	'meta_value',
				'order'				=>	'DESC'	
			);

			$tracks = new WP_Query( $args );

			if ( $tracks->have_posts() ) : while ( $tracks->have_posts() ) : $tracks->the_post();

				// Get the date query ready 
				$today = date('Y-m-d');

				$tid = get_the_id();
				
				// Get the gig meta
				$track_buy_link 	= get_post_meta( $tid, 'track_buy_link', TRUE );
				$track_description 	= get_post_meta( $tid, 'track_description', TRUE );
				$track_price 		= get_post_meta( $tid, 'track_price', TRUE );
				$track_release 		= get_post_meta( $tid, 'track_release_date', TRUE );
				$track_soundcloud 	= get_post_meta( $tid, 'track_soundcloud', TRUE );

				// Order by release date

					// Convert the date to human readable format
					$convert_date 	= new DateTime( $track_release );
					$track_date 	= date_format( $convert_date,'d m Y' );
					?>

					<div id="mup-tracks-widget">
					<!-- <div class="description">
						<?php echo $track_description; ?>
					</div> -->
					<?php if( has_post_thumbnail( ) ) { ?>
					<div class="track-image"><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a></div>
					<?php } else { ?>
					<div class="track-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div>
					<?php } // Ends post thumbnail conditional ?>
					<div class="links">
						<ul class="link">
							<li><a href="<?php the_permalink(); ?>"><?php _e( 'Info', 'mup' ); ?></a></li>
						<?php if( !empty( $track_buy_link ) ) { ?>
							<li><a href="<?php echo $track_buy_link; ?>"><?php _e( 'Buy Track', 'mup' ); ?></a></li>
						<?php } ?>
						<?php if( !empty( $track_soundcloud ) ) { ?>			
							<li><a href="<?php echo $track_soundcloud; ?>"><?php _e( 'Listen', 'mup' ); ?></a></li>
						<?php } ?>
						</ul>
					</div>			
				</div>
				<div class="clear"></div>
			<?php endwhile; ?>
			<?php else: ?>
				<p><?php __( 'No tracks have been added.', 'mup' ); ?></p>
			<?php endif; ?>
			
		</div>
		<?php
		echo $after_widget;
    
    }


    /**
     * Update the widget
     **/
    function update( $new_instance, $old_instance ) {

        // update logic goes here
        $instance = $old_instance;

        $instance['title'] 	= strip_tags( $new_instance['title'] );
        $instance['track_text'] = strip_tags( $new_instance['track_text'] );
        $new_instance['widget_length'] = strip_tags( $new_instance['widget_length'] );
        update_option( 'mup_track_list_widget_length', $new_instance['widget_length'] );

        return $instance;
    }

    /**
     * Display the widget - ADMIN
     **/
    function form( $instance ) {
    	$defaults = array( 'title' => __( '', 'mup' ), 'track_text' => '' );
        $instance = wp_parse_args( (array) $instance, $defaults );
        $title 	  = $instance['title'];
        $track_text = $instance['track_text'];
        $widget_length = get_option( 'mup_track_list_widget_length' );

        ?>
        <p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_attr_e( 'Title:', 'mup' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php esc_attr_e( $title ); ?>">
		<label for="<?php echo $this->get_field_id( 'track_text' ); ?>"><?php esc_attr_e( 'Supporting Text:', 'mup' ); ?></label>
		<textarea class="large-text" name="<?php echo $this->get_field_name( 'track_text' ); ?>" rows="4"><?php esc_attr_e( $track_text ); ?></textarea>
		<label for="<?php echo $this->get_field_id( 'widget_length' ); ?>"><?php esc_attr_e( 'How many tracks in widget:', 'mup' ); ?></label> 
		<input class="small-text" maxlength="1" id="<?php echo $this->get_field_id( 'widget_length' ); ?>" name="<?php echo $this->get_field_name( 'widget_length' ); ?>" type="text" value="<?php esc_attr_e( $widget_length ); ?>">
		</p>
		<?php 
    }

}

/**
 * Ajax call to subscribe the fan 
 * @since 1.0
 */
function musicpress_subscribe_fan() {

	$response = new WP_Ajax_Response();

	if( check_ajax_referer( 'musicpress_nonce', 'nonce', false ) ) {

		//Validate the email 
	    $add_fan  = $_POST['email'];

	    if( empty( $add_fan ) ) {

	      $response->add( array(
	          'data'  =>  'error',
	          'supplemental' => array(
	            'message' => __( 'Email address not entered.', 'mup' )
	            )
	          ));

	      $response->send();
	    }

	    if( !is_email( $add_fan ) ) {
      
	      $response->add( array(
	          'data'  =>  'error',
	          'supplemental' => array(
	            'message' => __( 'Email address not valid.', 'mup' )
	            )
	          ));

	      $response->send();
	    }

	    /**
	     * Check fan doesn't already exist 
	     * @since  1.0
	     */
	    $fan_exist = get_page_by_title( $add_fan, ARRAY_A, 'fan' );
	    
	    if( $fan_exist['post_title'] ) {

	    	$response->add( array(
	        	'data'  =>  'error',
	        	'supplemental' => array(
	        		'message' => __( 'Email address already added.', 'mup' )
	            	)
	        ));

	      $response->send();
	    }
	    else {

		    /**
		     * Subscribe the fan 
		     * @since  1.0
		     */    
	    	$add_fan = sanitize_email( $add_fan );		    
		    $country = $_POST['country'];
		    
		    $new_fan = array(
		    	'post_type' 	=> 'fan',
		    	'post_title'	=> $add_fan,
		    	'post_status'	=> 'publish'
		    	);

		    $fan_meta = array(
		    	'fan_country' => $country
		    	);    

		    $insert = wp_insert_post( $new_fan );
		    
		    /**
		     * Insert the fan into the database
		     * @since  1.0
		     */
		    if( add_post_meta( $insert, 'fan_meta', $fan_meta ) ) { 
	        
	        $response->add( array(
	          'data'  =>  'success',
	          'supplemental' => array(
	            'message' => __( 'Email address subscribed', 'mup' )
	            )
	          ));

	        $response->send();

	      }
	      else {   

	      	/**
	      	 * Delete the post meta if the insert to database doesnt happen
	      	 */
	        delete_post_meta( $insert, 'fan_meta' );
	        wp_delete_post( $insert );

	        $response->add( array(
		        'data'  =>  'error',
		        'supplemental' => array(
			        'message' => __( 'Email could not be sent. Please check your email settings.', 'mup' )
			        )
	        ));
	        $response->send();
	      }
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
add_action( 'wp_ajax_nopriv_musicpress_subscribe_fan', 'musicpress_subscribe_fan' ); 
add_action( 'wp_ajax_musicpress_subscribe_fan', 'musicpress_subscribe_fan' );