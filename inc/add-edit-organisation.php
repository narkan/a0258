<?php

/**
 * Class to Add or Edit an Organisation
 *
 * Created: 25/3/17
 * Version 1.0
 * Copyright 2017 NARKAN Ltd
 **/

// TODO: ENSURE ORGANISATIONS TABLE COLUMNS MATCH VARIABLES IN THI CLASS
// TODO: CLEAR LONG LAT INPUTS & GET LONG LAT IF POSTCODE CHANGES

defined( 'ABSPATH' ) or die();


class cpd_AddEditOrganisation {

	function __construct() {
		add_action( 'admin_post_add_edit_org', array( $this, 'handle_form' ) );
	}

	/**
	 * Manages the displaying of the add / edit form
	 */
	function manage_form( ) {
		
		// Exit if user not logged in
		// TODO: MAKE THIS WORK
/*		if( ! current_user_can( 'administrator' ) ) {
			cpd_not_logged_in_message();
			return;
		}
*/

		$org = new Organisation();

		// Check if an Org has been selected - ie: has a number value for $_GET['id']
		// This covers resposnes both when selecting an org for editing, or for saving an org
		if( isset( $_REQUEST['id'] ) && is_int( (int) $_REQUEST['id'] ) ) {
			$org->load_org_details( $_REQUEST['id'] );
		}

		$this->display_form( $org );
	}


	/*
	 * Displays the Add / Edit form. Takes data from the Location object
	 */
	function display_form( $org ) {  ?>

		<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="POST">
			<div class="row">
				<div class="medium-6 columns">
					<label>Organisation name
						<input type="text" name="title" value="<?php echo esc_attr( $org->columns['Title'] ); ?>">
					</label>
				</div>
				<div class="medium-6 columns">
					<label>Telephone
						<input type="text" name="telephone" value="<?php echo esc_attr( $org->columns['Telephone'] ); ?>">
					</label>
				</div>
				<div class="medium-6 columns">
					<label>Telephone alternative number
						<input type="text" name="telephonealternative" value="<?php echo esc_attr( $org->columns['TelephoneAlternative'] ); ?>">
					</label>
				</div>
				<div class="medium-6 columns">
					<label>Email
						<input type="email" name="email" value="<?php echo esc_attr( $org->columns['Email'] ); ?>">
					</label>
				</div>
				<div class="medium-6 columns">
					<label>Website URL
						<input type="text" name="website_url" value="<?php echo esc_url( $org->columns['Website_URL'] ); ?>">
					</label>
				</div>

				<div class="medium-6 columns">
					<label>Address
						<textarea name="address" rows="4" maxlength="1000" style="resize:vertical">NOT USED</textarea>
					</label>
				</div>
				<div class="medium-6 columns">
					<label>Location
						<-- ie: parish -->
						<input type="text" name="location" value="NOT USED" disabled>
					</label>
				</div>
				<div class="medium-6 columns">
					<label>Town / City
						<input type="text" name="cityortown" value="<?php echo esc_attr( $org->columns['CityOrTown'] ); ?>" disabled>
					</label>
				</div>
				<div class="medium-6 columns">
					<label>County
						<input type="text" name="county" value="<?php echo esc_attr( $org->columns['County'] ); ?>" disabled>
					</label>
				</div>
				<div class="medium-6 columns">
					<label>Postcode
						<input type="text" name="postcode" value="<?php echo esc_attr( $org->columns['Postcode'] ); ?>">
					</label>
				</div>
				<div class="medium-6 columns">
					<label>Country
						<input type="text" name="country_id" value="<?php echo esc_attr( $org->columns['Country_ID'] ); ?>">
					</label>
				</div>
				<div class="medium-6 columns">
					<label>Latitude
						<input type="text" name="latitude" value="<?php echo esc_attr( $org->columns['Latitude'] ); ?>" disabled>
					</label>
				</div>
				<div class="medium-6 columns">
					<label>Longitude
						<input type="text" name="longitude" value="<?php echo esc_attr( $org->columns['Longitude'] ); ?>" disabled>
					</label>
				</div>
				<div class="medium-6 columns">
					<label>Opening Hours
						<textarea name="openinghours" rows="4" maxlength="1000" style="resize:vertical"><?php echo esc_textarea( $org->columns['OpeningHours'] ); ?></textarea>
					</label>
				</div>


				<?php // Get a list of all the services
				$services = $this->get_service_list();

				if ( ! $services || empty( $services ) ) {
					// Error retrieving Services
					console_debug( 'There was a problem retrieving the services from the database' );
				} else {

					// Outputs the services checkboxes, ticking any that are selected for this organisation
					// TODO: Need to find out which are selected for this organisation if we're editing it
					?>
					<fieldset class="services-checkboxes">
						<legend>Services</legend>

						<?php foreach ( $services as $service ) {
							$id    = $service['ID'];
							$title = $service['Title'];

							echo '<input id="service-' . $id . '" type="checkbox" name="services[]" value="' . $id . '"> <label for="service-' . $id . '">' . $title . '</label>';
						} ?>

					</fieldset>

				<?php }  // endif ?>

				<div class="medium-6 columns">
					<label>Other Information
						<textarea name="information" rows="4" maxlength="1000" style="resize:vertical"><?php echo esc_textarea( $org->columns['Information'] ); ?></textarea>
					</label>
				</div>


				<?php // Hidden field of id. Will be 'null' if a new record.

				if ( $org->columns['ID'] == null ) {
					$the_id = 'null';
				} else {
					$the_id = $org->columns['ID'];
				}

				echo '<input type="hidden" name="id" value="' . esc_attr( $the_id ) . '" />'; ?>

				<?php // Set the action handler for wordpress ?>
				<input type="hidden" name="action" value="add_edit_org"/>


				<div class="medium-6 columns">
					<input class="button" type="submit" name="submit" value="Save">
					<input class="button" type="submit" name="submit" value="Cancel" formnovalidate>
				</div>
			</div>
		</form>

	<?php }

	
	/**
	* Returns an array of all services from the db
	* 
	* eg: [0] => array( 'id'=>2, 'service'=>'counselling' ))
	*/
	function get_service_list() {
		global $wpdb;
		
		$services = $wpdb->get_results( 
			"
			SELECT ID, Title
			FROM cp_Categories
			WHERE 1
			",
			ARRAY_A
		);
		
		return $services;
	}

	/** 
	* Handles the Add / Edit form when it has been submitted to the server
	* Called by admin-post.php hook
	*/
	function handle_form() {
		global $wpdb;
		
		// Exit if user not logged in
		// TODO: GET THIS WORKING TO AVOID NON-ADMINS USING IT
/*		if( ! is_user_logged_in() ) {
			cpd_not_logged_in_message();
			return;	
		}
*/
		// Exit if the Cancel button was pressed, and redirect to page that lists all orgs
		if( isset( $_POST['submit'] ) && 'Cancel' == $_POST['submit'] ) {
			
			// TODO: Check destination
			wp_safe_redirect( site_url() . '/admin/list-orgs' );
			exit();
		}

		$org = new Organisation();

		$org->save_org_from_post();
		
	}

}