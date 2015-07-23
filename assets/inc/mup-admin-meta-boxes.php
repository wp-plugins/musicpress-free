<?php if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 *
 *
 *	All metabox functionality 
 * 	@since 1.0
 * 
 */

function mup_add_custom_metaboxes() {

	/**
	 * Venue Information meta box
	 */
	add_meta_box( 
		'musicpress_gig_meta_box', 
		__( 'Venue Information', 'mup' ), 
		'musicpress_display_gig_meta_box', 
		'gig', 
		'normal', 
		'high'
	);

	/**
	 * Track Information meta box
	 */
	add_meta_box( 
		'musicpress_track_meta_box', 
		__( 'Track Information', 'mup' ), 
		'musicpress_display_track_meta_box', 
		'track', 
		'normal', 
		'high'
	);

	/**
	 * Fan Information meta box
	 */
	add_meta_box( 
		'musicpress_fan_meta_box', 
		__( 'Fan Information', 'mup' ), 
		'musicpress_display_fan_meta_box', 
		'fan', 
		'normal', 
		'high'
	);

}

/**
 *
 *
 *	Gigs Meta
 *
 * 
 */

/**
 * Display the meta box for gigs
 * @since 1.0
 */
function musicpress_display_gig_meta_box( $post ) { 

			$gig_address 	=	get_post_meta( $post->ID, 'gig_address', true );
			$gig_date 		=	get_post_meta( $post->ID, 'gig_date', true );
			$gig_time 		=	get_post_meta( $post->ID, 'gig_time', true );
			$gig_cost 		= 	get_post_meta( $post->ID, 'gig_cost', true );
			$gig_ticket 	=	get_post_meta( $post->ID, 'gig_ticket_url', true );

	?>
	<div class="mup-field">
		<p class="mup-admin-label">
			<label class="gig-address"><?php esc_attr_e( 'Location', 'mup' ); ?></label>
		</p>
		<input type="text" class="widefat" name="gig-address" id="gig-address" placeholder="<?php esc_attr_e( 'Enter the address', 'mup' ); ?>" value="<?php esc_attr_e( $gig_address ); ?>" />
	</div>
	<div class="mup-field half-width">
		<p class="mup-admin-label">
			<label class="gig-date"><?php esc_attr_e( 'Date', 'mup' ); ?></label>
		</p>
		<input type="date" class="widefat" name="gig-date" id="gig-date" placeholder="dd/mm/yy" value="<?php esc_attr_e( $gig_date ); ?>" />
	</div>
	<div class="mup-field half-width">
		<p class="mup-admin-label">
			<label class="gig-time"><?php esc_attr_e( 'Time', 'mup' ); ?></label>
		</p>
		<input type="time" class="widefat" name="gig-time" id="gig-time" placeholder="<?php esc_attr_e( 'Enter the time', 'mup' ); ?>" value="<?php esc_attr_e( $gig_time ); ?>" />
	</div>
	<div class="clear"></div>
	<div class="mup-field half-width">
		<p class="mup-admin-label">
			<label class="gig-cost"><?php esc_attr_e( 'Ticket Cost', 'mup' ); ?></label>
		</p>
		<input type="number" class="widefat" name="gig-cost" id="gig-cost" placeholder="<?php esc_attr_e( 'Enter a price', 'mup' ); ?>" value="<?php esc_attr_e( $gig_cost ); ?>" />
	</div>
	<div class="mup-field half-width">
		<p class="mup-admin-label">
			<label class="gig-ticket-url"><?php esc_attr_e( 'Ticket URL', 'mup' ); ?></label>
		</p>
		<input type="url" class="widefat" name="gig-ticket-url" id="gig-ticket-url" placeholder="<?php esc_attr_e( 'Paste link', 'mup' ); ?>" value="<?php esc_attr_e( $gig_ticket ); ?>" />
	</div>
		<div class="clear"></div>
<?php 
	wp_nonce_field( plugin_basename( __FILE__ ), 'mup_nonce_field' );
}


