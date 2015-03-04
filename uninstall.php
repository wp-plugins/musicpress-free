<?php if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 *
 *	Uninstall MusicPress
 * 
 */

if( !defined( 'WP_UNINSTALL_PLUGIN' ) ) exit;

/**
 * Delete the various stored options 
 */
delete_option( 'musicpress_options' );
delete_option( 'musicpress_css' );
delete_option( 'musicpress_email' );

// Remove Widgets options
delete_option( 'mup_gig_list_widget_length' );
delete_option( 'mup_track_list_widget_length' );
