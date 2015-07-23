<?php if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 *
 *
 *	Custom column functionality 
 *
 * 
 */


/**
 *
 *
 *	GIGS
 * 
 * 
 */

/**
 * Add the custom gig columns 
 * @since  1.0
 */
function mup_gig_admin_columns( $columns ) {

	$columns = array(
		'cb' 		=> '<input type="checkbox" />',
		'title' 	=> __( 'Venue', 'mup' ),
		'address' 	=> __( 'Address', 'mup' ),
		'gig_date' 	=> __( 'Date', 'mup' ),
		'time' 		=> __( 'Time', 'mup' ),
		'cost' 		=> __( 'Ticket Cost', 'mup' )
	);

	return $columns;
}

add_filter( 'manage_edit-gig_columns', 'mup_gig_admin_columns' ) ;


/**
 * Make the gig columns sortable
 * @since  1.0
 */
function mup_gig_admin_sortable_columns( $columns ) {

	$columns['gig_date'] 	= 'gig_date';
	$columns['time'] 		= 'time';
	$columns['cost'] 		= 'cost';

	return $columns;
}

add_filter( 'manage_edit-gig_sortable_columns', 'mup_gig_admin_sortable_columns' );

/**
 * Show the gig data inside the columns
 * @since  1.0
 */
function mup_gig_admin_show_data_in_columns( $column, $post_id ) {
	
	$gig_address 		= get_post_meta( $post_id, 'gig_address', true );
	$gig_date 			= get_post_meta( $post_id, 'gig_date', true );
	$gig_time 			= get_post_meta( $post_id, 'gig_time', true );
	$gig_cost 			= get_post_meta( $post_id, 'gig_cost', true );
	$musicpress_options = get_option( 'musicpress_options' );

	//var_dump($gig_column);

	switch( $column ) {

		case 'address' :

			/* If no address is found, output a default message. */
			if ( empty( $gig_address ) )
					esc_attr_e( 'Address not entered', 'mup' );

			else
					// Show the address
					esc_attr_e( $gig_address );

			break;

		case 'gig_date' :

			/* If no date is found, output a default message. */
			if ( empty( $gig_date ) )
					esc_attr_e( 'Date not entered', 'mup' );

			else
					// Convert the date to WordPress default
					echo esc_attr_e( date( get_option('date_format'), strtotime( $gig_date ) ) );

			break;

		case 'time' :

			/* If no time is found, output a default message. */
			if ( empty( $gig_time ) )
					esc_attr_e( 'Time not entered', 'mup' );

			else
					// Convert the time to WordPress default
					esc_attr_e( date( get_option('time_format'), strtotime( $gig_time ) ) );

			break;


		case 'cost' :

			/* If no cost is found, output a default message. */
			if ( empty( $gig_cost ) )
					esc_attr_e( 'Free', 'mup' );

			else
					// Show the ticket cost along with the chosen currency
					esc_attr_e( $musicpress_options['currency'] . $gig_cost );

			break;

			
		/* Just break out of the switch statement for everything else. */
		default :
			break;
	}
}

add_action( 'manage_gig_posts_custom_column', 'mup_gig_admin_show_data_in_columns', 10, 2 );


/**
 *
 *
 *	Tracks
 * 
 * 
 */

/**
 * Add the custom track columns 
 * @since  1.0
 */
function mup_track_admin_columns( $columns ) {

	$columns = array(
		'cb' 			=> '<input type="checkbox" />',
		'title' 		=> __( 'Track Name', 'mup' ),
		'release_date' 	=> __( 'Release Date', 'mup' ),
		'price' 		=> __( 'Price', 'mup' ),
		'purchase' 		=> __( 'Purchase Link', 'mup' )
	);

	return $columns;
}

add_filter( 'manage_edit-track_columns', 'mup_track_admin_columns' ) ;

/**
 * Make the track columns sortable
 * @since  1.0
 */
function mup_track_admin_sortable_columns( $columns ) {

	$columns['release_date'] 	= 'release_date';
	$columns['price'] 			= 'price';
	$columns['purchase'] 		= 'purchase';

	return $columns;
}

add_filter( 'manage_edit-track_sortable_columns', 'mup_track_admin_sortable_columns' );

/**
 * Show the track data inside the columns
 * @since  1.0
 */
function mup_track_admin_show_data_in_columns( $column, $post_id ) {
	
	$track_release 		= get_post_meta( $post_id, 'track_release_date', true );
	$track_buy_link		= get_post_meta( $post_id, 'track_buy_link', true );
	$track_price		= get_post_meta( $post_id, 'track_price', true );

	
	$musicpress_options = get_option( 'musicpress_options' );

	//var_dump($gig_column);

	switch( $column ) {

		case 'release_date' :

			/* If no release date is found, output a default message. */
			if ( empty( $track_release ) )
					esc_attr_e( 'Date not entered', 'mup' );

			else
					// Convert the release date to WordPress default
					esc_attr_e( date( get_option('date_format'), strtotime( $track_release ) ) );

			break;

		case 'purchase' :

			/* If no purchase link is found, output a default message. */
			if ( empty( $track_buy_link ) )
					esc_attr_e( 'Link not entered', 'mup' );

			else
					// Show the purchase link
				echo '<a href="'.esc_attr( $track_buy_link ).'">'.esc_attr( $track_buy_link ).'</a>';

			break;


		case 'price' :

			/* If no price is found, output a default message. */
			if ( empty( $track_price ) )
					esc_attr_e( 'Free', 'mup' );

			else
					// Show the price along with the chosen currency
					esc_attr_e( $musicpress_options['currency'] . $track_price );

			break;

			
		/* Just break out of the switch statement for everything else. */
		default :
			break;
	}
}

add_action( 'manage_track_posts_custom_column', 'mup_track_admin_show_data_in_columns', 10, 2 );


/**
 *
 *
 *	Fans
 * 
 * 
 */

/**
 * Add the custom fans columns 
 * @since  1.0
 */
function mup_fan_admin_columns( $columns ) {

	$columns = array(
		'cb' 			=> '<input type="checkbox" />',
		'title' 		=> __( 'Email', 'mup' ),
		'fan_name' 		=> __( '', 'mup' ),
		'fan_age' 		=> __( '', 'mup' ),
		'fan_country' 	=> __( 'Country', 'mup' )
	);

	return $columns;
}

add_filter( 'manage_edit-fan_columns', 'mup_fan_admin_columns' ) ;

/**
 * Make the fans columns sortable
 * @since  1.0
 */
function mup_fan_admin_sortable_columns( $columns ) {

	$columns['fan_country'] = 'fan_country';

	return $columns;
}

add_filter( 'manage_edit-fan_sortable_columns', 'mup_fan_admin_sortable_columns' );

/**
 * Show the fan data inside the columns
 * @since  1.0
 */
function mup_fan_admin_show_data_in_columns( $column, $post_id ) {
	
	$fan_column = get_post_meta( $post_id, 'fan_meta', true );

	//var_dump($fan_column);

	switch( $column ) {

		case 'fan_country' :

			/* If no country is found, output a default message. */
			if ( empty( $fan_column['fan_country'] ) )
					esc_attr_e( 'Free', 'mup' );

			else
					// Show the fans country
					esc_attr_e( $fan_column['fan_country'] );

			break;

			
		/* Just break out of the switch statement for everything else. */
		default :
			break;
	}
}

add_action( 'manage_fan_posts_custom_column', 'mup_fan_admin_show_data_in_columns', 10, 2 );