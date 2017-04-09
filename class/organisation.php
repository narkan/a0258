<?php

/**
 * Class that represents an Organisation
 *
 * Created: 2/4/17
 * Version 1.0
 * Copyright 2017 NARKAN Ltd
 **/


class Organisation {

	// Location name, address, etc.
	// Data pulled from Locations, Categories & Opening_Hours tables
	public $columns = array(
		"ID"                   => null,
		"Title"                => "",
		"Telephone"            => "",
		"TelephoneAlternative" => "",
		"Email"                => "",
		"Website_URL"          => "",
		"Information"          => "",
		"CityOrTown"           => "",
		"County"               => "",
		"Postcode"             => "",
		"Country_ID"           => 229,
		"Latitude"             => 0.0,
		"Longitude"            => 0.0,
		"OpeningHours"         => ""
	);

	// Categories / Services
	public $services = array();


	function __construct() {}



	/************** LOADING DATA FROM DATABASE ***********/

	/**
	 * Populate the object using the ID given.
	 */
	function load_org_details( $id ) {
		if ( $id != null ) {

			// Set this object's ID
			$this->columns['ID'] = $id;

			$org = $this->get_org_from_db();

			$this->populate_this_object( $org );
		}
	}

	/**
	 * Set the $this->column array to the data pulled from the db
	 *    using $this->columns['id'] as the index
	 *
	 * @Return: Either the retrieved Organisation row, or null if db query failed
	 */
	function get_org_from_db() {
		global $wpdb;

		$org = null;

		if ( $this->columns['ID'] ) {
			$q = $wpdb->prepare( "
					SELECT *
					FROM cp_Organisations
					WHERE cp_Organisations.ID = %d
		            ",
				$this->columns['ID'] );

			$org = $wpdb->get_row( $q, ARRAY_A );
		}

		if ( ! empty( $wpdb->last_error ) ) {
			console_debug( 'SQL error in add-edit-organisation.php, get-the-org(): ' . $wpdb->last_error );
		}

		return $org;
	}


	/**
	 * Set the $this->column array using the array data ($org) pulled from the db
	 *    using $this->columns['id'] as the index
	 *
	 * @Return: none
	 */
	function populate_this_object( $org ) {

		if( $org ) {
			// DELETE: ID already populated --->  $this->columns['ID'] = $org['ID'];
			$this->columns['Title']                = $org['Title'];
			$this->columns['Telephone']            = $org['Telephone'];
			$this->columns['TelephoneAlternative'] = $org['TelephoneAlternative'];
			$this->columns['Email']                = $org['Email'];
			$this->columns['Website_URL']          = $org['Website_URL'];
			$this->columns['Information']          = $org['Information'];
			$this->columns['CityOrTown']           = $org['CityOrTown'];
			$this->columns['County']               = $org['County'];
			$this->columns['Postcode']             = $org['Postcode'];
			$this->columns['Country_ID']           = $org['Country_ID'];
			$this->columns['Latitude']             = $org['Latitude'];
			$this->columns['Longitude']            = $org['Longitude'];
			$this->columns['OpeningHours']         = $org['OpeningHours'];
		}
	}




	/************** SAVING DATA TO DATABASE ***********/

	/**
	 * Takes the data retrieved from the $_POST array and saves it to the database
	 * Can be either a new Org or amending an old one
	 */
	function save_org_from_post() {
		global $wpdb;

		$new = true;

		// Is this a new or edited Organisation?
		if ( isset( $_POST['id'] ) && $_POST['id'] !== 'null' ) {

			if( is_int( (int) $_POST['id'] ) ){
				$new = false;

				$this->columns['ID'] = (int) $_POST['id'];
			}
		}

		// Sanitize...

		if( isset( $_POST['title'] ) ) {
			$title = sanitize_text_field( $_POST['title'] );
			$this->columns['Title'] = $title;
		}

		if( isset( $_POST['telephone'] ) ) {
			$telephone = sanitize_text_field( $_POST['telephone'] );
			$this->columns['Telephone'] = $telephone;
		}

		if( isset( $_POST['telephonealternative'] ) ) {
			$telephonealternative = sanitize_text_field( $_POST['telephonealternative'] );
			$this->columns['TelephoneAlternative'] = $telephonealternative;
		}

		if( isset( $_POST['email'] ) ) {
			$email = sanitize_email( $_POST['email'] );
			$this->columns['Email'] = $email;
		}

		if( isset( $_POST['website_url'] ) ) {
			$website_url = sanitize_text_field( $_POST['website_url'] );
			$this->columns['Website_URL'] = $website_url;
		}

		if( isset( $_POST['information'] ) ) {
			$information = filter_var( $_POST['information'], FILTER_SANITIZE_STRING );
			$this->columns['Information'] = $information;
		}

		if ( isset( $_POST['cityortown'] ) ) {
			$cityortown= sanitize_text_field( $_POST['cityortown'] );
			$this->columns['CityOrTown'] = $cityortown;
		}

		if ( isset( $_POST['county'] ) ) {
			$county = sanitize_text_field( $_POST['county'] );
			$this->columns['County'] = $county;
		}

		if ( isset( $_POST['postcode'] ) ) {
			$postcode = sanitize_text_field( $_POST['postcode'] );
			$this->columns['Postcode'] = $postcode;
		}

		if ( isset( $_POST['country_id'] ) && is_int( (int) $_POST['country_id'] ) ) {
			$country_id = (int) $_POST['country_id'];
			$this->columns['Country_ID'] = $country_id;
		}

		if ( isset( $_POST['openinghours'] ) ) {
			$openinghours = filter_var( $_POST['openinghours'], FILTER_SANITIZE_STRING );
			$this->columns['OpeningHours'] = $openinghours;
		}

		// TODO: CALCULATE LONG & LAT!!
		$this->columns['Latitude'] = 10;
		$this->columns['Longitude'] = 20;


		//***** SAVE TO DATABASE

		$outcome = null;


		$data = array(
			'ID'                   => $this->columns['ID'],
			'Title'                => $this->columns['Title'],
			'Telephone'            => $this->columns['Telephone'],
			'TelephoneAlternative' => $this->columns['TelephoneAlternative'],
			'Email'                => $this->columns['Email'],
			'Website_URL'          => $this->columns['Website_URL'],
			'Postcode'             => $this->columns['Postcode'],
			'Country_ID'           => $this->columns['Country_ID'],
			'Latitude'             => $this->columns['Latitude'],
			'Longitude'            => $this->columns['Longitude'],
			'CityOrTown'           => $this->columns['CityOrTown'],
			'Information'          => $this->columns['Information'],
			'OpeningHours'         => $this->columns['OpeningHours']
		);

		$string_formats = array(
			'%d',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%d',
			'%f',
			'%f',
			'%s',
			'%s',
			'%s'
		);

		if( $new ) {
			// Insert new organisation
			$outcome = $wpdb->insert(
				"cp_Organisations",
				$data,
				$string_formats
			);

		} else {
			// Replace current organisation
			$outcome = $wpdb->replace(
				"cp_Organisations",
				$data,
				$string_formats
			);

		}



		// Check if the update/insert was successful
		$add_update = ( $new ) ? "added" : "updated";

		if ( $outcome ) {
			// TODO: ECHO OUTCOME TO CONSOLE
			console_debug( "SUCCESS: {$outcome} record(s) " . $add_update );
		} else {
			console_debug( "PROBLEM: Record NOT " . $add_update );
		}

	}

}