/**
 *
 *
 *	Tracks Meta
 *
 * 
 */


/**
 * Display the meta box for tracks
 * @since 1.0
 */
function musicpress_display_track_meta_box( $post ) {

		$description 	= 	get_post_meta( $post->ID, 'track_description', true );
		$price 			=	get_post_meta( $post->ID, 'track_price', true );
		$release 		=	get_post_meta( $post->ID, 'track_release_date', true );
		$buy 			=	get_post_meta( $post->ID, 'track_buy_link', true );
		$soundcloud 	=	get_post_meta( $post->ID, 'track_soundcloud', true );

	?>
	<div class="mup-field">
		<p class="mup-admin-label">
			<label class="track-description"><?php esc_attr_e( 'Description', 'mup' ); ?></label>
		</p>
		<textarea name="track-description" id="track-description"><?php esc_attr_e( $description ); ?></textarea>
	</div>
	<div class="mup-field half-width">
		<p class="mup-admin-label">
			<label class="track-price"><?php esc_attr_e( 'Price', 'mup' ); ?></label>
		</p>
		<input type="text" class="widefat" name="track-price" id="track-price" placeholder="<?php esc_attr_e( 'Enter price', 'mup' ); ?>" value="<?php esc_attr_e( $price ); ?>" />
	</div>
	<div class="mup-field half-width">
		<p class="mup-admin-label">
			<label class="track-release"><?php esc_attr_e( 'Release Date', 'mup' ); ?></label>
		</p>
		<input type="date" class="widefat" name="track-release" id="track-release" placeholder="dd/mm/yy" value="<?php esc_attr_e( $release ); ?>" />
	</div>
	<div class="clear"></div>
	<div class="mup-field half-width">
		<p class="mup-admin-label">
			<label class="track-buy-link"><?php esc_attr_e( 'Purchase Link', 'mup' ); ?></label>
		</p>
		<input type="url" class="widefat" name="track-buy-link" id="track-buy-link" placeholder="<?php esc_attr_e( 'Paste link', 'mup' ); ?>" value="<?php echo esc_url( $buy ); ?>" />
	</div>
	<div class="mup-field half-width">
		<p class="mup-admin-label">
			<label class="track-soundcloud"><?php esc_attr_e( 'SoundCloud URL', 'mup' ); ?></label>
		</p>
		<input type="url" class="widefat" name="track-soundcloud" id="track-soundcloud" placeholder="<?php esc_attr_e( 'Paste link', 'mup' ); ?>" value="<?php echo esc_url( $soundcloud ); ?>" />
	</div>
	<div class="clear"></div>
<?php 
	wp_nonce_field( plugin_basename( __FILE__ ), 'mup_nonce_field' );
}

/**
 * Display the meta box for fan
 * @since 1.0
 */
function musicpress_display_fan_meta_box( $post ) {

	if( get_post_meta( $post->ID, 'fan_meta', true ) ) {
		$fan_info = get_post_meta( $post->ID, 'fan_meta', true );
	}

	/**
	 * Show the fan and include how many emails they have been sent - how long they have been registered.
	 */
?>
	<div class="mup-field">
		<p class="mup-admin-label">
			<label class="fan-country"><?php esc_attr_e( 'Country', 'mup' ); ?></label>
		</p>
		<input type="text" class="widefat" name="fan-country" id="fan-country" placeholder="<?php esc_attr_e( 'Enter a country', 'mup' ); ?>" value="<?php if( isset( $fan_info['fan_country'] ) ) { esc_attr_e( $fan_info['fan_country'] ); } ?>" />
	</div>
	<div class="clear"></div>

<?php 
	wp_nonce_field( plugin_basename( __FILE__ ), 'mup_nonce_field' );
}

/**
 * Check if the user can save meta information
 * @since  1.0
 */
