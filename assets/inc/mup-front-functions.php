<?php if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 *
 *
 *
 *	Front End showing of independant fields
 *
 *
 * 
 */



/**
 * Get the id of the post
 * Original Inspired from ACF functionality - Adapted to work with MusicPres
 * @since 1.0
 */
function mup_get_post_id( $post_id )
	{
		// set post_id to global
		if( !$post_id )
		{
			global $post;
			
			if( $post )
			{
				$post_id = intval( $post->ID );
			}
		}
		
		
		// allow for option == options
		if( $post_id == "option" )
		{
			$post_id = "options";
		}
		
		
		// object
		if( is_object( $post_id ) )
		{
			if( isset( $post_id->roles, $post_id->ID ) )
			{
				$post_id = 'user_' . $post_id->ID;
			}
			elseif( isset( $post_id->taxonomy, $post_id->term_id ) )
			{
				$post_id = $post_id->taxonomy . '_' . $post_id->term_id;
			}
			elseif( isset( $post_id->ID ) )
			{
				$post_id = $post_id->ID;
			}
		}
		
		
		if( isset( $_GET['preview_id'] ) )
		{
			$autosave = wp_get_post_autosave( $_GET['preview_id'] );
			if( $autosave->post_parent == $post_id )
			{
				$post_id = intval( $autosave->ID );
			}
		}
		
		
		// return
		return $post_id;
	}

/**
 * Gets the requested gig field from the database
 * @since  1.0
 */
function mup_gig( $field_key, $post_id = false ) {
	
	$return = false;
	$post_id = mup_get_post_id( $post_id );
	$field = get_post_meta( $post_id, 'gig_meta', true );
	
	if( is_array( $field ) )
	{
		$return = $field[$field_key];
	}
	
	echo $return;
	 
}

/**
 * Gets the requested track field from the database
 * @since  1.0
 */
function mup_track( $field_key, $post_id = false ) {
	
	$return = false;
	$post_id = mup_get_post_id( $post_id );
	$field = get_post_meta( $post_id, 'track_meta', true );
	
	if( is_array( $field ) )
	{
		$return = $field[$field_key];
	}
	
	echo $return;
	 
}

/**
 * Gets the requested social field from the database
 * @since  1.0
 */
function mup_option( $field_key ) {
	
	$return = false;
	$field = get_option( 'musicpress_options' );
	
	if( is_array( $field ) )
	{
		$return = $field[$field_key];
	}
	
	echo $return;
	 
}

/**
 * Adds footer credit if the value is true 
 * @since  1.0
 */
$options = get_option( 'musicpress_options' );
$credit = $options['credit'];

if( TRUE === $credit ) {

	function mup_footer_credit() {
		$content = '<a href="https://wordpress.org/">Proudly powered by WordPress</a> &amp; <a href="http://musicpress.io">MusicPress</a>';
	  echo $content;
	}
	add_action('wp_footer', 'mup_footer_credit');

}


/**
 * Create the custom css file - We do it this way as inline styles aren't good practice
 * @since  1.0
 */
function mup_generate_css( $css_data ) {

	$data = $css_data;	
	$css_dir = get_stylesheet_directory() . '/'; 
	$mup_directory = plugin_dir_path( __FILE__ );
	ob_start(); 

	require( $mup_directory . 'mup-style.php' );

	$css = ob_get_clean(); 
	file_put_contents( $css_dir . 'musicpress-custom.css', $css, LOCK_EX ); 
}


/**
 * Register the custom css file the correct way 
 * @since  1.0
 */
function mup_enqueue_custom_css() {

	$mup_plugin_front_css = plugins_url( 'musicpress-free/assets/css/musicpress.css', 'musicpress' );
	wp_register_style( 'musicpress', $mup_plugin_front_css, 'style' );
	
 	wp_enqueue_style( 'musicpress' );

 	/**
	 * Check weather musicpress_css exists inside the database 
	 */
	$mup_has_style = get_option( 'musicpress_css' );
	if( !empty( $mup_has_style['styling'] ) ) { 
		wp_register_style( 'musicpress-custom', get_template_directory_uri() . '/musicpress-custom.css', 'style' );
		wp_enqueue_style( 'musicpress-custom' );
	
	}

 	
}
add_action( 'wp_print_styles', 'mup_enqueue_custom_css' );

/**
 * Register the MusicPress script on the theme page
 * @since  1.0
 */
