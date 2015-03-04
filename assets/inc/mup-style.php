<?php
echo '

/**
 * MusicPress Custom Styling
 */

';

$options = get_option( 'musicpress_css', 'none' );

echo $options['styling'];