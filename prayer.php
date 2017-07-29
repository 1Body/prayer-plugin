<?php
/*
Plugin Name: PrayerList Plugin
Description: React.JS Prayers in WordPress!
Author: Alice
Author URI: aplai168@gmail.com
*/
error_log(print_r($v, TRUE), 3, '/var/tmp/errors.log');

global $wpdb;
$posts = $wpdb->get_results("SELECT ID, post_title FROM $wpdb->posts WHERE post_status = 'publish'
AND post_type='post' ORDER BY comment_count DESC LIMIT 0,4");
$prayercards = $wpdb->get_results("SELECT * FROM prayer_cards WHERE id = 1", OBJECT);
$results = $wpdb->get_results( 'SELECT * FROM wp_options WHERE option_id = 1', OBJECT );
   // Echo the title of the first scheduled post
  //  echo $posts[0]->post_title;
	//  echo $prayercards[0]->name;
	//  echo $results[0]->option_name;

	 add_action( 'wp_ajax_my_action', 'my_action' );
	 function my_action() {
	 	global $wpdb;
		$posts = $wpdb->get_results("SELECT ID, post_title FROM $wpdb->posts WHERE post_status = 'publish'
		AND post_type='post' ORDER BY comment_count DESC LIMIT 0,4");
	         echo $posts[0]->post_title;
	 	wp_die();
	 }

	 /**
	  * Grab latest post title by an author!
	  *
	  * @param array $data Options for the function.
	  * @return string|null Post title for the latest,  * or null if none.
	  */
	 function my_awesome_func( $data ) {
	 	$posts = get_posts( array(
	 		'author' => $data['id'],
	 	) );

	 	if ( empty( $posts ) ) {
			return new WP_Error( 'awesome_no_author', 'Invalid author', array( 'status' => 404 ) );
	 	}

	 	return $posts[0]->post_title;
	 }

	 function get_prayers( $data ) {
		 global $wpdb;
		 $prayercards1 = $wpdb->get_results("SELECT * FROM prayer_cards WHERE id = 1", OBJECT);
		 if ( empty($prayercards1)) {
			 return new WP_Error( 'no_prayers', 'no prayers', array('status' => 404));
		 }
		//  return $prayercards1[0]->name;
		 return $prayercards1;
	 }
	 add_action( 'rest_api_init', function () {
		 register_rest_route( 'prayer/v1', '/name', array(
		// 	register_rest_route( 'myplugin/v1', '/author/(?P<id>\d+)', array(
	 		'methods' => 'GET',
	 		'callback' => 'my_awesome_func',
	 	) );
		register_rest_route('prayer/v1', 'prayer', array(
			'methods' => 'GET',
			'callback' => 'get_prayers',
		));
	 } );







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
