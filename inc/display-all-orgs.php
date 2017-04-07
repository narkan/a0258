<?php

/**
 * Class to display a list of all Organisations, or a searched group of them
 *    and enable them to be selected for editing / deleting
 *
 * Created: 26/3/17
 * Version 1.0
 * Copyright 2017 NARKAN Ltd
 **/

defined( 'ABSPATH' ) or die();


class cpd_DisplayOrganisations {

	function __construct() {
		// add_action( 'admin_post_search_orgs', array( $this, 'handle_search' ) );
	}
	
	function manage_display() {
		// Exit if user not logged in
		if( ! is_user_logged_in() ) {
			wp_redirect( site_url() );
			exit();		
		}
	
		$search_text = get_search_input();
		
		display_search_bar();

		$orgs = get_orgs_from_db( $search_text );
		
		if( $orgs && ! empty( $orgs ) ) {
			print_table( $orgs );
			
		} else {
			echo "No records returned";
		}
		
	}
	

	/**
	* Checks if the user has entered a search via GET
	* Returns the search text
	*/
	function get_search_input() {		
		// If search form was correctly submitted, get the input
		if( isset( $_GET['search_text'] ) ) {
			return $_GET['search_text'];
			
		} else {
			return null;
		}
	}

	
	/**
	* Displays the search bar to search the Organisations
	* Uses the title column to search
	*/
	function display_search_bar() {
	/*	echo '
		<form class="search-orgs" action="' . admin_url() . '/admin-post.php" method="GET">
			<input type="hidden" name="action" value="search_orgs" />
			<div class="input-group">
			  <input class="input-group-field" type="text" name="search_text">
			  <div class="input-group-button">
			    <input type="submit" class="button" name="search_button" value="Submit">
			  </div>
			</div>
		</form>
		';
		*/
		
		// TODO: CHECK ACTION URL
		echo '
		<form class="search-orgs" action="' . home_url() . '/admin/list-organisations" method="GET">
			<div class="input-group">
			  <input class="input-group-field" type="text" name="search_text">
			  <div class="input-group-button">
			    <input type="submit" class="button" name="search_button" value="Submit">
			  </div>
			</div>
		</form>
		';

	}
	
		
	/**
	* Returns an array of all (or searched for) orgs
	* The array is objects with key values being the Organisation's id
	*/
	function get_orgs_from_db( $search_text = null ) {
		global $wpdb;
		
		// TODO: CHECK PREFIX CONSTANT NAME CPD_PREFIX
		$table = CPD_PREFIX . "organisation";

		// WHERE title LIKE $search_text, 
		//   or if empty, WHERE 1
		$where = $search_text ? "title LIKE %" . $search_text . "%" : "1";
				
		$sql = $wpdb->prepare("
			SELECT name, location, telephone, email, website
			FROM %s WHERE %s",
			$table, $where
		);
		
		$orgs = $wpdb->get_results( $sql, OBJECT_K );
		
		return $orgs,
	}
	
	/**
	* Echoes out the table of Organisations
	*/
	function print_table( $orgs ) {
		echo '<table>';
		
		// Headings
		echo '<thead><tr>';
		
			echo '<td>Name</td>';
			echo '<td>Location</td>';
			echo '<td>Telephone</td>';
			echo '<td>Email</td>';
			echo '<td>Website</td>';
		
/*		$headings = get_column_names();
		
		foreach( $headings as $heading ) {
			echo '<th>' . $heading . '</th>';
		}
*/

		echo '</tr></thead>';
		
		// Body
		echo '<tbody>';

		foreach( $orgs as $org ) {
			// Link points to edit page with parameter org={id}
			// TODO: CHECK URL & PARAMETERS
			echo '<tr><a href="' . site_url() . '/admin/add-edit-organisation?org=' . $org->id . '">';
			
				echo '<td>' . $org->name . '</td>';
				echo '<td>' . $org->location . '</td>';
				echo '<td>' . $org->telephone . '</td>';
				echo '<td>' . $org->email . '</td>';
				echo '<td>' . $org->website . '</td>';

			// Iterate through column headings to use them 
			//     as the property name for the Organisations table
//			foreach( $headings as $heading ) {
				// ie: $org->$heading is using the variable $heading as the property nam
//				echo '<td>' . $org->$heading . '</td>';
//			}
			
			echo '</a></tr>';
		}
		echo '</tbody>';

		echo '</table>';
	}
	
	/**
	* Returns a numbered array of all the column headings for the Organisations table
	*/
/*	function get_column_names() {
		global $wpdb;
		
		$sql = "
		SELECT column_name
		FROM information_schema.columns
		WHERE table_name='organisations'
		";
		
		$headings = $wpdb->get_row( $sql, ARRAY_N );
		
		return $headings;
	}
*/
}