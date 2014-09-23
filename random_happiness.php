<?php
/**
 * @package random_happines
 * @version 1.0
 */
/*
Plugin Name: Random Happiness
Plugin URI: http://wordpress.org/extend/plugins/random-happiness/
Description: This is just a plugin, it provides random happy thoughts in the upper right of your admin screen on every page.
Author: Greg Hewitt-Long
Version: 1.0
Author URI: http://www.webyourbusiness.com/


Release Notes:

1.0 - Initial release
*/
$happy_library='';

function random_happiness_get_thought() {
$dir = plugin_dir_path( __FILE__ );
$happy_thoughts_files = array_values( preg_grep( '/^((?!index.php).)*$/', glob($dir."inc/happy*.inc.php") ) );
$use_this_happy_thoughts_file = $happy_thoughts_files[mt_rand(0, count($happy_thoughts_files) -1)];
include_once($use_this_happy_thoughts_file);
	// Here we split it into lines
	$happy_thoughts = explode( "\n", $happy_thoughts );

	// And then randomly choose a line
	$happy_thought_array[0] = wptexturize($happy_thoughts[0]);
	$happy_thought_array[1] = wptexturize($happy_thoughts[ mt_rand( 1, count( $happy_thoughts ) - 1 ) ]);
	return ( $happy_thought_array );
//	return wptexturize( $happy_thoughts[ mt_rand( 1, count( $happy_thoughts ) - 1 ) ] );
}

// This just echoes the chosen line, we'll position it later
function happy_thoughts() {
	global $happy_library;
	$chosen_array = random_happiness_get_thought();
	$happy_library = $chosen_array[0].': ';
	$happy_thought_now = $chosen_array[1];

	echo "<p id='happythought'><em>$happy_library</em>$happy_thought_now</p>";
}

// Now we set that function up to execute when the admin_notices action is called
add_action( 'admin_notices', 'happy_thoughts' );

// We need some CSS to position the paragraph
function happines_css() {
	// This makes sure that the positioning is also good for right-to-left languages
	$x = is_rtl() ? 'left' : 'right';

	echo "
	<style type='text/css'>
	#happythought {
		float: $x;
		padding-$x: 15px;
		padding-top: 5px;		
		margin: 0;
		font-size: 11px;
	}
	</style>
	";
}

add_action( 'admin_head', 'happines_css' );

// This code adds the links in the settings section of the plugin
if ( ! function_exists( 'random_happiness_plugin_meta' ) ) :
        function random_happiness_plugin_meta( $links, $file ) {
                if ( strpos( $file, 'random_happiness.php' ) !== false ) {
                        $links = array_merge( $links, array( '<a href="http://wordpress.org/support/view/plugin-reviews/random-happiness#postform" title="Review-Random-Happiness">Please Review Random Happiness</a>' ) );
                        $links = array_merge( $links, array( '<a href="http://wordpress.org/support/plugin/random-happiness" title="Support-for-Random Happiness">Support</a>' ) );
                }
                return $links;
        }
        add_filter( 'plugin_row_meta', 'random_happiness_plugin_meta', 12, 2 );
endif; // end of post_edit_toolbar_plugin_meta()

?>
