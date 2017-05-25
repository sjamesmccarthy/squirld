<?php


	function phone() {
		if(  preg_match( '/^\+\d(\d{3})(\d{3})(\d{4})$/', $this->data['location']['phone'],  $matches ) )
		{
		    $result = $matches[1] . '-' .$matches[2] . '-' . $matches[3];
		    echo $result;
		}
	}

	function getHappyHours($data) {


		$hh_data = array();
		$day = date('N');
		$day_long = date('l');

		foreach ($data['happyhours'] as $key => $value) {
			
			
			// Date conversion to 12 hour
			$start_time = date('g:i A', strtotime($value['start']));
			$end_time = date('g:i A', strtotime($value['end']));
				
			//Monday-Friday
			if( in_array($day, explode(',', $value['days'])) && $value['type'] == 1) {
			
				$current_hour = (int) round( date('H:i A', time()), 0, PHP_ROUND_HALF_UP);
				#$current_hour = (int) 20;
				$start_time_hour = (int) date('H', strtotime($value['start']));
				$end_time_hour = (int) date('H', strtotime($value['end']));
				$happy_state =  $current_hour - $end_time_hour;
	
				if($current_hour >= 0 && $current_hour <= 12) {
				#echo "loop1";
					if ($current_hour >= $start_time_hour && $current_hour >= $end_time_hour)
					{
					   	$hh_data['openclosed'] = 'rgba(255,255,255,1)';
					} else {
						$hh_data['openclosed'] = 'rgba(0,0,0,.3)';
					}
				} else {
				#echo "loop2 - " . $start_time_hour . "/" . $end_time_hour;
					if ($current_hour >= $start_time_hour && $current_hour <= $end_time_hour || $end_time_hour < 12)
					{
					   	$hh_data['openclosed'] = 'rgba(255,255,255,1)';
					} else {
						$hh_data['openclosed'] = 'rgba(0,0,0,.3)';
					}
				}
				
				/* if ($current_hour > $start_time_hour && $current_hour < $end_time_hour)
				{
				   	$hh_data['openclosed'] = 'rgba(255,255,255,1)';
				} else {
					$hh_data['openclosed'] = 'rgba(0,0,0,.3)';
				} */
				
				//settype($start_time_hour, int);
				//settype($end_time_hour, int);
				//settype($current_hour, int);
				// var_dump($end_time_hour);
				// var_dump($current_hour);

				/* if($current_hour >= $start_time_hour) {
					$hh_data['openclosed'] = 'rgba(255,255,255,1)';
				} else {
					$hh_data['openclosed'] = 'rgba(0,0,0,.3)';
				}
				
				if($current_hour >= $end_time_hour) {
					$hh_data['openclosed'] = 'rgba(0,0,0,.3)';
				} */
				
				$hh_data['MF'] = $start_time . " - " . $end_time . " " .  strtoupper(date('l')) . " HAPPY HOUR";
					if( isSet($value['description']) ) {
						$hh_data['MF_desc'] = $value['description'];
					}
				$hh_data['MF_day'] = $value['days'];
				$hh_data['MF_today'] = $day;
			}

			// Specials
			if( in_array($day, explode(',', $value['days']))  && $value['type'] == 2) {
				$hh_data['specials'] = "<span style='background-color: #FFF; color: #000; font-size: .8em; letter-spacing: normal;'>" . $value['description'] . "</span>";
			} 
			
			// Second Happy Hours
			if( in_array($day, explode(',', $value['days'])) && $value['type'] == 3) {
				
				$current_hour = (int) round( date('H:i A', time()), 0, PHP_ROUND_HALF_UP);
				#$current_hour = 22;
				$start_time_hour = (int) date('H', strtotime($value['start']));
				$end_time_hour = (int) date('H', strtotime($value['end']));
				$happy_state =  $current_hour - $end_time_hour;
				
				if($current_hour >= 0 && $current_hour <= 12) {
				#echo "loop1";
					if ($current_hour >= $start_time_hour && $current_hour >= $end_time_hour)
					{
					   	$hh_data['openclosedlate'] = 'rgba(255,255,255,1)';
					} else {
						$hh_data['openclosedlate'] = 'rgba(0,0,0,.3)';
					}
				} else {
				#echo "loop2";
					if ($current_hour >= $start_time_hour)
					{
					   	$hh_data['openclosedlate'] = 'rgba(255,255,255,1)';
					} else {
						$hh_data['openclosedlate'] = 'rgba(0,0,0,.3)';
					}
				}
				
				//settype($end_time_hour, int);
				//settype($current_hour, int);
				// var_dump($end_time_hour);
				//var_dump($current_hour);
				
				/* if($current_hour >= $start_time_hour) {
					$hh_data['openclosedlate'] = 'rgba(255,255,255,1)';
				} else {
					$hh_data['openclosedlate'] = 'rgba(0,0,0,.3)';
				}
				
				if($current_hour >= $end_time_hour) {
					$hh_data['openclosed'] = 'rgba(0,0,0,.3)';
				}
				*/
				
				$hh_data['MF_LN'] = $start_time . " - " . $end_time . " " .  strtoupper(date('l')) . " LATE-NIGHT";
					if( isSet($value['description']) ) {
						$hh_data['MFLN_desc'] = $value['description'];
					}
				$hh_data['MFLN_day'] = $value['days'];
				$hh_data['MFLN_today'] = $day;
			}
			
			if(!isSet($hh_data['MF'])) {
				$hh_data['MF'] = "NO " . strtoupper(date('l')) . " HAPPY HOUR TODAY";
			}

		}
		
		return($hh_data);
	}

	function getRatings($data) {

		$rating_data = '';

		// print_r($data['ratings']);

		foreach ($data['ratings'] as $rate_key => $rate_val) {
		
			$ratings_cnt = explode('/', $rate_val);
		     	$stars = '';
		     	
		     	if($rate_key == "price") {
		     		$icon= 'usd';
		     		$margin = ' margin-right: 5px';
		     	} else {
		     		$icon= 'star';
		     		$margin = '';
		     	}
		     	
			
			for ($i = 1;$i <= 4;$i++) {
				if($i <= round($rate_val, 0, PHP_ROUND_HALF_DOWN)) {
				    $stars .= '<i class="fa fa-' . $icon. '" style="color: #000;' . $margin . '"></i>';
				} else {
					$stars .= '<i class="fa fa-' . $icon. '" style="color: #AAA;' . $margin . '"></i>';
				}
			}

			$rating_data .= '<li>' . $stars . ' ' . $rate_key . ' <!-- (' . $ratings_cnt[1] . ') --></li>';

		}

		return($rating_data);
	}
	
	function getCrowdData($data) {

		$crowd_data = '';
		
		foreach ($data['ratings'] as $rate_key => $rate_val) {
		
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

			$crowd_data .= '<li>' . $stars . "</li>";
			// "(" . $desc . ")<br />";

		}
		}

		return($crowd_data);
	}
	