function mup_user_can_save( $post_id, $nonce ) {

	if( current_user_can( 'manage_options' ) ) {

		$is_autosave = wp_is_post_autosave( $post_id );
		$is_revision = wp_is_post_revision( $post_id );
		$is_valid_nonce = ( isset( $_POST['mup_nonce_field'] ) && wp_verify_nonce( $_POST['mup_nonce_field'], plugin_basename( __FILE__ ) ) );

		return ! ( $is_autosave || $is_revision ) && $is_valid_nonce;
	}
	else {
		wp_die( __( 'You do not have sufficient permissions to access this page.', 'mup' ) );
	}
	
}

/**
 * Save the gig meta information inside the postmeta table
 * @since  1.0
 */
function mup_save_meta_box_data( $post_id ) {

	if( 'gig' == get_post_type( $post_id ) ) {

		if( mup_user_can_save( $post_id, 'mup_nonce_field' ) ) {

			if( isset( $_POST['gig-address'] ) || isset( $_POST['gig-date'] ) || isset( $_POST['gig-time'] ) || isset( $_POST['gig-cost'] ) || isset( $_POST['gig-ticket-url'] ) ) {

				// Clean up the meta box post inputs 
				$address 	= stripslashes( strip_tags( $_POST['gig-address'] ) );
				$date 		= stripslashes( strip_tags( $_POST['gig-date'] ) ); 
				$time 		= stripslashes( strip_tags( $_POST['gig-time'] ) );
				$cost 		= stripslashes( strip_tags( $_POST['gig-cost'] ) );
				$url 		= stripslashes( strip_tags( $_POST['gig-ticket-url'] ) );

				// Save the post meta
				update_post_meta( $post_id, 'gig_address', $address );
				update_post_meta( $post_id, 'gig_date', $date );
				update_post_meta( $post_id, 'gig_time', $time );
				update_post_meta( $post_id, 'gig_cost', $cost );
				update_post_meta( $post_id, 'gig_ticket_url', $url );

			}

		}
	}
	elseif( 'track' == get_post_type( $post_id ) ) { 

		if( mup_user_can_save( $post_id, 'mup_nonce_field' ) ) {

			if( isset( $_POST['track-description'] ) || isset( $_POST['track-price'] ) || isset( $_POST['track-release'] ) || isset( $_POST['track-buy-link'] ) || isset( $_POST['track-soundcloud'] ) ) {

				// Clean up the meta box post inputs 
				$description 		= stripslashes( strip_tags( $_POST['track-description'] ) );
				$price 				= stripslashes( strip_tags( $_POST['track-price'] ) );
				$release_date 		= stripslashes( strip_tags( $_POST['track-release'] ) );
				$buy_link 			= stripslashes( strip_tags( $_POST['track-buy-link'] ) );
				$soundcloud_link 	= stripslashes( strip_tags( $_POST['track-soundcloud'] ) );

				// Save the post meta
				update_post_meta( $post_id, 'track_description', $description );
				update_post_meta( $post_id, 'track_price', $price );
				update_post_meta( $post_id, 'track_release_date', $release_date );
				update_post_meta( $post_id, 'track_buy_link', $buy_link );
				update_post_meta( $post_id, 'track_soundcloud', $soundcloud_link );

			}

		}
		
	}
	elseif( 'fan' == get_post_type( $post_id ) ) { 
		
		if( mup_user_can_save( $post_id, 'mup_nonce_field' ) ) {

			if( isset( $_POST['fan-country'] ) ) {


				$fan_country 	= stripslashes( strip_tags( $_POST['fan-country'] ) );

				// Populate the post meta array
				$fan_information = array(
					'fan_country' 		=> $fan_country
				);
				update_post_meta( $post_id, 'fan_meta', $fan_information );

			}

		}
	}
	


}

add_action( 'save_post', 'mup_save_meta_box_data', 100 );

