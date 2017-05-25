<?php
    
function getCrowdDataDEPRECATETHIS ($data) {

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

?>

<html>
<head>	
	<?php include($_SERVER['DOCUMENT_ROOT'] . '/view/snip_dochead_meta.php'); ?>
	<title>Squirld <?= strtoupper($_REQUEST['a']) ?> / Find Your Happy Hour</title>
	
	<script>
	window.onload = geoFindMe;

function errorCallback(error) {
  alert('ERROR(' + error.code + '): ' + error.message);
};

function geoFindMe() {
console.log('geoFindMe');
  var output = document.getElementById("out");
  var heading = document.getElementById("coords");
  var lat_placeholder = document.getElementById("lat_ph");
  var lng_placeholder = document.getElementById("lng_ph");

  if (!navigator.geolocation){
    output.innerHTML = "<p>Geolocation is not supported by your browser</p>";
    return;
  }

  function success(position) {
    var latitude  = position.coords.latitude.toFixed(8);
    var longitude = position.coords.longitude.toFixed(8);

    output.innerHTML = '<p>Latitude is ' + latitude + ' <br>Longitude is ' + longitude + '°</p>';
    heading.innerHTML = 'Within 5 Miles of ' + latitude + ' , ' + longitude;
    lat_placeholder.innerHTML = latitude;
    lng_placeholder.innerHTML = longitude;

	var data = 'lat:' + latitude + ',lng:' + longitude;
	// var data = latitude + "," + longitude;
	console.log(data);

	$.ajax({
	  type: "POST",
	  url: "ajax_currentlocations.php",
	  data:{"lat": latitude,"lng":longitude},
	  cache: false,
	  success: function(data){
	     $("#currentLocationsResults").html(data);
	     $('#currentLocationImage').hide();
	     // console.log(data);
	  },
    		error: function(xhr) {
        	//Do Something to handle error
    	  }
	});

  }

  function error() {
    output.innerHTML = "Unable to retrieve your location";
  }

  output.innerHTML = "<p>Locating…</p>";

  navigator.geolocation.getCurrentPosition(success, error);

  // AJAX request to page that runs query to get locations by current location
  // return $this->data object

}

function reportCrowd() {
	alert('Thanks!\nYour feedback has made someone happier this hour!');
		
	// pop up modal to select from crowd levels
	// Ajax request with id to ajax_reportcrowd.php
	// 
	$('#reportCrowd').css('background-color','green').html('CROWD REPORT SENT');
	
}

</script>

<?php

	//get locations using current location
	// $this->listingsModel->getCurrentLocations($city,$lat,$lng);

?>

</head>
<body>

<div style="display: none;">
<span id="lat_ph"></span>
<span id="lng_ph"></span>
</div>

<div id="wrapper">

<div class="header">
	<h2>NEARBY HAPPY HOURS</h2>
	<p id="coords" style="font-size: .8em; margin-top: -10px;"></p>
		<div id="back-button-current">
		<p style="background-color: green; padding: 3px;"><a href="/?a=states"><i class="fa fa-list" aria-hidden="true"></i></a></p>
	</div>
</div>



<ul id="currentLocationsResults" class="listings">
	<p style="text-align: center; padding-top: 50px; margin: auto;"><img id="currentLocationImage" src="/images/loading.gif" ></p>
</ul>

<p style='clear: both; text-align: center'>Don't See Your Location?</p><p class='button' style='clear: both; width: 75%; border-radius: 5px;  text-align: center; margin: 0 auto 20px auto;'><a href='makinghappy.php'>Add Happy Hour Location</a></p>

<div style="display: none;">
<p style="margin-left: 20px;"><button onclick="geoFindMe()">Show my location</button></p>
<div id="out"></div>
</div>

<?php print $add_link; ?>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/view/snip_footer.php'); ?>

</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/view/snip_analytics.php'); ?>

</body>
</html>
