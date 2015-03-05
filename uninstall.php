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

global $wpdb;

/**
 * Remove all gigs from the database
 * @since 1.0
 */
$cptName_gigs = 'gig';

$tablePostMeta_gigs = $wpdb->prefix . 'postmeta';
$tablePosts_gigs = $wpdb->prefix . 'posts';

$postMetaDeleteQuery_gigs = "DELETE FROM $tablePostMeta_gigs".
                      " WHERE post_id IN".
                      " (SELECT id FROM $tablePosts_gigs WHERE post_type='$cptName_gigs'";

$postDeleteQuery_gigs = "DELETE FROM $tablePosts_gigs WHERE post_type='$cptName_gigs'";

$wpdb->query($postMetaDeleteQuery_gigs);
$wpdb->query($postDeleteQuery_gigs);

/**
 * Remove gig postmeta
 * @since  1.0
 */
$remove_gig_meta = "DELETE FROM $tablePostMeta_gigs WHERE meta_key LIKE 'gig_%'";

$wpdb->query($remove_gig_meta);


/**
 * Remove all tracks from the database
 * @since 1.0
 */
$cptName_tracks = 'track';

$tablePostMeta_tracks = $wpdb->prefix . 'postmeta';
$tablePosts_tracks = $wpdb->prefix . 'posts';

$postMetaDeleteQuery_tracks = "DELETE FROM $tablePostMeta_tracks".
                      " WHERE post_id IN".
                      " (SELECT id FROM $tablePosts_tracks WHERE post_type='$cptName_tracks'";

$postDeleteQuery_tracks = "DELETE FROM $tablePosts_tracks WHERE post_type='$cptName_tracks'";

$wpdb->query($postMetaDeleteQuery_tracks);
$wpdb->query($postDeleteQuery_tracks);

/**
 * Remove track postmeta
 * @since  1.0
 */
$remove_track_meta = "DELETE FROM $tablePostMeta_tracks WHERE meta_key LIKE 'track_%'";

$wpdb->query($remove_track_meta);

/**
 * Remove all fans from the database
 * @since 1.0
 */
$cptName_fans = 'fan';

$tablePostMeta_fans = $wpdb->prefix . 'postmeta';
$tablePosts_fans = $wpdb->prefix . 'posts';

$postMetaDeleteQuery_fans = "DELETE FROM $tablePostMeta_fans".
                      " WHERE post_id IN".
                      " (SELECT id FROM $tablePosts_fans WHERE post_type='$cptName_fans'";

$postDeleteQuery_fans = "DELETE FROM $tablePosts_fans WHERE post_type='$cptName_fans'";

$wpdb->query($postMetaDeleteQuery_fans);
$wpdb->query($postDeleteQuery_fans);

/**
 * Remove track postmeta
 * @since  1.0
 */
$remove_fan_meta = "DELETE FROM $tablePostMeta_fans WHERE meta_key LIKE 'fan_%'";

$wpdb->query($remove_fan_meta);
