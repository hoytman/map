<?php

$degree = 111139; // 111,139 meters = 1 degree

$api = '&key=';

//$y = '48.5';
//$x = '-124.5';

$y = '32.065'; // lat
$x = '-84.21'; // lon

$both = '-84.71:38.12';

if($both){
	$both = explode(':',$both);
	$y = (float)$both[1];
	$x = (float)$both[0];
}

$loc = urldecode('aldi');

$page = file_get_contents('https://maps.googleapis.com/maps/api/place/nearbysearch/json?location='.$y.'%2C'.$x.'&radius=50000&keyword='.$loc.$api);

// adius=14200 good for a 10 km square

$data = json_decode($page);

$data = $data->results;

$myData = [];

foreach($data as $entry){
	$lat = (float)$entry->geometry->location->lat;
	$lon = (float)$entry->geometry->location->lng;
	$ydis = abs($lat - $y);
	$xdis = abs($lon - $x);
	$dist = sqrt(($ydis*$ydis)+($xdis*$xdis)) * 111;


	$myData[] = [
		'lat' => $lat,
		'lon' => $lon,
		'add' => $entry->vicinity,
		'dist' => $dist.'km'
	];

}

echo '<pre>';
echo print_r($myData);
echo '</pre>';

echo '<pre>';
echo print_r($data);
echo '</pre>';
?>
