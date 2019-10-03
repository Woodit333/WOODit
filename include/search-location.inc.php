<?php

// Get search term
$searchTerm = urlencode($_GET['qry']);

$maps_url = 'http://autocomplete.geocoder.api.here.com/6.2/suggest.json?app_id=bzonpeNIWqYBXzfcQhLK&app_code=i4NaaZIjpiCRyvDWg4BNPQ&query={0}&beginHighlight=%3Cb%3E&endHighlight=%3C/b%3E';
$maps_url = str_replace("{0}",$searchTerm, $maps_url);
$maps_json = file_get_contents($maps_url);
$maps_array = json_decode($maps_json, true);

// Generate locations data array
$locations = array();
if(count($maps_array) > 0){
    foreach($maps_array['suggestions'] as $suggestion) {
        $data['id'] =  $suggestion['locationId'];
        $data['value'] = str_replace(['<b>', '</b>'], '' ,$suggestion['label']);
        array_push($locations, $data);
    }
}

// Return results as json encoded array
echo json_encode($locations);

?>