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
		"AlternativeTelephone" => "",
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


	/**
	 * Populate the object using the ID given, or will just initialise a new object with default properties set.
	 */
	function __construct( $id = null ) {

		if ( $id != null ) {

			// Set this object's ID
			$this->columns['id'] = $id;

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

		if ( $this->columns['id'] ) {
			$q = $wpdb->prepare( "
					SELECT *
					FROM cp_Organisations
					WHERE cp_Organisations.ID = %d
		            ",
				$this->columns['id'] );

			$org = $wpdb->get_row( $q, ARRAY_A );
		}

		if ( ! empty( $wpdb->last_error ) ) {
			console_debug( 'SQL error in add-edit-organisation.php, get-the-org(): ' . $wpdb->last_error );
		}

		return $org;
	}


	/**
	 * Set the $this->column array using the data ($org) pulled from the db
	 *    using $this->columns['id'] as the index
	 *
	 * @Return: none
	 */
	function populate_this_object( $org ) {

		if( $org ) {
			// DELETE: ID already populated --->  $this->columns['ID'] = $org['ID'];
			$this->columns['Title']                = $org['Title'];
			$this->columns['Telephone']            = $org['Telephone'];
			$this->columns['AlternativeTelephone'] = $org['AlternativeTelephone'];
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

}