?>

<html>
<head>
	<?php include($_SERVER['DOCUMENT_ROOT'] . '/view/snip_dochead_meta.php'); ?>
	<title>Squirld <?= $this->data['location']['name'] ?> / Find Your Happy Hour</title>

<script>
$(document).ready(function () {

	$('#reportSend').on("click", function() {

		// alert('Thank you for flagging this location. We will take a look into things.'); 
		$.ajax({
		  
			url: 'ajax.php',
		    type : 'POST',
		    data: $('#reportForm').serialize(), 
		    beforeSend: function(){
		    	$('#reportEdit').hide();
		    	$('#reportForm').hide();
		        $('#reportImage').show();
		        console.log('beforeSend');
		    },
		    complete: function(){
		        $('#reportImage').hide();
		        $('#reportEdit').show();
		        console.log('complete');
		    },
		    success: function(data){
		       	$('#reportEdit').html(data);
				$('#reportForm').hide();
				console.log('success-'+data);
		    },
		    error: function(e) {
				console.log(e.message);
		  	}

		});

	});

	$('#reportEdit').on("click", function() {

		$('#reportForm').toggle();

 		var tag = $("#reportForm");
    		$('html,body').animate({scrollTop: tag.offset().top},'slow');

	});

	$('#ratings_btn').on("click", function() {
		$('.details-list-ratings').toggle();
	});
	
	$('#description_short').on("click", function() {
		$('#description_short').hide();
		$('#description_long').show();
	});
	
	$('#description_long').on("click", function() {
		$('#description_long').hide();
		$('#description_short').show();
	});
	
});

