<?php

/*
 * Initialize data.
 *
 * "input.csv" should look like this:
 *  [0] => neighborhood
 *
 * "output.csv" will look like this:
 *  [0] => neighborhood
 *  [1] => latitude
 *  [2] => longitude
 */
$readHandle = fopen("input-csv-goes-here/input.csv", "r");
$writeHandle = fopen("output-csv-found-here/output.csv", "w");

// TODO Remove duplicates from input.csv...

// Process each line of "input.csv"...
while( $csvLineArray = fgetcsv($readHandle) ){
	$neighborhoodCityArray = getNeighborhoodFromGoogle($csvLineArray[0]);
	/// Write the processed line to "output.csv".
	fputcsv($writeHandle, array_merge($csvLineArray, $neighborhoodCityArray));
}

// Close file handles.
fclose($readHandle);
fclose($writeHandle);
echo "Script completed without errors.\n";



function getNeighborhoodFromGoogle($address)
{
	$url = "https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyDepye_kPyix97bgRtiI8weT_D7mcXMNt0&address=" . urlencode(trim($address) . "Philadelphia PA");
	$json = file_get_contents($url);
	$obj = json_decode($json);

	if( $obj->results
	 && $obj->results[0]
	 && $obj->results[0]->geometry
	 && $obj->results[0]->geometry->location
	 && $obj->results[0]->geometry->location->lat
	 && $obj->results[0]->geometry->location->lng ){
		$lat = $obj->results[0]->geometry->location->lat;
		$lng = $obj->results[0]->geometry->location->lng;
	}

	return ($lat && $lng) ? array($lat, $lng) : array();
}
