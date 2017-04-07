<?php

/**
 * Initialise plugin
 *
 * Created: 2/4/17
 * Version 1.0
 * Copyright 2017 NARKAN Ltd
 **/


function cpd_init() {
	global $wpdb;

	$wpdb->show_errors();
}


/**
 * Define new role allowing users to update the CP database
 */
/*
function add_db_admin_role() {
	add_role( 'database_admin', 'Database Admin', array(
		'read'              => true, // Allows a user to read
		'create_posts'      => true, // Allows user to create new posts
		'edit_posts'        => true, // Allows user to edit their own posts
		'edit_others_posts' => true, // Allows user to edit others posts too
		'publish_posts'     => true, // Allows the user to publish posts
		'manage_categories' => true, // Allows user to manage post categories
	) );
}
add_action( 'init', 'add_db_admin_role' );
*/

function cpd_not_logged_in_message() {
	echo 'You must log in to access this page';

	return;
}

/**
 * Console debugger
 *
 * @param      $data
 * @param bool $throw_error
 */

function console_debug( $data ) {
	if ( WP_DEBUG ) {
		if ( is_array( $data ) ) {
			$output = '<script>console.log( "Debug: ' . implode( ',', $data ) . '" );</script>';
		} else {
			$output = '<script>console.log( "Debug: ' . htmlspecialchars( $data ) . '" );</script>';
		}

		printf( $output . "<br />" );
	}
	else {
		// Output to email
	}
}