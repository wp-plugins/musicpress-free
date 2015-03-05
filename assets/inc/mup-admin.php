<?php if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 *
 *	Loads all admin pages functionality
 * 
 */


/**
 * Include the admin scripts
 * @since  1.0
 */
function mup_add_admin_scripts() {


    wp_enqueue_script( 'jquery-ui-tabs' );
    wp_register_style( 'mup_admin_css', plugins_url( 'musicpress-free' ) . '/assets/css/mup-admin-styling.css', false, '1.0' );
    wp_enqueue_style( 'mup_admin_css' );

    wp_register_script( 'mup_admin_script', plugins_url( 'musicpress-free' ) . '/assets/js/mup-admin-script.js', 'jquery', '1.0', true );
    wp_localize_script( 'mup_admin_script', 'mupAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ), 'nonce' => wp_create_nonce( 'mup_email_nonce' ) ) );
    wp_enqueue_script('mup_admin_script' );

}

/**
 *  Register the settings pages and build the tab navigation
 *  @since  1.0
 */
function mup_register_admin_options() {

  /**
   * Build the settings sections 
   * @since  1.0
   */

  /**
   * Register the various settings
   */
  register_setting( 'musicpress_options', 'musicpress_options', 'musicpress_options_validation' );
  register_setting( 'musicpress_css', 'musicpress_css',  'musicpress_css_validation');
  register_setting( 'musicpress_email', 'musicpress_email',  'musicpress_email_validation');

  /**
   * Add MusicPress main settings section
   */
  add_settings_section( 'musicpress_social_section', '', 'musicpress_no_callback', 'musicpress_social' );
  add_settings_section( 'musicpress_general_section', '', 'musicpress_no_callback', 'musicpress_general' );
  add_settings_section( 'musicpress_css_section', '', 'musicpress_no_callback', 'musicpress_css' );
  add_settings_section( 'musicpress_email_section', '', 'musicpress_no_callback', 'musicpress_email' );

  /**
   * Add the individual social settings fields
   */
  add_settings_field( 'twitter', __( 'Twitter URL', 'mup' ), 'mup_twitter_setting_input', 'musicpress_social', 'musicpress_social_section' );
  add_settings_field( 'facebook', __( 'Facebook URL', 'mup' ), 'mup_facebook_setting_input', 'musicpress_social', 'musicpress_social_section' );
  add_settings_field( 'youtube', __( 'YouTube URL', 'mup' ), 'mup_youtube_setting_input', 'musicpress_social', 'musicpress_social_section' );
  add_settings_field( 'googleplus', __( 'Google Plus URL', 'mup' ), 'mup_google_plus_setting_input', 'musicpress_social', 'musicpress_social_section' );
  add_settings_field( 'soundcloud', __( 'SoundCloud URL', 'mup' ), 'mup_soundcloud_setting_input', 'musicpress_social', 'musicpress_social_section' );
  add_settings_field( 'itunes', __( 'itunes URL', 'mup' ), 'mup_itunes_setting_input', 'musicpress_social', 'musicpress_social_section' );

  /**
   * Add the individual general settings fields
   */
  add_settings_field( 'currency', __( 'Currency Symbol', 'mup' ), 'mup_currency_setting_input', 'musicpress_general', 'musicpress_general_section' );
  add_settings_field( 'credit', __( 'MusicPress Footer Credit', 'mup' ), 'mup_credit_link', 'musicpress_general', 'musicpress_general_section' );

  add_settings_field( 'styling', '', 'mup_css_textarea', 'musicpress_css', 'musicpress_css_section' );
  add_settings_field( 'subject', '', 'mup_email_subject', 'musicpress_email', 'musicpress_email_section' );
  add_settings_field( 'content', '', 'mup_email_textarea', 'musicpress_email', 'musicpress_email_section' );
  

}

add_action( 'admin_init', 'mup_register_admin_options' );


function musicpress_no_callback() { 
 
  // Nothing here - just an empty callback to allow consistency throughout the plugin   
}

function musicpress_options_validation( $input ) {
  
  $valid = array();

  // Sanitize each text URL input
  $valid['twitter']     = sanitize_text_field( $input['twitter'] );
  $valid['facebook']    = sanitize_text_field( $input['facebook'] );
  $valid['youtube']     = sanitize_text_field( $input['youtube'] );
  $valid['googleplus']  = sanitize_text_field( $input['googleplus'] );
  $valid['soundcloud']  = sanitize_text_field( $input['soundcloud'] );
  $valid['itunes']      = sanitize_text_field( $input['itunes'] );

  // Sanitize the Checkboxes 
  $valid['currency']   = esc_html( $input['currency'] );
  $valid['credit']     = esc_html( $input['credit'] );

  
  // Validate each input
  if( $valid['twitter'] != $input['twitter'] ) {
    add_settings_error( 'twitter', 'musicpress_texterror', __( 'Twitter URL: Incorrect URL format', 'mup' ), 'error' );
  }
  elseif( substr( $valid['twitter'], 0, 3 ) === "ftp" || substr( $valid['twitter'], 0, 7 ) === "mailto:" || substr( $valid['twitter'], 0, 2 ) === "ws" || substr( $valid['twitter'], 0, 3 ) === "xml"  ) {
    add_settings_error( 'twitter', 'musicpress_texterror', __( 'Twitter URL: Only http or https allowed', 'mup' ), 'error' );
  }

  if( $valid['facebook'] != $input['facebook'] ) {
    add_settings_error( 'facebook', 'musicpress_texterror', __( 'Facebook URL: Incorrect URL format', 'mup' ), 'error' );    
  }
  elseif( substr( $valid['facebook'], 0, 3 ) == "ftp" || substr( $valid['facebook'], 0, 7 ) == "mailto:" || substr( $valid['facebook'], 0, 2 ) === "ws" || substr( $valid['facebook'], 0, 3 ) === "xml" ) {
    add_settings_error( 'facebook', 'musicpress_texterror', __( 'Facebook URL: Only http or https allowed', 'mup' ), 'error' );
  }

  if( $valid['youtube'] != $input['youtube'] ) {
    add_settings_error( 'youtube', 'musicpress_texterror', __( 'YouTube URL: Incorrect URL format', 'mup' ), 'error' );    
  }
  elseif( substr( $valid['youtube'], 0, 3 ) == "ftp" || substr( $valid['youtube'], 0, 7 ) == "mailto:" || substr( $valid['youtube'], 0, 2 ) === "ws" || substr( $valid['youtube'], 0, 3 ) === "xml" ) {
    add_settings_error( 'youtube', 'musicpress_texterror', __( 'YouTube URL: Only http or https allowed', 'mup' ), 'error' );
  }

  if( $valid['googleplus'] != $input['googleplus'] ) {
    add_settings_error( 'googleplus', 'musicpress_texterror', __( 'Google Plus URL: Incorrect URL format', 'mup' ), 'error' );    
  }
  elseif( substr( $valid['googleplus'], 0, 3 ) == "ftp" || substr( $valid['googleplus'], 0, 7 ) == "mailto:" || substr( $valid['googleplus'], 0, 2 ) === "ws" || substr( $valid['googleplus'], 0, 3 ) === "xml" ) {
    add_settings_error( 'googleplus', 'musicpress_texterror', __( 'Google Plus URL: Only http or https allowed', 'mup' ), 'error' );
  }

  if( $valid['soundcloud'] != $input['soundcloud'] ) {
    add_settings_error( 'soundcloud', 'musicpress_texterror', __( 'SoundCloud URL: Incorrect URL format', 'mup' ), 'error' );    
  }
  elseif( substr( $valid['soundcloud'], 0, 3 ) == "ftp" || substr( $valid['soundcloud'], 0, 7 ) == "mailto:" || substr( $valid['soundcloud'], 0, 2 ) === "ws" || substr( $valid['soundcloud'], 0, 3 ) === "xml" ) {
    add_settings_error( 'soundcloud', 'musicpress_texterror', __( 'SoundCloud URL: Only http or https allowed', 'mup' ), 'error' );
  }

  if( $valid['itunes'] != $input['itunes'] ) {
    add_settings_error( 'itunes', 'musicpress_texterror', __( 'iTunes URL: Incorrect URL format', 'mup' ), 'error' );    
  }
  elseif( substr( $valid['itunes'], 0, 3 ) == "ftp" || substr( $valid['itunes'], 0, 7 ) == "mailto:" || substr( $valid['itunes'], 0, 2 ) === "ws" || substr( $valid['itunes'], 0, 3 ) === "xml" ) {
    add_settings_error( 'itunes', 'musicpress_texterror', __( 'iTunes URL: Only http or https allowed', 'mup' ), 'error' );
  }

  if( strlen( $valid['currency'] ) != 1 ) { add_settings_error( 'currency', 'musicpress_texterror', __( 'Too many characters in currency symbol', 'mup' ), 'error' ); } 
  if( TRUE == $valid['credit'] ) { $valid['credit'] = '1'; }
  
  return apply_filters( 'musicpress_options_validation', $valid, $input );
}

/**
 * CSS validation  
 * @since 1.0
 */
function musicpress_css_validation( $input ) {
    
    if ( !empty( $input['styling'] ) )
        
        $input['styling'] = esc_textarea( trim( $input['styling'] ) );

        $css_data = $input['styling'];
        add_settings_error( 'styling', 'settings_updated', __( 'CSS saved', 'mup' ), 'updated' );
    
    return apply_filters( 'musicpress_css_validation', $input );
}

/**
 * Email saving Validtion
 * @since  1.0
 */
function musicpress_email_validation( $input ) {

   if ( !empty( $input['content'] ) )
        
        $input['content'] = esc_textarea( stripslashes( $input['content'] ) );
        add_settings_error( 'content', 'settings_updated', __( 'Email saved', 'mup' ), 'updated' );
    
    return apply_filters( 'musicpress_email_validation', $input );

}


/**
 * Show each field as per the add_settings_field function - Validation of field underneath
 * @since  1.0
 */
function mup_twitter_setting_input() {

  $options = get_option( 'musicpress_options' );
  echo '<input type="text" class="widefat mup-field-text" id="twitter" name="musicpress_options[twitter]" value="'. esc_url( $options['twitter'] ) .'" />';

}

function mup_facebook_setting_input() {

  $options = get_option( 'musicpress_options' );
  echo '<input type="text" class="widefat mup-field-text" id="facebook" name="musicpress_options[facebook]" value="'. esc_url( $options['facebook'] ) .'" />';

}

function mup_youtube_setting_input() {

  $options = get_option( 'musicpress_options' );
  echo '<input type="text" class="widefat mup-field-text" id="youtube" name="musicpress_options[youtube]" value="'. esc_url( $options['youtube'] ) .'" />';

}

function mup_google_plus_setting_input() {

  $options = get_option( 'musicpress_options' );
  echo '<input type="text" class="widefat mup-field-text" id="googleplus" name="musicpress_options[googleplus]" value="'. esc_url( $options['googleplus'] ) .'" />';

}

function mup_soundcloud_setting_input() {

  $options = get_option( 'musicpress_options' );
  echo '<input type="text" class="widefat mup-field-text" id="soundcloud" name="musicpress_options[soundcloud]" value="'. esc_url( $options['soundcloud'] ) .'" />';

}

function mup_itunes_setting_input() {

  $options = get_option( 'musicpress_options' );
  echo '<input type="text" class="widefat mup-field-text" id="itunes" name="musicpress_options[itunes]" value="'. esc_url( $options['itunes'] ) .'" />';

}

function mup_currency_setting_input() {

  $options = get_option( 'musicpress_options' );
  echo '<input type="text" class="small-text mup-field-text" id="currency" maxlength="1" name="musicpress_options[currency]" value="'. esc_html( $options['currency'] ) .'" />';

}

function mup_credit_link() {

  $options = get_option( 'musicpress_options' );
  echo '<input name="musicpress_options[credit]" id="credit" type="checkbox" value="1" class="code" ' . checked( 1, $options['credit'], false ) . ' />';

}

/**
 * Show the Main page with tabs
 * @since 1.0
 */
function mup_show_welcome_page() {
  
  if (!current_user_can('manage_options')) {
        wp_die( __( 'You do not have sufficient permissions to access this page.', 'mup' ) );
    }
    ?>
    
    <div class="wrap about-wrap">
      <?php settings_errors(); ?>
        <h1>MusicPress</h1>
        <div class="about-text">
          <?php echo  __( 'Thanks for downloading! MusicPress helps you list your gigs, tracks and notify your fans. ', 'mup' ); ?>
          <a href="http://musicpress.io/" target="_blank"><?php __( 'Upgrade to the PRO version', 'mup' ); ?></a>
        </div>
        <a href="http://musicpress.io/"><div class="wp-badge"><?php echo __( 'Version', 'mup' );?> <?php echo MUP_VERSION; ?></div></a>

          <?php  $active = 'welcome'; if( isset( $_GET['tab'] ) ) { $active =   $_GET['tab'];  }  ?>

        <h2 class="nav-tab-wrapper">
          <a href="?page=musicpress&tab=welcome" class="nav-tab <?php echo 'welcome' == $active ? 'nav-tab-active' : ''; ?>"><?php echo __( 'Welcome', 'mup' );?></a>
          <a href="?page=musicpress&tab=settings" class="nav-tab <?php echo 'settings' == $active ? 'nav-tab-active' : ''; ?>"><?php echo __( 'Settings', 'mup' );?></a>
          <a href="?page=musicpress&tab=styling" class="nav-tab <?php echo 'styling' == $active ? 'nav-tab-active' : ''; ?>"><?php echo __( 'Custom Styling', 'mup' );?></a>
          <a href="?page=musicpress&tab=fan-email" class="nav-tab <?php echo 'fan-email' == $active ? 'nav-tab-active' : ''; ?>"><?php echo __( 'Email your Fans', 'mup' );?></a>
        </h2>
      <?php 

        if( 'welcome' == $active ) {
        // Show the tour video and some basic faq links to website on the right
        ?>
                
            <div class="headline-feature">
              <h2><?php esc_attr_e( 'Built For Musicians And Performers', 'mup' );?></h2>
              <div class="featured-image">
                <img src="<?php echo plugins_url( 'musicpress-free' ); ?>/assets/img/musicpress-admin-feature-intro.png">
              </div>
              <h2><?php esc_attr_e( 'What You Get', 'mup' );?></h2>
                <div class="feature-section col two-col">
                    <div>
                        <h3><?php echo __( 'Your Music, Your Performance', 'mup' ); ?></h3>  
                        <p><?php echo __( 'MusicPress helps you show your tracks, gigs, podcasts, performances and social networks. MusicPress has been built to be easy to use without the need for hassle.', 'mup' ); ?></p>
                    </div>
                    <div class="last-feature">
                      <h3><?php echo __( 'Fan Subscription', 'mup' ); ?></h3>  
                        <p><?php echo __( 'Email fans directly within MusicPress keeping them informed of your activities. Build your fan base using the subscription widget.', 'mup' ); ?></p>
                    </div>
                    <div>
                        <h3><?php echo __( 'Widget Ready', 'mup' ); ?></h3>  
                        <p><?php echo __( 'Drag and drop custom MusicPress widgets directly onto your page templates through the widgets section of WordPress. Pull out specific gigs, tracks, and social links.', 'mup' ); ?></p>
                    </div>
                    <div class="last-feature">
                      <h3><?php echo __( 'Your Language', 'mup' ); ?></h3>  
                        <p><?php echo __( 'MusicPress currently translates into English, Chinese, French, Spanish and Russian with more languages being added with each new update. Let us know if you need a language.', 'mup' ); ?></p>
                    </div>
                  </div>
                <div class="feature-section col two-col">
                  <h2><?php esc_attr_e( 'Pro Features Coming Soon', 'mup' );?></h2>
                    
                </div>
                                    
      <br class="clear">
      <?php }

       else if( 'settings' == $active ) { 
        // Do the settings pages
        ?>
        <div id="poststuff">
        
        <form action="options.php" method="post">
        <div id="post-body" class="metabox-holder columns-2">
    
        <!-- main content -->
        <div id="post-body-content">
          
          <div class="meta-box-sortables ui-sortable">
            <h2><?php echo __( 'Social Links', 'mup' ); ?></h2>
            <div class="postbox">
            
              <div class="inside">
                
                <?php settings_fields( 'musicpress_options' ); ?>
                <?php do_settings_sections( 'musicpress_social' ); ?>   
              </div> <!-- .inside -->
            
            </div> <!-- .postbox -->

            <h2><?php echo __( 'General Settings', 'mup' );?></h2>
            <div class="postbox">
            
              <div class="inside">
                
                <?php settings_fields( 'musicpress_options' ); ?>
                <?php do_settings_sections( 'musicpress_general' ); ?>   
                
              </div> <!-- .inside -->
            
            </div> <!-- .postbox -->
            
          </div> <!-- .meta-box-sortables .ui-sortable -->
          
        </div> <!-- post-body-content -->

        <!-- sidebar -->
        <div id="postbox-container-1" class="postbox-container">
          
          <div class="meta-box-sortables">
            <h2><?php _e( 'Help', 'mup' ); ?></h2>
              <h4><?php _e( 'Shortcodes', 'mup' ); ?></h4>
              <?php _e( 'Here are the shortcodes you can use inside your content:', 'mup' ); ?>
              <br><br>
              <strong>[mup-gigs]</strong> <?php _e( 'Show all your gigs and style up using the custom css panel.', 'mup' ); ?>
              <br><br>
              <strong>[mup-gig id="GIG ID"]</strong> <?php _e( 'List a single gig.', 'mup' ); ?>
              <br><br>
              <strong>[mup-tracks]</strong> <?php _e( 'Show all your tracks and style up using the custom css panel.', 'mup' ); ?>
              <br><br>
              <strong>[mup-track id="TRACK ID"]</strong> <?php _e( 'List a single track and all its extra content.', 'mup' ); ?>
              <br><br>
              <strong>[mup-social]</strong> <?php _e( 'Show all your social networks in one go.', 'mup' ); ?>
              <br><br>
              <strong>[mup-social type="TYPE"]</strong> <?php _e( 'Show a single social network link in your content.', 'mup' ); ?>
              <br><br>
              <h4><?php _e( 'Functions', 'mup' ); ?></h4>
              <div class="inside">
              <?php echo __( 'The following functions can be used in your templates:', 'mup' );?>
              <br><br>
              <strong>mup_option('type');</strong> <?php echo __( 'Social urls can be linked to by using this.', 'mup' ); ?>
              <br><br>
              <strong>mup_gig('');</strong> <?php echo __( 'Pull out individual gig information using this function.', 'mup' ); ?>
              <br><br>
              <strong>mup_track('')</strong> <?php echo __( 'Pull out individual track information using this function.', 'mup' ); ?>
              <br><br>

               

              </div> <!-- .inside -->
              
            
          </div> <!-- .meta-box-sortables -->
          
        </div> <!-- #postbox-container-1 .postbox-container -->
        <?php submit_button(); ?>
        
      </div> <!-- #post-body .metabox-holder .columns-2 -->
      
      <br class="clear">   
        
      </form>
       <?php } 
       else if( 'styling' == $active ) {
        $css_data = NULL;
        // Update musicpress-style.css once stored
        if( isset( $_GET['settings-updated'] ) && TRUE == $_GET['settings-updated'] ) {
           mup_generate_css( $css_data );
        }
        ?>

        <div id="poststuff">
        <form action="options.php" method="post">
        <div id="post-body" class="metabox-holder columns-2">
    
        <!-- main content -->
        <div id="post-body-content">
          
          <div class="meta-box-sortables ui-sortable">
            
            <h2><?php echo __( 'Custom CSS', 'mup' ); ?></h2>
            <p><?php echo __( 'Here you can overwrite the default MusicPress styling. Please see the musicpress-custom.css stylesheet in your theme folder once you click save at the bottom.', 'mup' ); ?></p>
            <div class="postbox">
            
              <div class="inside">
                 <?php $options = get_option( 'musicpress_css' ); ?>

                <?php settings_fields( 'musicpress_css' ); ?>
                <textarea class="widefat" id="styling" name="musicpress_css[styling]" cols="80" rows="29" class="large-text"><?php echo $options['styling']; ?></textarea>
                
              </div> <!-- .inside -->
            
            </div> <!-- .postbox -->

            <?php submit_button(); ?>   
            
          </div> <!-- .meta-box-sortables .ui-sortable -->
          
        </div> <!-- post-body-content -->

        <!-- sidebar -->
        <div id="postbox-container-1" class="postbox-container">
          
          <div class="meta-box-sortables">
            
            
              <h2><?php echo __( 'CSS Guide', 'mup' );?></h2>
              <div class="inside">
               <?php echo __( 'Here\'s a list of the most common selectors', 'mup' );?>
               <ul>
                   <div class="sub-title"><?php echo __( 'Gigs', 'mup' );?></div>
                <li>.mup-gigs {}</li>
                <li>.mup-gig {}</li>
                <li>.mup-gig .venue {}</li>
                <li>.mup-gig .address {}</li>
                <li>.mup-gig .cost {}</li>
                <li>.mup-gig .ticket {}</li>
               </ul>
               <ul>
                   <div class="sub-title"><?php echo __( 'Tracks', 'mup' );?></div>
                <li>.mup-tracks {}</li>
                <li>.mup-track {}</li>
                <li>.mup-track .name {}</li>
                <li>.mup-track .description {}</li>
                <li>.mup-track .release-date {}</li>
                <li>.mup-track .price {}</li>
                <li>.mup-track .soundcloud {}</li>
               </ul>
               <ul>
                   <div class="sub-title"><?php echo __( 'Social', 'mup' );?></div>
                <li>.mup-social {}</li>
                <li>.mup-social .twitter {}</li>
                <li>.mup-social .facebook {}</li>
                <li>.mup-social .youtube {}</li>
                <li>.mup-social .google-plus {}</li>
                <li>.mup-social .itunes {}</li>
                <li>.mup-social .soundcloud {}</li>
               </ul>
              </div> <!-- .inside -->
              
            
          </div> <!-- .meta-box-sortables -->
          
        </div> <!-- #postbox-container-1 .postbox-container -->
        
      </div> <!-- #post-body .metabox-holder .columns-2 -->
      
      <br class="clear">   

       <?php
       }
       else if( 'fan-email' == $active ) { ?>


        <div id="poststuff">
        
        <div id="post-body" class="metabox-holder columns-2">
    
        <!-- main content -->
        <div id="post-body-content">
          
          <div class="meta-box-sortables ui-sortable">
            
            <h2><?php echo __( 'Email', 'mup' ); ?></h2>
            <p><?php echo __( 'Here you can add plain text and email your fanbase. MusicPress will store the email so you can use it again for future use. When you are ready to send your email press the Send Email button at the bottom.', 'mup' ); ?></p>
          <div class="mup-notify">
            <p><?php _e( 'Please do not close this window while emails are being sent!', 'mup' ); ?></p>
          </div>
            <div class="postbox mup-sending">
            <div class="mup-send-email">
              <div class="inside">
                <h2>Sending mail...</h2> 
                <div class="mup-field-progress"> 
                  <progress id="mup-progressbar" value="0" max="<?php echo mup_get_fan_count(); ?>"></progress>
                </div>
            </div>
      </div>
    </div>
        <div class="send-email-success">
          <p class="message"><?php _e( 'Emails successfully sent.', 'mup' ); ?></p> 
          </div> 
          <div class="send-email-error">
          <p class="message"><?php _e( 'Emails could not be sent.', 'mup' ); ?></p> 
          </div> 
          <div class="mup-email-window">
          <div class="postbox">
          <div class="mup-email-content">
              <div class="inside">
                <form action="options.php" method="post" id="mup-email-form">
                  <?php $saved = get_option( 'musicpress_email' ); ?>
                  <?php settings_fields( 'musicpress_email' ); ?>
                  <div class="mup-field-label">Subject heading</div>
                  <input type="text" class="widefat mup-field-text" id="subject" name="musicpress_email[subject]" value="<?php echo $saved['subject']; ?>">
                  <textarea class="widefat large-text" id="content" name="musicpress_email[content]"  rows="29"><?php echo $saved['content']; ?></textarea>
              </div> <!-- .inside -->
              </div> <!-- .postbox -->
            </div>
                <input type="submit" name="save-email" id="save-email" class="button button-primary" style="float:left;" value="<?php esc_attr_e( 'Save Email', 'mup' ); ?>">  
              </form>  
                <input type="hidden" name="mup_email_fans_nonce" id="mup_email_fans_nonce" value="<?php echo wp_create_nonce( 'mup_email_fans_nonce' ); ?>" />       
                <input type="hidden" name="musicpress_email[content]" value="<?php esc_html_e( $saved['saved'] ); ?>">
                <input type="submit" name="mup-email-fans" id="mup-email-fans" class="button button-musicpress" value="<?php esc_attr_e( 'Send This Email', 'mup' ); ?>"> 
              </div>
          </div> <!-- Ends mup-send-email -->                  

         </div> <!-- post-body-content -->  

        <!-- sidebar -->
        <div id="postbox-container-1" class="postbox-container">
          
          <div class="meta-box-sortables">
          
              <h2><?php _e( 'Email Rules', 'mup' );?></h2>
              <div class="inside">
               <?php _e( 'Please check with your hosting supplier that you can send bulk emails.', 'mup' ); ?>          
              </div> <!-- .inside -->
            <br class="clear">  
            <div class="postbox">
              <div class="inside test-box">
                <h2><?php _e( 'Test Email', 'mup' );?></h2>
                <p><?php _e( 'Enter a valid email address to get a preview in your inbox.', 'mup' ); ?></p>
                  <input type="email" name="mup-test-email" id="mup-test-email" class="widefat mup-field-text" required/>
                  <br class="clear"><br>
                  <input type="submit" name="mup-test" id="mup-test" class="button button-secondary" value="<?php esc_attr_e( 'Send Preview', 'mup' ); ?>">
                  <input type="hidden" name="action" value="mup_send_test_email" />
                  <input type="hidden" name="mup_test_email_nonce" id="mup_test_email_nonce" value="<?php echo wp_create_nonce( 'mup_email_nonce' ); ?>" />
                  <div class="spinner"></div>
              </div>
            </div>          
          </div> <!-- .meta-box-sortables -->
          <div class="test-email-success">
            <p class="message"><?php _e( 'Please check your inbox.', 'mup' ); ?></p> 
          </div> 
          <div class="test-email-error">
            <p class="message"><?php _e( 'Email could not be sent. Please check your email settings.', 'mup' ); ?></p> 
          </div> 
          
        </div> <!-- #postbox-container-1 .postbox-container -->
        
      </div> <!-- #post-body .metabox-holder .columns-2 -->
      
      <br class="clear">  

       <?php } ?>
    
    </div> 
    
    </div> <!-- #poststuff -->

<?php

}


/**
 * Show the fans page
 * @since 1.0
 */
function mup_display_fans_admin() {

    if ( !current_user_can( 'manage_options' ) ) {
        wp_die( __( 'You do not have sufficient permissions to access this page.', 'mup' ) );
    }

    echo '<div class="wrap about-wrap"><h1>'. __( 'MusicPress Fans' ) .'</h1></div>';

}
