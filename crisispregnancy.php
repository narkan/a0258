<?php
/*
 * crisispregnancy.php
 *
 * Author: Narkan Ltd
 * Author URI: narkan.co.uk
 * Description: Manages the Crisis Pregnancy database
 * Plugin Name: Crisis Pregnancy Database
 * Plugin URI: narkan.co.uk
 * Version: 1.0.0
 *
 * Created 23/03/2017 20:50
 * Copyright 2017 NARKAN Ltd
 */

// Prevent script kiddies
defined( 'ABSPATH' ) or die();

require( dirname( __FILE__ ) . '/inc/init.php' );
require( dirname( __FILE__ ) . '/class/organisation.php' );
require( dirname( __FILE__ ) . '/inc/add-edit-organisation.php' );

load_plugin_textdomain( 'crisispregnancy', false, basename( dirname( __FILE__ ) ) . '/languages' );

//require_once( dirname( __FILE__ ) . '/class/DBhandler.php' );
//$dbHandler = new DBhandler();


cpd_init();

$cpd_add_edit_handler = new cpd_AddEditOrganisation();

// Add shortcode to embed on page to display add / edit form
add_shortcode( 'add_edit_form', 'display_add_edit_form' );
function display_add_edit_form() {
	global $cpd_add_edit_handler;

	$cpd_add_edit_handler->manage_form();
}


//test_queries();

function test_queries() {
	global $wpdb;
	$orgs = $wpdb->get_results( "
			SELECT *
			FROM cp_Locations
			INNER JOIN cp_Address ON cp_Address.ID=cp_Locations.Address_ID
			LEFT JOIN cp_OpeningHours ON cp_OpeningHours.Locations_ID=cp_Locations.ID
			WHERE cp_Locations.ID=114
        ",
		OBJECT_K
	);

	// Get the Services (Categories) available at a Location
	$services = $wpdb->get_results( "
			SELECT cp_Categories.Title
			FROM cp_Locations_TO_Categories, cp_Categories
			WHERE cp_Locations_TO_Categories.Categories_ID=cp_Categories.ID
				AND cp_Locations_TO_Categories.Locations_ID=19
        ",
		OBJECT_K
	);

	die();

}