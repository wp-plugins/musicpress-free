<?php if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/*
Plugin Name: MusicPress - FREE
Plugin URI: http://musicpress.io
Description: The Definitive WordPress Plugin For Musicians and Performers. PRO - Version coming very soon.
Version: 1.0
Author: Jesse Cohen
Author URI: http://www.jessecohen.co.uk
License: GPL
Copyright: Jesse Cohen
Text Domain: mup
*/

if( !class_exists( 'musicpress' ) ) {

	class musicpress {

		var $mup_options;

		public function __construct() {
			
			define('MUP_VERSION', '1.0');


			// Language Translation
			load_textdomain('mup', dirname( __FILE__ ) . '/assets/lang/mup-' . get_locale() . '.mo');

			/**
			 * Activate MusicPress 
			 * @since  1.0
			 */
			register_activation_hook( __FILE__, array( $this , 'mup_activate' ) );


			// Actions
			add_action( 'init', array( $this, 'mup_install' ), 1 );

			

			// Filters 
			

			// File includes 
			$this->mup_include_before_theme();

		}

		/**
		 * Check version number
		 * @since  1.0
		 */
		public static function mup_activate() {

			if( version_compare( get_bloginfo( 'version' ), '3.8', '<' ) ) {

				require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
				deactivate_plugins( plugin_basename( __FILE__ ) );
				wp_die( __( 'This plugin requires a minimum of WordPress 3.8.  Sorry about that.' ) );	
			}

		}

		/**
		 * Include neccessary files for MusicPress before the theme functions.php 
		 * @since 1.0
		 */
		function mup_include_before_theme() {

			if( is_admin() ) {

				include_once( dirname( __FILE__ ) . '/assets/inc/mup-admin-functions.php' );
				include_once( dirname( __FILE__ ) . '/assets/inc/mup-admin-email.php' );
				include_once( dirname( __FILE__ ) . '/assets/inc/mup-admin-email.php' );
				include_once( dirname( __FILE__ ) . '/assets/inc/mup-admin.php' );
				include_once( dirname( __FILE__ ) . '/assets/inc/mup-admin-meta-boxes.php' );
				include_once( dirname( __FILE__ ) . '/assets/inc/mup-admin-columns.php' );
				
			}
			include_once( dirname( __FILE__ ) . '/assets/inc/mup-front-widgets.php' );
			include_once( dirname( __FILE__ ) . '/assets/inc/mup-front-functions.php' );
		}
		

		function mup_install() {

			// Creates the Gigs post type
			$labels = array(
					'menu_name' => __( 'Gigs', 'mup' ),
				    'name' => __( 'My Gigs', 'mup' ),
					'singular_name' => __( 'Gig', 'mup' ),
				    'add_new' => __( 'Add New' , 'mup' ),
				    'add_new_item' => __( 'Add New Gig' , 'mup' ),
				    'edit_item' =>  __( 'Edit Gig' , 'mup' ),
				    'new_item' => __( 'New Gig' , 'mup' ),
				    'view_item' => __('View Gig', 'mup'),
				    'search_items' => __('Search Gigs', 'mup'),
				    'not_found' =>  __('No Gig found', 'mup'),
				    'not_found_in_trash' => __('No Gig found in Trash', 'mup'), 
			);
				
			register_post_type( 'gig', array(
					'labels' => $labels,
					'public' => false,
					'show_ui' => true,
					'_builtin' =>  false,
					'capability_type' => 'post',
					'hierarchical' => true,
					'rewrite' => true,
					'query_var' => "gig",
					'supports' => array(
						'title',
					),
					'show_in_menu'	=> 'musicpress',
			));

			// Creates the Tracks post type
			$labels = array(
					'menu_name' => __( 'Tracks', 'mup' ),
				    'name' => __( 'My Tracks', 'mup' ),
					'singular_name' => __( 'Track', 'mup' ),
				    'add_new' => __( 'Add New' , 'mup' ),
				    'add_new_item' => __( 'Add New Track' , 'mup' ),
				    'edit_item' =>  __( 'Edit Track' , 'mup' ),
				    'new_item' => __( 'New Track' , 'mup' ),
				    'view_item' => __('View Track', 'mup'),
				    'search_items' => __('Search Tracks', 'mup'),
				    'not_found' =>  __('No Track found', 'mup'),
				    'not_found_in_trash' => __('No Track found in Trash', 'mup'), 
			);
				
			register_post_type( 'track', array(
					'labels' => $labels,
					'public' => true,
					'show_ui' => true,
					'_builtin' =>  false,
					'capability_type' => 'post',
					'hierarchical' => true,
					'rewrite' => true,
					'query_var' => "track",
					'supports' => array(
						'title',
						'thumbnail',
					),
					'show_in_menu'	=> 'musicpress',
					'menu_position'	=> 15,
			));

			// Creates the Fans post type
			$labels = array(
					'menu_name' => __( 'Fans', 'mup' ),
				    'name' => __( 'My Fans', 'mup' ),
					'singular_name' => __( 'Fan', 'mup' ),
				    'add_new' => __( 'Add New' , 'mup' ),
				    'add_new_item' => __( 'Add New Fan' , 'mup' ),
				    'edit_item' =>  __( 'Edit Fan' , 'mup' ),
				    'new_item' => __( 'New Fan' , 'mup' ),
				    'view_item' => __('View Fan', 'mup'),
				    'search_items' => __('Search Fans', 'mup'),
				    'not_found' =>  __('No Fan found', 'mup'),
				    'not_found_in_trash' => __('No Fan found in Trash', 'mup'),
			);
				
			register_post_type( 'fan', array(
					'labels' => $labels,
					'public' => false,
					'show_ui' => true,
					'_builtin' =>  false,
					'capability_type' => 'post',
					'hierarchical' => true,
					'rewrite' => false,
					'query_var' => "fan",
					'supports' => array(
						'title',
					),
					'show_in_menu'	=> 'musicpress',
					'can_export'	=> true,
					'menu_position'	=> 5,
			));


			// Actions
			add_action( 'admin_enqueue_scripts', 'mup_add_admin_scripts' );
			add_action( 'admin_menu', array( $this, 'mup_admin_menu' ), 1 );
			add_action( 'add_meta_boxes', 'mup_add_custom_metaboxes' );
			

			// Filters
	

			/**
			 * Set the initial options for MusicPress
			 * @since 1.0
			 */
			
			$this->mup_options = array(
				'version'		=>	MUP_VERSION,
				'twitter'		=>	'http://twitter.com/musicpressvip',
				'facebook'		=>	'http://facebook.com/musicpress',
				'youtube'		=>	'http://youtub.com/musicpressvip',
				'googleplus'	=>	'http://plus.google.com/musicpressvip',
				'soundcloud'	=>	'http://soundcloud.com/sigma',
				'itunes'		=>	'http://itunes.com/musicpressvip',
				'mpcron'		=>	'0',
				'currency'		=>	'$',
				'credit'		=>	'1'
				);

			if( FALSE == get_option( 'musicpress_options' ) ) {
				update_option( 'musicpress_options', $this->mup_options );
			}



		}

	 	/**
		 * Add admin menu 
		 * @since 1.0
		 */
		function mup_admin_menu()
		{
			add_menu_page( 'MusicPress', 'MusicPress', 'manage_options', 'musicpress', 'mup_show_welcome_page', plugins_url( 'musicpress-free' ) . '/assets/img/musicpress-admin-menu-logo.png', 25 );
			add_submenu_page( 'musicpress', __( 'Welcome To MusicPress', 'mup' ), __( 'Welcome', 'mup' ), 'manage_options', 'musicpress' );
			add_submenu_page( 'musicpress', __( 'Settings', 'mup' ), __( 'Settings', 'mup' ), 'manage_options', 'musicpress&tab=settings', 'mup_show_welcome_page' );

		}


		/**
		 * Deactivation routines for MusicPress
		 * @since 1.0
		 */
		function mup_deactivate() {
			// Delete any pages and remove any scripts and widgets that get loaded in

		}	


	}
}

if( class_exists( 'musicpress' ) ) {
    // Installation and uninstallation hooks
    register_activation_hook( __FILE__, array( 'musicpress', 'mup_activate' ) );
    register_deactivation_hook(__FILE__, array( 'musicpress', 'mup_deactivate' ) );

    // instantiate the plugin class
    $musicpress = new musicpress();
}


