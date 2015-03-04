<?php if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 *
 *
 *	General functions for the admin 
 * 	@since  1.0
 * 
 */

/**
 * Count the number of fans in the database 
 * @since 1.0
 */
function mup_get_fan_count() {

	global $wpdb;

	$sql = "SELECT COUNT(ID) FROM {$wpdb->posts} WHERE post_type = 'fan' AND post_status = 'publish'";

	$fan_count = $wpdb->get_var( $sql );

	return $fan_count;

}

/**
 * Custom messaging for post types
 * @since  1.0
 */
function mup_custom_updated_messages($messages) {
	
	global $post_ID, $post;
  
  	// Track custom message output
  	$messages['track'] = array(
	    0 => '', // Unused. Messages start at index 1.
	    1 => sprintf( __( 'Track updated. <a href="%s">View Track</a>', 'mup' ), esc_url( get_permalink( $post_ID ) ) ),
	    2 => __( 'Custom field updated', 'mup' ),
	    3 => __( 'Custom field deleted', 'mup' ),
	    4 => __( 'Track updated.', 'mup' ),
	    5 => isset( $_GET['revision'] ) ? sprintf( __( 'Track restored to revision from %s', 'mup' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
	    6 => sprintf( __( 'Track published. <a href="%s">View track</a>', 'mup' ), esc_url( get_permalink( $post_ID ) ) ),
	    7 => __('Track saved.'),
	    8 => sprintf( __( 'Track submitted. <a target="_blank" href="%s">Preview track</a>', 'mup' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ),
	    9 => sprintf( __( 'Track scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview track</a>', 'mup' ), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink( $post_ID ) ) ),
	    10 => sprintf( __( 'Track draft updated. <a target="_blank" href="%s">Preview track</a>', 'mup' ), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
  	);
	// Gig custom message output
	$messages['gig'] = array(
	    0 => '', // Unused. Messages start at index 1.
	    1 => sprintf( __( 'Gig updated.', 'mup' ) ),
	    2 => __( 'Custom field updated.', 'mup' ),
	    3 => __( 'Custom field deleted.', 'mup' ),
	    4 => __( 'Gig updated.', 'mup' ),
	    5 => isset( $_GET['revision']) ? sprintf( __( 'Gig restored to revision from %s', 'mup' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
	    6 => sprintf( __( 'Gig published. <a href="%s">View gig</a>', 'mup' ), esc_url( get_permalink($post_ID) ) ),
	    7 => __( 'Gig saved.', 'mup' ),
	    8 => sprintf( __( 'Gig submitted. <a target="_blank" href="%s">Preview gig</a>', 'mup' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID) ) ) ),
	    9 => sprintf( __( 'Gig scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview gig</a>', 'mup' ), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink( $post_ID ) ) ),
	    10 => sprintf( __( 'Gig draft updated. <a target="_blank" href="%s">Preview gig</a>', 'mup' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ),
  	);
	// Fan custom message output
	$messages['fan'] = array(
	    0 => '', // Unused. Messages start at index 1.
	    1 => sprintf( __( 'Fan updated', 'mup' ) ),
	    2 => __( 'Custom field updated', 'mup' ),
	    3 => __( 'Custom field deleted', 'mup' ),
	    4 => __( 'Gig updated.', 'mup' ),
	    5 => isset( $_GET['revision'] ) ? sprintf( __( 'Fan restored', 'mup' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
	    6 => sprintf( __( 'Fan published', 'mup' ), esc_url( get_permalink( $post_ID ) ) ),
	    7 => __( 'Fan saved.', 'mup' ),
	    8 => sprintf( __( 'Fan submitted', 'mup' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ),
	    9 => sprintf( __( 'Fan scheduled for: <strong>%1$s</strong>', 'mup' ), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink( $post_ID ) ) ),
	    10 => sprintf( __( 'Fan draft updated', 'mup' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ),
  	);

  return $messages;

}

add_filter( 'post_updated_messages', 'mup_custom_updated_messages' );

