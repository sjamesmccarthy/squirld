<?php

/**
 * @Author: James McCarthy <sjamesmccarthy>
 * @Date:   05-02-2017 12:11:58
 * @Email:  james@jmccarthy.xyz
 * @Filename: ajax_currentlocations.php
 * @Last modified by:   sjamesmccarthy
 * @Last modified time: 05-17-2017 7:01:23
 * @Copyright: 2017
 */

ini_set('display_errors', 1);
error_reporting(E_ALL ^ E_NOTICE);

spl_autoload_register(function ($class) {
    include 'controller/' . $class . '.php';
});

// load config file here

function getCrowdData($data) {

	$crowd_data = '';

	foreach ($data as $rate_key => $rate_val) {

	if($rate_key == "noise") {
		$ratings_cnt = explode('/', $rate_val);
	    	$this_rating = round($rate_val, 0, PHP_ROUND_HALF_DOWN);

		$stars = '';
		for ($i = 1;$i <= 4;$i++) {
			if($i <= $this_rating) {

			    	if($this_rating == 1) {
			    		$color = "#99CCFF";
			    	} else if($this_rating == 2) {
			    		$color = "green";
			    	}  else if($this_rating == 3) {
			    		$color = "orange";
			    	}  else if($this_rating == 4) {
			    		$color = "red";
			    	} else {
			    		$color = 'rgba(0,0,0,.1)';
			    	}

			} else {
				// $stars .= '<i class="fa fa-male "' . 'style="color: #AAA"></i>';
				$color = 'rgba(0,0,0,.1)';
			}

			if($i&1) {
				$icon = "male";
			} else {
				$icon = "female";
			}

			$stars .= '<i class="fa fa-' . $icon . '"' . 'style="color:' . $color . '"></i>';

		}

		$crowd_data .=  $stars;

	}
	}

	return($crowd_data);
}

# force yourself to be at BeerNV
# $_REQUEST['lat'] = '39.4393656';
# $_REQUEST['lng'] = '-119.7708987';

$listings = new Listings;
$results = $listings->showCurrentLocations($_REQUEST['lat'],$_REQUEST['lng']);

if(count($results) >= 1 && $results[0] != 0) {

$i=0;

foreach($results as $key => $value) {
// $value['distance'] = .5;

	// && number_format((float)$value['distance'], 2, '.', '') < .02
	if($i == 0 && number_format((float)$value['distance'], 2, '.', '') < .05) {
		$crowd_action = '<br /><p id="reportCrowd" style="background-color: #000; color: #FFF; font-size: .5em; padding: 3px; margin: 5px 0 0 -5px; border-radius:3px; text-align: center" data-id="' . $value['id'] . '" onclick="reportCrowd();">REPORT CROWD SIZE</p>';
		$i++;
	} else {
		$crowd_action = '<br /><span style="font-size: .5em; padding: 0; margin: 0 0 0 5px;">average crowd rating</span>';
	}

	if( number_format((float)$value['distance'], 3, '.', '') > .045 && number_format((float)$value['distance'], 3, '.', '') <= 1.25 ) {
		$improveGEO = "<span id='gps'style='font-size: .8em;'><a href='#' onclick='alert(\"Thank You!\"); document.getElementById(\"gps\").remove();'>Are you here? Tap to improve accuracy</a></span>";
	} else {
		$improveGEO = '';
	}

	// Date conversion to 12 hour
	$start_time = date('g:i A', strtotime($value['start']));
	$end_time = date('g:i A', strtotime($value['end']));

	$html .= "<li class='listings-hr'>";

	if(number_format((float)$value['distance'], 1, '.', '') == 0.0) {
		$html .=  "<p style='float: left; width: 15%; padding-left: 5px; margin-top: 35px;
    font-size: 2.0em;'><i class='fa fa-map-marker'></i></p>";
	} else {
		$html .= "<p style='float: left; width: 15%; padding-left: 5px'>" . number_format((float)$value['distance'], 1, '.', '') . "<span style='font-size: .6em;'> m<br />" . $value['city'] . "</span></p>";
	}

		// number_format((float)$foo, 2, '.', '');
				$html .= "<p style='float: left; width: 50%;'>";
				$html .= "<a href='happyplace.php?&id=" . $value['id'] . "&name=" . urlencode($value['name']) . "'>";
				$html .=  substr($value['name'], 0, 20) . "</a> <br />";
				// echo " &mdash; " . $value['ambiance'] . "<br />";
				$html .= "<span style='font-size: .8em'>";

				$serving = array();
				//$serving_kind = explode(',', $value[4]);

				if($value['beer'] == 1) {
					$serving[] = 'Beer';
				}
				if($value['wine'] == 1) {
					$serving[] = 'Wine';
				}
				if($value['cocktails'] == 1) {
					$serving[] = 'Cocktails';
				}
				if ($value['food'] == 1) {
					$serving[] = 'Food';
				}

				$serving_format = rtrim(implode(', ', $serving), ',');
				$html .= $serving_format . "<br />";


				//  $html .= $value[3], "<br />";
				$html .= $start_time . " - " . $end_time . " <br /> " . $value['address'] . "</span><br />";
				$html .= $improveGEO;

				$html .= "</p>";
				$html .= "<div style='float: right; margin-right: 20px; margin-top: 37px;'>";

				$html .= getCrowdData($value['ratings']);

				$html .= $crowd_action;

				// <i class='fa fa-map-marker' aria-hidden='true'></i>
				$html .= "</div><br style='clear: both' /></li>";
}

} else {
	// print "<pre>";
	// print_r($results);

	$html = "<p style='padding: 50px 50px 0 50px; text-align: center; font-size: 2.0em;'>What!<br />Taps Busted, Try Again Cowboy</p><p style='font-size: 1.0em; text-align: center; margin-top: -35px'><a href='/?a=states'>Start A New Search</a></p>";
}

print $html;