function mup_enqueue_front_scripts() {

	if( !is_admin() ) {
		$mup_script_dir = plugins_url( 'musicpress-free/assets/js/musicpress.js', 'musicpress' );
		
		wp_register_script( 'musicpress', $mup_script_dir, 'jquery', 1.0 );
		wp_localize_script( 'musicpress', 'musicpressAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

		wp_register_script( 'musicpress_geo', 'http://www.geoplugin.net/javascript.gp', 'musicpress', 1.0 );
		

		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'musicpress' );
		wp_enqueue_script( 'musicpress_geo' );

		
	}
}
add_action( 'wp_print_scripts', 'mup_enqueue_front_scripts' );


/**
 *
 *	Shortcode functionality
 *	@since 1.0
 * 
 */

/**
 * Multiple shortcode - Gigs
 * @since  1.0
 */
function mup_show_all_gigs() {
	
	/**
	 * Query the database and return all the gigs 
	 */

	// Setup $paged
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

	$args = array(
		'post_type'			=>	'gig',
		'post_status'		=>	'publish',
		'paged'				=>	$paged,
		'meta_key' 			=> 'gig_date',
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

	if ( $gigs->have_posts() ) : ?> 
	
	<div class="mup-gigs">
	
		<?php while ( $gigs->have_posts() ) :  $gigs->the_post(); 

			// Get the date query ready 
			$today = date( 'Y-m-d' );

			$gig = get_the_id();

			$options = get_option( 'musicpress_options' );

			$gig_date 	= get_post_meta( $gig, 'gig_date', true );
			$gig_ticket = get_post_meta( $gig, 'gig_ticket_url', true );


			// Convert the date to human readable format
			$convert_date = new DateTime( $gig_date );
			$new_date = date_format( $convert_date,'M d Y' );//Change the format of date time

			?>	
			<div class="mup-gig">	
				<h3 class="title"><?php the_title(); ?></h3>
				<?php if( get_post_meta( get_the_id(), 'gig_address', true ) ) { ?><div class="address"><?php echo get_post_meta( get_the_id(), 'gig_address', true );?></div><?php } ?>
				<?php if( !empty( $new_date ) ) { ?><div><?php echo $new_date; ?></div><?php } // Ends date conditional ?>
				<?php if( get_post_meta( get_the_id(), 'gig_cost', true ) ) { ?><div class="cost"><?php echo $options['currency']; echo get_post_meta( get_the_id(), 'gig_cost', true );?></div><?php } else { _e( 'FREE' , 'mup' ); } ?>
				<?php if( !empty( $gig_ticket ) ) { ?><div class="ticket"><a href="<?php echo $gig_ticket; ?>"><?php echo __( 'Buy Tickets', 'mup' );?></a></div><?php } ?>
			</div>
			<div class="clear"></div>
		<?php endwhile; ?>
		<?php
				global $wp_query;

				$big = 999999999; // need an unlikely integer
				$translated = __( 'Page', 'mup' ); // Supply translatable string

				echo paginate_links( array(
					'base' 		=> str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
					'format' 	=> '?paged=%#%',
					'next_text'		=>	'>',
					'prev_text'		=>	'<',
					'current' 	=> max( 1, get_query_var('paged') ),
					'total' 	=> $gigs->max_num_pages,
				        'before_page_number' => '<span class="screen-reader-text">'.$translated.' </span>'
				) );
			?>
		<?php else: ?>
		<div class="mup-no-content">No gigs have been added yet.</div>
		</div>
		<?php endif;

}

add_shortcode( 'mup-gigs', 'mup_show_all_gigs' );


/**
 * Multiple shortcode - Tracks
 * @since  1.0
 */
function mup_show_all_tracks() {

	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

	$args = array(
		'post_type'			=>	'track',
		'post_status'		=>	'publish',
		'paged'				=>	$paged,
		'meta_key' 			=> 'track_release_date',
		'orderby'			=>	'meta_value',
		'order'				=>	'DESC',
		);

	$tracks = new WP_Query( $args );

	if ( $tracks->have_posts() ) : ?> 
	
	<div class="mup-tracks">
	
		<?php while ( $tracks->have_posts() ) :  $tracks->the_post(); 

			// Get the date query ready 
			$today = date( 'Y-m-d' );

			$track = get_the_id();

			$options = get_option( 'musicpress_options' );

			$release_date 	= get_post_meta( $track, 'track_release_date', true );
			$buy_link = get_post_meta( $track, 'track_buy_link', true );


			// Convert the date to human readable format
			$convert_date = new DateTime( $release_date );
			$new_date = date_format( $convert_date,'M d Y' );//Change the format of date time

			?>	
			<div class="mup-track">	
				<h3 class="title"><?php the_title(); ?></h3>
				<?php the_post_thumbnail(); ?>
				<?php if( !empty( $new_date ) ) { ?><div><?php echo $new_date; ?></div><?php } // Ends date conditional ?>
				<?php if( get_post_meta( get_the_id(), 'track_price', true ) ) { ?><div class="cost"><?php echo $options['currency']; echo get_post_meta( get_the_id(), 'track_price', true );?></div><?php } else { _e( 'FREE' , 'mup' ); } ?>
				<?php if( !empty( $buy_link ) ) { ?><div class="ticket"><a href="<?php echo $buy_link; ?>"><?php echo __( 'Buy Track', 'mup' );?></a></div><?php } ?>
			</div>
			<div class="clear"></div>
		<?php endwhile; ?>
		<?php
				global $wp_query;

				$big = 999999999; // need an unlikely integer
				$translated = __( 'Page', 'mup' ); // Supply translatable string

				echo paginate_links( array(
					'base' 		=> str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
					'format' 	=> '?paged=%#%',
					'next_text'		=>	'>',
					'prev_text'		=>	'<',
					'current' 	=> max( 1, get_query_var('paged') ),
					'total' 	=> $tracks->max_num_pages,
				        'before_page_number' => '<span class="screen-reader-text">'.$translated.' </span>'
				) );
			?>
		<?php else: ?>
		<div class="mup-no-content">No tracks have been added yet.</div>
		</div>
		<?php endif;

}
add_shortcode( 'mup-tracks', 'mup_show_all_tracks' );

/**
 * Singular shortcode - Gig
 * @since  1.0
 */
function mup_show_single_gig( $atts ) {

	// Set data vars ready
	$return_string = '';
	$options = get_option( 'musicpress_options' );

	$gig_id = shortcode_atts(array(
      'id' => 'Returning nothing as nothing valid was passed',
   ), $atts);

	// Validates against a number and reassigns if false
	if( !is_numeric( $gig_id['id'] ) ) {
		$gig_id['id'] = 'Non validated number entered';
	}

   $args = array( 
   	'post_type' => 'gig', 
   	'post_status' => 'publish', 
   	'posts_per_page' => 1, 
   	'p' => $gig_id['id']
   	);

   $single_gig = new WP_Query( $args );

   if ( $single_gig->have_posts()) {
   while ( $single_gig->have_posts()) : $single_gig->the_post(); 

    $gig = get_the_id(); 

    $today = date('Y-m-d');
	$gig_date 		= get_post_meta( $gig, 'gig_date', true );
	$gig_ticket 	= get_post_meta( $gig, 'gig_ticket_url', true );
	$gig_address 	= get_post_meta( $gig, 'gig_address', true );
	$gig_cost 		= get_post_meta( $gig, 'gig_cost', true );

	// Convert the date to human readable format
	$convert_date = new DateTime( $gig_date );
	$new_date = date_format( $convert_date,'M d Y' );//Change the format of date time

	/**
	 * Build the output
	 */
  	$return_string .= '<div class="mup-gig"><h3 class="title">'. get_the_title( $gig ) .'</h3>';			
	if( !empty( $gig_address ) ) {
		$return_string .= '	<div class="address">'. $gig_address .'</div>';
	} // Ends if address conditional
	if( !empty( $new_date ) ) {
		$return_string .= '<div>' . $new_date . '</div>';
	} // Ends date conditional 
	if( !empty( $gig_cost ) ) {
		$return_string .= '<div class="cost">' . $options['currency'] . '' . $gig_cost . '</div>';
	} else { _e( 'FREE' , 'mup' ); }
	if( !empty( $gig_ticket ) ) {
		$return_string .= '<div class="ticket"><a href="' . $gig_ticket .'">'. __( 'Tickets', 'mup' ) .'</a></div>';
	}
	$return_string .= '</div><div class="clear"></div>';
      
      endwhile;
   
   }
   else {
   		$return_string .= '<div class="mup-gig">'. __( 'Gig not found', 'mup' ) .'</div>';
   }

   // Reset the query for politeness
   wp_reset_query();
   return $return_string;
}
add_shortcode( 'mup-gig', 'mup_show_single_gig' );

/**
 * Singular shortcode - Track
 * @since  1.0
 */
function mup_show_single_track( $atts ) {

	// Set data vars ready
	$return_string = '';
	$options = get_option( 'musicpress_options' );

	$track_id = shortcode_atts(array(
      'id' => 'Returning nothing as nothing valid was passed',
   	), $atts);

	// Validates against a number and reassigns if false
	if( !is_numeric( $track_id['id'] ) ) {
		$track_id['id'] = 'Non validated number entered';
	}

   $track_args = array( 
   	'post_type' => 'track', 
   	'post_status' => 'publish', 
   	'posts_per_page' => 1, 
   	'p' => $track_id['id']
   	);

   $single_track = new WP_Query( $track_args );

   if ( $single_track->have_posts()) {
   while ( $single_track->have_posts()) : $single_track->the_post(); 

    $track = get_the_id(); 

    $today = date('Y-m-d');

	$track_release_date = get_post_meta( $track, 'track_release_date', true );
	$track_buy_link 	= get_post_meta( $track, 'track_buy_link', true );
	$track_soundcloud 	= get_post_meta( $track, 'track_soundcloud', true );
	$track_price	= get_post_meta( $track, 'track_price', true );
	$description 	= get_post_meta( $track, 'track_description', true );

	// Convert the date to human readable format
	$convert_date = new DateTime( $track_release_date );
	$new_date = date_format( $convert_date,'M d Y' ); //Change the format of date time

  	/**
  	 * Build the output
  	 */
  	$return_string .= '<div class="mup-track"><h3 class="title"><a href="' . get_the_permalink( $track ) . '">'. get_the_title( $track ) .'</a></h3>';
	if( !empty( $description ) ) {
		$return_string .= '<div class="description">' . $description . '</div>';
	}
	if( !empty( $new_date ) ) {
		$return_string .= '<div>' . $new_date . '</div>';
	} // Ends date conditional 
	if( !empty( $track_price ) ) {
		$return_string .= '<div class="track-price">' . $options['currency'] . '' . $track_price . '</div>';
	} else { _e( 'FREE' , 'mup' ); }
	if( !empty( $track_buy_link ) ) {
		$return_string .= '<div class="buy-link"><a href="' . $track_buy_link .'">'. __( 'Buy', 'mup' ) .'</a></div>';
	}
	if( !empty( $track_soundcloud ) ) {
		$return_string .= '<div class="SoundCloud"><a href="' . $track_soundcloud .'">'. __( 'Listen', 'mup' ) .'</a></div>';
	}
		$return_string .= '</div><div class="clear"></div>';
      
    endwhile;

   }
   else {
   		$return_string .= '<div class="mup-track">'. __( 'Track not found', 'mup' ) .'</div>';
   }

   // Reset the query for politeness
   wp_reset_query();
   return $return_string;

	
}
add_shortcode( 'mup-track', 'mup_show_single_track' );

/**
 * Shortcode - Social
 * @since  1.0
 */
function mup_social_shortcode( $atts ) {

	// Set data vars ready
	$social = '';
	$option = get_option( 'musicpress_options' );

	$link = shortcode_atts(array(
      'type' => 'Returning nothing as nothing valid was passed',
   	), $atts);

	// Validate that the type isn't long to cause hassle
   	if( strlen( $link['type'] ) > 11 ) {
   		$link['type'] = 'nothing';
   	}

	/**
	 * Check through each social network for type - Default to all if not valid or type not passed
	 */
	$social = '<ul class="mup-social-links">';
	if( $link['type'] == 'twitter' && $option['twitter'] != NULL ) {
		$social .= '<li><a href="' . $option['twitter'] . '">Twitter</a></li></ul>';
	}
	else if( $link['type'] == 'facebook' && $option['facebook'] != NULL ) {
		$social .= '<li><a href="' . $option['facebook'] . '">Facebook</a></li></ul>';
	}
	else if( $link['type'] == 'youtube' && $option['youtube'] != NULL ) {
		$social .= '<li><a href="' . $option['youtube'] . '">YouTube</a></li></ul>';
	}
	else if( $link['type'] == 'googleplus' && $option['googleplus'] != NULL ) {
		$social .= '<li><a href="' . $option['googleplus'] . '">Google Plus</a></li></ul>';
	}
	else if( $link['type'] == 'soundcloud' && $option['soundcloud'] != NULL ) {
		$social .= '<li><a href="' . $option['soundcloud'] . '">SoundCloud</a></li></ul>';
	}
	else if( $link['type'] == 'itunes' && $option['itunes'] != NULL ) {
		$social .= '<li><a href="' . $option['itunes'] . '">Itunes</a></li></ul>';
	}
	else {		
		if( $option['twitter'] != NULL ) { $social .= '<li><a href="' . $option['twitter'] . '">Twitter</a></li>'; }
		if( $option['facebook'] != NULL ) { $social .= '<li><a href="' . $option['facebook'] . '">Facebook</a></li>'; }
		if( $option['youtube'] != NULL ) { $social .= '<li><a href="' . $option['youtube'] . '">YouTube</a></li>'; }
		if( $option['googleplus'] != NULL ) { $social .= '<li><a href="' . $option['googleplus'] . '">Google Plus</a></li>'; }
		if( $option['soundcloud'] != NULL ) { $social .= '<li><a href="' . $option['soundcloud'] . '">SoundCloud</a></li>'; }
		if( $option['itunes'] != NULL ) { $social .= '<li><a href="' . $option['itunes'] . '">Itunes</a></li>'; }
		$social .= '</ul>';
	}

   	return $social;

}
add_shortcode( 'mup-social', 'mup_social_shortcode' );

/**
 *	Add custom post type content to the gig and track 
 *	@since  1.0
 */
function mup_add_to_content( $content ) {
	
	if( is_singular( 'track' ) && is_main_query() ) {

		global $post;

		$today = date( 'Y-m-d' );

		$track = get_the_id();

		$options = get_option( 'musicpress_options' );

		$release_date 	= get_post_meta( $track, 'track_release_date', true );
		$track_price	= get_post_meta( $track, 'track_price', true );
		$buy_link 		= get_post_meta( $track, 'track_buy_link', true );
		$description 	= get_post_meta( $track, 'track_description', true );
		$SoundCloud 	= get_post_meta( $track, 'track_soundcloud', true );

		// Convert the date to human readable format
		$convert_date = new DateTime( $release_date );
		$new_date = date_format( $convert_date,'M d Y' );//Change the format of date time
		
		/**
		 * Build the output
		 */
		$new_content = '';
		if( !empty( $description ) ) { 
			$new_content = '<div class="description">' . $description .'</div>';
		}
		$new_content .= '<div class="mup-track">';
		$new_content .= '<div class="mup-track-meta">';
		if( !empty( $new_date ) ) { 
			$new_content .= '<div>' . $new_date .'</div>';
		} // Ends date conditional	
		if( !empty( $track_price ) ) {
			$new_content .= '<div class="track-price">' . $options['currency'] .'' . $track_price .'</div>';
		} else { 
			$new_content .= '<div class="track-price">' . __( 'FREE' , 'mup' ) .'</div>'; 
		}// Ends price conditional
		$new_content .= '</div>';
		if( !empty( $buy_link ) ) { 
			$new_content .= '<div class="buy-link"><a href="'. $buy_link .'">' . __( 'Buy Track', 'mup' ) .'</a></div>';
		}
		if( !empty( $SoundCloud ) ) { 
			$new_content .= '<div class="buy-link"><a href="'. $SoundCloud .'">' . __( 'Listen', 'mup' ) .'</a></div>';
		}
		$new_content .= '</div><div class="clear"></div>';
		$content .= $new_content;

	}
	else if( is_singular( 'gig' ) && is_main_query() ) {

		global $post;

		$today = date( 'Y-m-d' );

		$gig = get_the_id();

		$options = get_option( 'musicpress_options' );

	    $today = date('Y-m-d');
		$gig_date 		= get_post_meta( $gig, 'gig_date', true );
		$gig_ticket 	= get_post_meta( $gig, 'gig_ticket_url', true );
		$gig_address 	= get_post_meta( $gig, 'gig_address', true );
		$gig_cost 		= get_post_meta( $gig, 'gig_cost', true );

		// Convert the date to human readable format
		$convert_date = new DateTime( $gig_date );
		$new_date = date_format( $convert_date,'M d Y' );//Change the format of date time

		/**
		 * Build the output
		 */
		$new_content = '';
	  	$new_content .= '<div class="mup-gig"><h3 class="title">'. get_the_title( $gig ) .'</h3>';			
		if( !empty( $gig_address ) ) {
			$new_content .= '	<div class="address">'. $gig_address .'</div>';
		} // Ends if address conditional
		if( !empty( $new_date ) ) {
			$new_content .= '<div>' . $new_date . '</div>';
		} // Ends date conditional 
		if( !empty( $gig_cost ) ) {
			$new_content .= '<div class="cost">' . $options['currency'] . '' . $gig_cost . '</div>';
		} else { _e( 'FREE' , 'mup' ); }
		if( !empty( $gig_ticket ) ) {
			$new_content .= '<div class="ticket"><a href="' . $gig_ticket .'">'. __( 'Tickets', 'mup' ) .'</a></div>';
		}
		$new_content .= '</div><div class="clear"></div>';

		$content .= $new_content;
	}

	return $content;
}

add_filter( 'the_content', 'mup_add_to_content', 20 );