window.onload = geoFindMe;

function errorCallback(error) {
  alert('ERROR(' + error.code + '): ' + error.message);
};

function geoFindMe() {
console.log('geoFindMe');
  var output = document.getElementById("out");

  if (!navigator.geolocation){
    output.innerHTML = "<p>Geolocation is not supported by your browser</p>";
    return;
  }

  function success(position) {
    var latitude  = position.coords.latitude;
    var longitude = position.coords.longitude;

    output.innerHTML = '<p>Latitude is ' + latitude + '° <br>Longitude is ' + longitude + '°</p>';

    //var img = new Image();
    //img.src = "https://maps.googleapis.com/maps/api/staticmap?center=" + latitude + "," + longitude + "&zoom=13&size=300x300&sensor=false";

    //output.appendChild(img);
    
   // document.getElementById("lat-lng").innerHTML = latitude + "," + longitude;
    
    var MapURL = '<iframe style="height: 300px; width: 99%;" src="https://www.google.com/maps/embed/v1/directions?origin=' + latitude + ',' +  longitude + '&destination=<?php echo urlencode($this->data["location"]["address"]); ?>,<?php echo urlencode($this->data["location"]["city"]); ?>,<?php echo urlencode($this->data["location"]["state"]); ?>&zoom=11&center=<?php echo urlencode($this->data["location"]["lat"]); ?>,<?php echo urlencode($this->data["location"]["lng"]); ?>&key=AIzaSyBY8pwzZ0cmfOdFoS0l_wiGRxkaIXK6K5s"</iframe>';
     
    console.log(MapURL);
    document.getElementById("mapURL").innerHTML = MapURL;
    
  }

  function error() {
    output.innerHTML = "Unable to retrieve your location";
  }

  output.innerHTML = "<p>Locating…</p>";

  navigator.geolocation.getCurrentPosition(success, error);
}
	
</script>

</head>
<body>

<div id="wrapper">

<div class="header">
		<h2 style="padding: 0"><?= $this->data['location']['name'] ?></h2>	
		<p><?= $this->data['location']['ambiance'] ?></p>
		
	<!-- <div id="back-button">
	<a href='/?a=locations&city=<?= $_REQUEST['city'] ?>&state=<?= $_REQUEST['state'] ?>'><i class="fa fa-arrow-left" aria-hidden="true"></i></a>
	</div> -->
</div>



<!-- google maps API: AIzaSyBY8pwzZ0cmfOdFoS0l_wiGRxkaIXK6K5s -->
<!-- origin: <?php echo urlencode($_REQUEST['city']); ?>,<?php echo urlencode($_REQUEST['state']); ?> -->

<span id="mapURL"></span>

<div class="details-hours">

	<?php
		$happyhours = getHappyHours($this->data);
		echo "<p style='font-size: .9em; font-weight: 400;'><span style='color:" . $happyhours['openclosed'] . ";'><i class='fa fa-clock-o'></i> " . ucwords($happyhours['MF']) . "</span>";
		if(isSet($happyhours['MF_LN'])) {
			echo "<br /><span style='font-size: .9em; font-weight: 400; color:" . $happyhours['openclosedlate'] . "'><i class='fa fa-moon-o'></i>" . $happyhours['MF_LN'] . "</span>";
		}
		echo "</p>";
	?>

</div>

<div class="details">

