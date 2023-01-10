<?php

// note 1302 areas.  One degree of latitude each.

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$doc = "WKT,name".'<br>'.PHP_EOL;

$start_y = 48.5;
$start_x = -124.5;

$diameter = 1;

$delta_y = -1;
$delta_x = 1;

$circle_offset = .135;

function create_box_from_midpoint($x, $y, $side){
	$bit = $side/2;
	$xA = number_format((float)($x + $bit), 3, '.', '');
	$xB = number_format((float)($x - $bit), 3, '.', '');
	$yA = number_format((float)($y + $bit), 3, '.', '');
	$yB = number_format((float)($y - $bit), 3, '.', '');
	return "(($xA $yA, $xA $yB, $xB $yB, $xB $yA, $xA $yA))";
}

function check_exclusion($x, $y){
	
	$list = [
		['41-99'], // 0
		['40-99'],
		['0-0','47-62', '66-99'],
		['66-99'],
		['0-0', '67-99'],
		['66-99'], // 5
		['64-99'],
		['63-99'],
		['0-0', '64-99'],
		['61-99'],
		['0-0', '59-99'], // 10
		['0-0', '58-99'],
		['0-1', '58-99'],
		['0-1', '57-99'],
		['0-2', '57-99'],
		['0-2', '57-99'], // 15
		['0-4', '57-99'],
		['0-5', '54-99'],
		['0-6', '53-99'],
		['0-10', '51-99'],
		['0-14', '51-99'], // 20
		['0-21', '50-99'],
		['0-22', '42-44', '51-99'],
		['0-23', '34-47', '51-99'],
		['0-24', '33-47', '52-99'],
		['0-25', '32-47', '52-99'], // 25
		['0-26', '32-48', '52-99'], // 26
		['0-48', '51-99']
	];

	if(isset($list[$y])){
		foreach($list[$y] as $entry){
			$data = explode('-', $entry);
			$x = (int)$x;
			$yA = (int)$data[0];
			$yB = (int)$data[1];
			if($x >= $yA && $x <= $yB){
				return true;
			}
		}
	}
	return false;
	
}



// =======================

for($i=0; $i < 69; $i++){
	for($j=0; $j < 28; $j++){
		if(!check_exclusion($i, $j)){
			$myX = $start_x + ($i * $diameter * $delta_x) - ($circle_offset * $i * $delta_x) + ($diameter * .5 * ($j % 2));
			$myY = $start_y + ($j * $diameter * $delta_y) - ($circle_offset * $j * $delta_y);
			$doc .= '"POLYGON ' . create_box_from_midpoint($myX, $myY, $diameter) . '",P' . "$i:$j($myX:$myY)<br>".PHP_EOL;
		}
	}
}

echo $doc;


