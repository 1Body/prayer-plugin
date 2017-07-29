<?php
/*
Plugin Name: PrayerList Plugin
Description: React.JS Prayers in WordPress!
Author: Alice
Author URI: aplai168@gmail.com
*/
global $wpdb;
$posts = $wpdb->get_results("SELECT ID, post_title FROM $wpdb->posts WHERE post_status = 'publish'
AND post_type='post' ORDER BY comment_count DESC LIMIT 0,4");
$prayercards = $wpdb->get_results("SELECT * FROM prayer_cards WHERE id = 1", OBJECT);
$results = $wpdb->get_results( 'SELECT * FROM wp_options WHERE option_id = 1', OBJECT );
   // Echo the title of the first scheduled post
   echo $posts[0]->post_title;
	 echo $prayercards[0]->name;
	 echo $results[0]->option_name;


wp_enqueue_script( 'read.php', plugin_dir_url( __FILE__ ) . 'api/config/core.php' );

wp_enqueue_script( 'read.php', plugin_dir_url( __FILE__ ) . 'api/category/read.php' );
wp_enqueue_script( 'read.php', plugin_dir_url( __FILE__ ) . 'api/config/database.php' );
wp_enqueue_script( 'read.php', plugin_dir_url( __FILE__ ) . 'api/objects/category.php' );
wp_enqueue_script( 'read.php', plugin_dir_url( __FILE__ ) . 'api/objects/product.php' );
wp_enqueue_script( 'read.php', plugin_dir_url( __FILE__ ) . 'api/product/create.php' );
wp_enqueue_script( 'read.php', plugin_dir_url( __FILE__ ) . 'api/product/delete.php' );
wp_enqueue_script( 'read.php', plugin_dir_url( __FILE__ ) . 'api/product/read_one.php' );
wp_enqueue_script( 'read.php', plugin_dir_url( __FILE__ ) . 'api/product/read_paging.php' );
wp_enqueue_script( 'read.php', plugin_dir_url( __FILE__ ) . 'api/product/read.php' );
wp_enqueue_script( 'read.php', plugin_dir_url( __FILE__ ) . 'api/product/search.php' );
wp_enqueue_script( 'read.php', plugin_dir_url( __FILE__ ) . 'api/product/update.php' );
wp_enqueue_script( 'read.php', plugin_dir_url( __FILE__ ) . 'api/shared/utilities.php' );
add_shortcode( 'prayer', 'prayer_function' );
function prayer_function() {
	return '<div id="quiz">here is the prayer plugin</div>';
}

add_action( 'wp_enqueue_scripts', 'wpshout_react_quiz_enqueue_scripts' );
function wpshout_react_quiz_enqueue_scripts() {
	// if( ! is_single( 10742 ) ) {
	// 	return;
	// }

	wp_enqueue_script( 'react', plugin_dir_url( __FILE__ ) . 'react/build/react.js' );
	wp_enqueue_script( 'react-dom', plugin_dir_url( __FILE__ ) . 'react/build/react-dom.min.js' );
	wp_enqueue_script( 'babel', 'https://npmcdn.com/babel-core@5.8.38/browser.min.js', '', null, false );
	wp_enqueue_script( 'style', plugin_dir_url( __FILE__ ) . 'src/style.js' );
	wp_enqueue_script( 'quiz', plugin_dir_url( __FILE__ ) . 'src/index.js' );
	wp_enqueue_script( 'axios', plugin_dir_url( __FILE__ ) . 'node_modules/axios/dist/axios.js' );
	wp_enqueue_script( 'marked', plugin_dir_url( __FILE__ ) . 'node_modules/marked/marked.min.js' );
	wp_enqueue_script( 'material-ui', plugin_dir_url( __FILE__ ) . 'node_modules/material-ui/RaisedButton/RaisedButton.js' );

	// wp_enqueue_script( 'ChromePhp', plugin_dir_url( __FILE__ ) . 'ChromePhp.php' );

	// wp_enqueue_script( 'react-flexbox-grid', plugin_dir_url( __FILE__ ) . 'node_modules/react-flexbox-grid/lib/components/Grid.js' );
	// wp_enqueue_script( 'react-flexbox-col', plugin_dir_url( __FILE__ ) . 'node_modules/react-flexbox-grid/lib/components/Col.js' );
	// wp_enqueue_script( 'react-flexbox-row', plugin_dir_url( __FILE__ ) . 'node_modules/react-flexbox-grid/lib/components/Row.js' );


	wp_enqueue_style( 'quiz', plugin_dir_url( __FILE__ ) . 'src/prayer.css' );
	wp_enqueue_style( 'quiz', plugin_dir_url( __FILE__ ) . 'src/index.css' );
	wp_enqueue_style( 'quiz', plugin_dir_url( __FILE__ ) . 'src/App.css' );
}

// Add "babel" type to quiz script
add_filter( 'script_loader_tag', 'wpshout_react_quiz_add_babel_type', 10, 3 );
function wpshout_react_quiz_add_babel_type( $tag, $handle, $src ) {
	if ( $handle !== 'quiz' ) {
		return $tag;
	}

	return '<script src="' . $src . '" type="text/babel"></script>' . "\n";

}
