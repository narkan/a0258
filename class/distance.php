<?php

/**
 * Class DisplayOrgs
 *
 * Calculates distances between user and Organisations
 * Created 6/4/17
 * Copyright 2017 Narkan Ltd
 * Author: Toby Cosh
 * URL: narkan.co.uk
 */

class DisplayOrgs {

private $allDistances = array();


/** Display All Orgs **/


/** Display Closest Orgs **/

FN displayClosestOrgs(postcode) {
$orgObjects = getClosestOrgs(postcode);
foreach $orgObjects as $org {
echo $this->allDistances[$org['id']]
echo name, etc
}
}

FN getClosestOrgs(postcode) {
$orgs = getAllDistances(postcode)
for( $i = 0; $i < MAX_ORGS_DISPLAYED; $i++ ) {
$closest[] = $orgs[$i];
}
$args = array( 'id' => $closest );
$orgObjects = new WP_Query( $args);
return $orgObjects;
}

FN getAllDistances(postcode) {
$orgs = getAllOrgs()
foreach $orgs as $org
getDistance(postcode, thisOrgLongLat)
$this->allDistances[] = 'ID'=>'distance';
endforeach
sort array by distance
Ret: array
}

FN getAllOrgs()
- get all organisations from db: ID, Lat, Long
Ret: array of all Orgs


FN getDistance(a, b)
- check distance between a & b - postcode.io
Ret: distance

