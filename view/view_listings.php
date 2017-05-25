<?php

	// echo "view_listings(" . $_REQUEST['a'] . ")<br />";
	
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

?>

<html>
<head>	
	<?php include($_SERVER['DOCUMENT_ROOT'] . '/view/snip_dochead_meta.php'); ?>
	<title>Squirld <?= strtoupper($_REQUEST['a']) ?> / Find Your Happy Hour</title>
</head>
<body>

<div id="wrapper">

<div class="header">
	<h2><?= $_REQUEST['a'] ?> 
	
		<?php
			if(isSet($_REQUEST['state']) && !$_REQUEST['city']) {
				echo " &mdash; " . $_REQUEST['state'];
			}
			
			if(isSet($_REQUEST['city'])) {
				echo " &mdash; " . $_REQUEST['city'] . ", " . $_REQUEST['state'];
			}
		?>
	</h2>
	
	<div id="back-button-current">
		<p style="background-color: green; padding: 3px;"><a href="/?a=currentlocations"><i class="fa fa-location-arrow" aria-hidden="true"></i></a></p>
	</div>
	
	<div id="back-button-arrows">
		<?php
			// from locations list
			if(isSet($_REQUEST['state']) && isSet($_REQUEST['city'])) {
				echo "<a href='/?a=cities&state=" . $_REQUEST['state'] . "'><i class='fa fa-arrow-left' aria-hidden='true'></i></a>";
			}
			
			//from cities
			if(isSet($_REQUEST['state']) && $_REQUEST['a'] == 'cities') {
				echo "<a href='/?a=states'><i class='fa fa-arrow-left' aria-hidden='true'></i></a>";	 
			}
			
			//from
			if($_REQUEST['a'] == 'states') {
				echo '<i class="fa fa-home" aria-hidden="true" style="color: #FFF"></i>';
			}
			 
		?>
	</div>

</div>

<ul class="listings">

<?php
	$add_link = '';
	$noData = 0;

	foreach ($this->data as $key => $value) {

		if($_REQUEST['a'] == 'states') {
			$data = explode("/", $value);
			if($data[0] == "No States Found") { $noDatatError = "No States Found"; $noData = 1; } else {
			// changed to data[0] from datat[1]
			echo "<li class='listings-hr'><p><a href='?a=cities&state=", strtoupper($data[0]), "'>", strtoupper($data[1]), "</a> (", $data[2], ")</p>";
			$add_link = "<p style='text-align: center'>Don't See Your State?</p><p class='button' style='width: 75%; border-radius: 5px; text-align: center; margin: 0 auto 20px auto;'><a href='makinghappy.php?state=" . $_REQUEST['state'] . "'>Add A Happy Hour Location</a></p>";
			}
		} 
		else if($_REQUEST['a'] == 'cities') {
			$data = explode("/", $value);
			if($data[0] == "No Cities Found") { $noDatatError = "No Cities Found"; $noData = 1; } else {		
			echo "<li class='listings-hr'><p><a href='?a=locations&city=", urlencode($data[0]), "&state=", strtoupper($data[1]), "'>", strtoupper($data[0]), "</a> (", $data[2], ")</p>";
			$add_link = "<p style='text-align: center'>Don't See Your City?</p><p class='button' style='width: 75%; border-radius: 5px; text-align: center; margin: 0 auto 20px auto;'><a href='makinghappy.php?city=" . $_REQUEST['city'] . "'>Add A Happy Hour Location</a></p>";
			}
			
		}
		else if($_REQUEST['a'] == 'currentlocations') {
			
			include_once($_SERVER['DOCUMENT_ROOT'] . '/view/view_listings_currentlocations.php');			
		}
		else if($_REQUEST['a'] == 'locations') {
	
			//print_r($value);
			//$data = explode("/", $value);
			if($value[0] == "No Locations Found") { $noDatatError = "No Locations Found"; $noData = 1; } else {
			
			// Date conversion to 12 hour
			$start_time = date('g:i A', strtotime($value['start']));
			$end_time = date('g:i A', strtotime($value['end']));


				echo "<li class='listings-hr'>";
				echo "<p style='float: left; width: 65%;'>";
				echo "<a href='happyplace.php?city=" . $_REQUEST['city'] . "&state=" . $_REQUEST['state'] . "&id=" . $value['id'] . "&name=" . urlencode($value['name']) . "'>";
				echo $value['name'] . "</a><br />";
				// echo " &mdash; " . $value['ambiance'] . "<br />";
				echo "<span style='font-size: .8em'>";
				
				$serving = array();

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
				echo $serving_format . "<br />";
				
				// echo $value['address'], "<br />";
				echo $start_time, " - ", $end_time, "<br />" . ucwords(strtolower($value['address'])), "</span><br />";

				echo "</p>";
				echo "<p style='float: right; margin-right: 20px; margin-top: 37px;'>";

					print getCrowdData($value['ratings']);
					
					echo '<br />
					<span style="font-size: .5em; padding: 0; margin: 0 0 0 5px;">average crowd rating</span>';

				// <i class='fa fa-map-marker' aria-hidden='true'></i>
				echo "</p><br style='clear: both' /></li>";
				$add_link = "<p style='clear: both; text-align: center'>Don't See Your Location?</p><p class='button' style='clear: both; width: 75%; border-radius: 5px;  text-align: center; margin: 0 auto 20px auto;'><a href='makinghappy.php?city=" . $_REQUEST['city'] . "&state=" . $_REQUEST['state'] . "'>Add Happy Hour Location</a></p>";
				}
		}	
		
	}
		
		if($noData == 1) {
			echo '<p style="padding: 20px; text-align: center; font-size: 1.2em; font-weight: 700">Yikes, this tap has busted!<br/><span style="font-size: 1.6em;">' . strtoupper($noDatatError) . '</span></p>';
			echo "<hr><p style='text-align: center'>Don't See Your Location?</p><p class='button' style='clear: both; width: 75%; border-radius: 5px; text-align: center; margin: 0 auto 20px auto;'><a href='makinghappy.php'>Add Happy Hour Location</a></p>"; 
		}
?>

</ul>

<?php print $add_link; ?>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/view/snip_footer.php'); ?>

</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/view/snip_analytics.php'); ?>

</body>
</html>