<?php
	
		//$happyhours_list = preg_replace("/;/", "<!-- split --><br />&mdash;", $happyhours['MF_desc']);
		if( strlen($happyhours['MF_desc']) >= 100) {
			$happyhours_list = substr($happyhours['MF_desc'], 0, 100) . " ... ";
		} else {
			$happyhours_list = $happyhours['MF_desc'];
		}
			
		$happyhours_list_long = $happyhours['MF_desc'];
				
		echo "<p id='description_short'>" . $happyhours_list . "</p>";
		echo "<p id='description_long' style='display: none;'>" . $happyhours_list_long  . "</p>";
		//substr("abcdef", -3, 1); 
		
		if($happyhours['specials'] != '') {
			echo '<p style="padding: 10px 0 20px 0; margin: 0;"><b>' . strtoupper(date('l')) . ' SPECIALS</b><br />';
			echo $happyhours['specials'];
			echo '</p>';
		}
	
	?>

	<!--
	<p style="padding: 20px 0 0 0; margin: 0;"><b>SERVING</b><br />

		<?php

		$serving = array();

		if($this->data['location']['beer'] == 1) {
			$serving[] = 'Beer';
		} 
		if($this->data['location']['wine'] == 1) {
			$serving[] = 'Wine';
		} 
		if($this->data['location']['cocktails'] == 1) {
			$serving[] = 'Cocktails';
		} 
		if ($this->data['location']['food'] == 1) {
			$serving[] = 'Food';
		}

		$serving_format = rtrim(implode(', ', $serving), ',');
		echo $serving_format;
		?>

	</p>
	-->
	
	<p style="padding: 10px 0 0 0; margin: 0;"><b>AVERAGE CROWD</b></p>
	<ul class="details-list-crowd">
		<?php
			print getCrowdData($this->data);
		?>
	</ul>
	
	<ul class="details-list">
	
		<li><i class="fa fa-location-arrow"></i> <a href="#"><?=  ucwords(strtolower($this->data['location']['address'])) ?></a>
		<!-- <br /><span style="font-size: .8em; margin-left:42px;"><?= $this->data['location']['city'] ?>, <?= $this->data['location']['state'] ?> <?= $this->data['location']['zip'] ?></span>--></li>
		<?php if($this->data['location']['phone'] != '') { ?> <li><a href="tel:<?= $this->data['location']['phone'] ?>"><i class="fa fa-phone"></i> <?= $this->data['location']['phone'] ?></a></li> <?php } ?>
		
		<?php 
			if($this->data['location']['weblink'] != '') { 

				if (!preg_match("/http:\/\//i", $this->data['location']['weblink'])) {
					$this->data['location']['weblink'] = "http://" . $this->data['location']['weblink'];
				}

		?> <li><a target="_new" href="<?= $this->data['location']['weblink'] ?>"><i class="fa fa-external-link"></i> <?php if( strlen($this->data['location']['weblink']) >= 30 ) { print substr($this->data['location']['weblink'], 0, 30) . ' ...'; } else { print $this->data['location']['weblink']; } ?></a></li> <?php } ?>
		
		<li><i class="fa fa-star"></i> <span id="ratings_btn">ratings</span></li> 
			<ul class="details-list-ratings" style="display: none">

			<?php 
				// substr('abcdef', 0, 4);
				print getRatings($this->data);
			
			?>

	</ul>

</div>


<p style="margin-left: 20px; display: none;"><button onclick="geoFindMe()">Show my location</button></p>
<div id="out" style="display: none"></div>

<div class="footer" style="text-align: center">

	<p class="edit" id="reportEdit"><i class="fa fa-warning" aria-hidden="true"></i> REPORT INACCURATE INFORMATION</p>
	<img id="reportImage" src="/images/loading.gif" >
	<form id="reportForm" method="POST">
	<input type="hidden" name="reportListingName" value="<?= $this->data['location']['name'] ?>" />
	<input type="hidden" name="reportListingId" value="<?= $this->data['location']['id'] ?>" />
	<p id="reportFormMsg" style="margin-top: 10px;"">
		<textarea style="height:100px;" name="reportText" placeholder="Tell us what is innaccurate and we will make sure it gets up to date. Thanks!"></textarea>
	</p>
	<p><input style="width: 100%; border: 1px solid #CCC; padding: 10px;" type="text" name="reportEmail" placeholder="email (only if you want a reply" /></p>
	<p id="reportSend" class="button" style="padding: 10px;">SEND REPORT</p>
	</form>
	
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/view/snip_footer.php'); ?>

</div>

<?php

	if($_REQUEST['d'] == 1) {
		print "<pre>";
		print_r($this->data);
		print_r($happyhours );
		print "</pre>";
	}

?>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/view/snip_analytics.php'); ?>

</body>
</html>
