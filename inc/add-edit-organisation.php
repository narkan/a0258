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

	private $org_object;

	function __construct() {
		add_action( 'admin_post_add_edit_org', array( $this, 'handle_form' ) );

		$this->org_object = new Organisation();
	}
	
	function manage_form( ) {
		
		// Exit if user not logged in
		// TODO: CHECK THIS WORKS
/*		if( ! current_user_can( 'administrator' ) ) {
			cpd_not_logged_in_message();
			return;
		}
*/

		$this->get_the_org();

		$this->display_form();
	}
	

	/**
	* Checks if there is a chosen Organisation via GET['id']
	*.   ie: if the display list page has sent us here
	* Returns the org as an object, else null
	*/
	function get_the_org() {	
		global $wpdb;

		$org = null;

		// If we're coming from the display-all-orgs.php page we'll
		//   we'll have a $_GET['id']
		if( isset( $_GET['id'] ) && is_int( (int) $_GET['id'] ) ) {
			$get_id = $_GET['id'];

			$org = $wpdb->get_row( $wpdb->prepare( "
				SELECT cp_Locations.ID, cp_Locations.Title, cp_Locations.Telephone, cp_Locations.AlternativeTelephone, cp_Locations.Email, cp_Locations.Website_URL, cp_Locations.Information,
					   cp_Address.CityOrTown, cp_Address.County, cp_Address.Postcode, cp_Address.Country_ID, cp_Address.Latitude, cp_Address.Longitude,
					   cp_OpeningHours.OpeningHours
				FROM cp_Locations
				INNER JOIN cp_Address ON cp_Address.ID = cp_Locations.Address_ID
				LEFT JOIN cp_OpeningHours ON cp_OpeningHours.Locations_ID = cp_Locations.ID
				WHERE cp_Locations.ID = %d
	            ",
				$get_id ),
				ARRAY_A
			);

			if( ! empty( $wpdb->last_error ) ) {
				console_debug( 'SQL error in add-edit-organisation.php, get-the-org(): ' . $wpdb->last_error );
			}

		}

		// If the $org array has contents, assign them to the Location object
		if( $org ) {
			$this->populate_org_object( $org );
		}
	}
	

	/*
	 * Takes the data array from the query in get_the_org() and inserts it into the Location object
	 * If the array is not set / null, it'll just create a new empty Location object
	 */
	function populate_org_object( $org ) {

		$this->org_object->columns['ID']                   = $org['ID'];
		$this->org_object->columns['Title']                = $org['Title'];
		$this->org_object->columns['Telephone']            = $org['Telephone'];
		$this->org_object->columns['AlternativeTelephone'] = $org['AlternativeTelephone'];
		$this->org_object->columns['Email']                = $org['Email'];
		$this->org_object->columns['Website_URL']          = $org['Website_URL'];
		$this->org_object->columns['Information']          = $org['Information'];
		$this->org_object->columns['CityOrTown']           = $org['CityOrTown'];
		$this->org_object->columns['County']               = $org['County'];
		$this->org_object->columns['Postcode']             = $org['Postcode'];
		$this->org_object->columns['Country_ID']           = $org['Country_ID'];
		$this->org_object->columns['Latitude']             = $org['Latitude'];
		$this->org_object->columns['Longitude']            = $org['Longitude'];
		$this->org_object->columns['OpeningHours']         = $org['OpeningHours'];


	}

	
	/*
	 * Displays the Add / Edit form. Takes data from the Location object
	 */
	function display_form() {
	
	// TODO: textarea for info & address is maxlength=1000. Ensure db column is varchar(1000) too
echo esc_url( admin_url( 'admin-post.php' ) );		?>
	<form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="POST">
	  <div class="row">
	    <div class="medium-6 columns">
	      <label>Organisation name
	        <input type="text" name="name" value="<?php echo esc_attr( $this->org_object->columns['Title'] ); ?>">
	      </label>
	    </div>
	    <div class="medium-6 columns">
	      <label>Telephone
	        <input type="text" name="telephone" value="<?php echo esc_attr( $this->org_object->columns['Telephone'] ); ?>">
	      </label>
	    </div>
	    <div class="medium-6 columns">
	      <label>Telephone alternative number
	        <input type="text" name="alternativetelephone" value="<?php echo esc_attr( $this->org_object->columns['AlternativeTelephone'] ); ?>">
	      </label>
	    </div>
		<div class="medium-6 columns">
	      <label>Email
	        <input type="email" name="email" value="<?php echo esc_attr( $this->org_object->columns['Email'] ); ?>">
	      </label>
	    </div>
		<div class="medium-6 columns">
	      <label>Website URL
	        <input type="text" name="website_url" value="<?php echo esc_url( $this->org_object->columns['Website_URL'] ); ?>">
	      </label>
	    </div>

		<div class="medium-6 columns">
	      <label>Address
	        <textarea name="address" rows="4" maxlength="1000">NOT USED</textarea>
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
				  <input type="text" name="cityortown" value="<?php echo esc_attr( $this->org_object->columns['CityOrTown'] ); ?>" disabled>
			  </label>
		  </div>
		  <div class="medium-6 columns">
			  <label>County
				  <input type="text" name="county" value="<?php echo esc_attr( $this->org_object->columns['County'] ); ?>" disabled>
			  </label>
		  </div>
	    <div class="medium-6 columns">
	      <label>Postcode
	        <input type="text" name="postcode" value="<?php echo esc_attr( $this->org_object->columns['Postcode'] ); ?>">
	      </label>
	    </div>
		  <div class="medium-6 columns">
			  <label>Country
				  <input type="text" name="country_id" value="<?php echo esc_attr( $this->org_object->columns['Country_ID'] ); ?>">
			  </label>
		  </div>
	    <div class="medium-6 columns">
	      <label>Latitude
	        <input type="text" name="latitude" value="<?php echo esc_attr( $this->org_object->columns['Latitude'] ); ?>" disabled>
	      </label>
	    </div>
		<div class="medium-6 columns">
	      <label>Longitude
	        <input type="text" name="longitude" value="<?php echo esc_attr( $this->org_object->columns['Longitude'] ); ?>" disabled>
	      </label>
	    </div>
		  <div class="medium-6 columns">
			  <label>Opening Hours
				  <textarea name="openinghours"><?php echo esc_textarea( $this->org_object->columns['OpeningHours'] ); ?></textarea>
			  </label>
		  </div>



		<?php // Get a list of all the services
		$services = $this->get_service_list();
		
		if( ! $services || empty( $services ) ) {
			// Error retrieving Services
			console_debug( 'There was a problem retrieving the services from the database' );
		} else { 	
	
			// Outputs the services checkboxes, ticking any that are selected for this organisation
			// TODO: Need to find out which are selected for this organisation if we're editing it
			?>
			<fieldset class="services-checkboxes">
				<legend>Services</legend>
				
				<?php foreach( $services as $service ) {
					$id     = $service['ID'];
					$title  = $service['Title'];
					
					echo '<input id="service-' . $id . '" type="checkbox" name="services[]" value="' . $id .'"> <label for="service-' . $id .'">' . $title . '</label>';
				}	?>
				
			</fieldset>
			
		<?php }  // endif ?>
		
		<div class="medium-6 columns">
	      <label>Other Information
	        <textarea name="information" rows="4" maxlength="1000"><?php echo esc_textarea( $this->org_object->columns['Information'] ); ?></textarea>
	      </label>
	    </div>

			
		<?php // Hidden field of id. Will be 'null' if a new record.

		if( $this->org_object->columns['ID'] == null ) {
			$the_id = 'null';
		} else {
			$the_id = $this->org_object->columns['ID'];
		}

		echo '<input type="hidden" name="id" value="' . esc_attr( $the_id ) . '" />';		?>
		
		<?php // Set the action handler for wordpress ?>
		<input type="hidden" name="action" value="add_edit_org" />
	
		
		<div class="medium-6 columns">
			<input class="button" type="submit" name="submit" value="Save">
			<input class="button" type="submit" name="submit" value="Cancel" formnovalidate>
		</div>
	  </div>	
	  
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
		
		$outcome = false;
		
		// Is this a new or edited Organisation?
		if( isset( $_POST['id'] ) && $_POST['id'] != 'null' ) {
			// Edited Organisation, therefore Update record
			
			// TODO: SANITIZE INPUTS
			$outcome = $wpdb->replace(
				"cp_organisation",
				array(
					'id'			    	=> $_POST['id'],
					'name' 			        => $_POST['name'],
					'telephone'		        => $_POST['telephone'],
					'alternativetelephone'	=> $_POST['alternativetelephone'],
					'email' 	       		=> $_POST['email'],
					'website_url'   		=> $_POST['website_url'],
					'address' 		        => $_POST['address'],
					'postcode'  	    	=> $_POST['postcode'],
					'country_id'     		=> $_POST['country_id'],
					'latitude'  	    	=> $_POST['latitude'],
					'longitude'     		=> $_POST['longitude'],
					'organisation' 		    => $_POST['organisation'],
					'cityortown' 	    	=> $_POST['cityortown'],
					'information'	 		=> $_POST['information']
				),
				array(
					'%d',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%f',
					'%f',
					'%s',
					'%s',
					'%s'
				)
			);			
		} else {
			// New Organisation, therefore Insert new record
			// id not set as auto-increment will set it
			
			// TODO: SANITIZE INPUTS
			
			$outcome = $wpdb->insert(
				"cp_organisation",
				array(
					'name'                 => $_POST['name'],
					'telephone'            => $_POST['telephone'],
					'alternativetelephone' => $_POST['alternativetelephone'],
					'email'                => $_POST['email'],
					'website_url'          => $_POST['website_url'],
					'address'              => $_POST['address'],
					'postcode'             => $_POST['postcode'],
					'country_id'           => $_POST['country_id'],
					'latitude'             => $_POST['latitude'],
					'longitude'            => $_POST['longitude'],
					'organisation'         => $_POST['organisation'],
					'cityortown'           => $_POST['cityortown'],
					'information'          => $_POST['information']
				),
				array(
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%f',
					'%f',
					'%s',
					'%s',
					'%s'
				)
			);
			
			// Check if the update/insert was successful
			if( $outcome ) {
				// TODO: ECHO OUTCOME TO CONSOLE
				console_debug( "SUCCESS: {$outcome} record(s) updated or added" );
			} else {
				console_debug( "PROBLEM: Record NOT updated or added" );
			}
		}
	}

}