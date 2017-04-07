<?php

/**
 * Class that represents an Organisation/Location
 *
 * Created: 2/4/17
 * Version 1.0
 * Copyright 2017 NARKAN Ltd
 **/


class Location {

	// Location name, address, etc.
	// Data pulled from Locations, Categories & Opening_Hours tables
	public $columns = array(
		"ID"                    => null,
		"Title"                 => "",
		"Telephone"             => "",
		"AlternativeTelephone"  => "",
		"Email"                 => "",
		"Website_URL"           => "",
		"Information"           => "",
		"CityOrTown"            => "",
		"County"                => "",
		"Postcode"              => "",
		"Country_ID"            => 229,
		"Latitude"              => 0.0,
		"Longitude"             => 0.0,
		"OpeningHours"          => ""
	);

	// Categories / Services
	public $services = array();



	function __construct() {
	}
}