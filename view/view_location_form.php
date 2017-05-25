<?php
	
?>

<html>
<head>
	<?php include($_SERVER['DOCUMENT_ROOT'] . '/view/snip_dochead_meta.php'); ?>
	<script src="/lib/jquery.mask.js"></script>
	
	<title>Squirld Add Location Form / Find Your Happy Hour</title>

	<script>
	$(document).ready(function () {
		
		$('#loc_phone').mask('(000) 000-0000', {'translation': {0: {pattern: /[0-9*]/}}});
		
		$('#happyhour_label').on("click", function () {
		
			$('#happyhour_label').hide();
			$('#happyhour_hours').show();
					
		});
		
		$('#happyhour_close').on("click", function () {
		
			$('#happyhour_label').show();
			$('#happyhour_hours').hide();
		
		});
		
		$('#happyhour2').on("click", function () {
		
			$('#happyhour2').hide();
			$('#secondhappyhour').show();
					
		});
		
		$('#happyhour2_close').on("click", function () {
		
			$('#happyhour2').show();
			$('#secondhappyhour').hide();
		
		});
		
		$('#happyhourweekend_label').on("click", function () {
		
			$('#happyhourweekend_label').hide();
			$('#happyhourweekend_area').show();
					
		});
		
		$('#happyhourweekend_close').on("click", function () {
		
			$('#happyhourweekend_label').show();
			$('#happyhourweekend_area').hide();
		
		});

		$('#submitForm').click(function() {
			
			var err = 0;
			$('input[type=text],select').css('border-color', '#CCC');

			var inputVal = $('#loc_name').val();
			if ( inputVal.match( /([A-Za-z0-9])\w+/g )) {
				// nothing here
			} else {
				err++; 
			   	$('#loc_name').css('border-color', 'red');
			}

			var inputValAdd = $('#loc_address').val();
			if ( inputValAdd.match( /([A-Za-z0-9])\w+/g )) {
				// nothing here
			} else {
				err++; 
			   	$('#loc_address').css('border-color', 'red');
			}

			// if( $('#loc_name').val() == '' || $('#loc_name').val() == ' ') { 
			// 	err++; 
			// 	$('#loc_name').css('border-color', 'red');
			// }

			// if( $('#loc_address').val() == '' || $('#loc_address').val() == ' ') { 
			// 	err++; 
			// 	$('#loc_address').css('border-color', 'red');
			// }
			
			if( $('#loc_city').val() == '') { 
				err++; 
				$('#loc_city').css('border-color', 'red');
			}
			
			if( $('#loc_state option:selected').index() == 0) { 
				err++; 
				$('#loc_state').css('border-color', 'red');
			}
			
			if( $('#loc_hours_MF_desc').val() == '') { 
				err++; 
				$('#loc_hours_MF_desc').css('border-color', 'red');
			}
			
			if( $('#loc_user').val() == '') { 
				err++; 
				$('#loc_user').css('border-color', 'red');
			}
			
			if( $('#loc_rating_price option:selected').index() == 0) { 
				err++; 
				$('#loc_rating_price').css('border-color', 'red');
			}
			
			if( $('#loc_rating_ambiance option:selected').index() == 0) { 
				err++; 
				$('#loc_rating_ambiance ').css('border-color', 'red');
			}
			
			if( $('#loc_rating_noise option:selected').index() == 0) { 
				err++; 
				$('#loc_rating_noise').css('border-color', 'red');
			}
			
			if( $('#loc_rating_beer option:selected').index() == 0) { 
				err++; 
				$('#loc_rating_beer').css('border-color', 'red');
			}
			
			if( $('#loc_rating_wine option:selected').index() == 0) { 
				err++; 
				$('#loc_rating_wine').css('border-color', 'red');
			}
			
			if( $('#loc_rating_cocktails option:selected').index() == 0) { 
				err++; 
				$('#loc_rating_cocktails').css('border-color', 'red');
			}
			
			if( $('#loc_rating_food option:selected').index() == 0) { 
				err++; 
				$('#loc_rating_food').css('border-color', 'red');
			}
			
			if(err > 1) {
				alert('You Are Missing ' + err + ' fields. Please Complete The Red Boxes');
				return false;
			} else {
				console.log('form-submitted');
				$( "#location-form" ).submit();
			}
			
		});
		
	});
	</script>

</head>

<body>

<div id="wrapper">

<div class="header">
	<h2>ADD LOCATION</h2>
	<!-- <div id="back-button">
	<a href='?a=locations&city=<?= $_REQUEST['city'] ?>&state=<?= $_REQUEST['state'] ?>'><i class="fa fa-arrow-left" aria-hidden="true"></i></a>
	</div> -->
</div>
	
	<form id="location-form" action="makinghappy.php" method="POST">
	<input type="hidden" name="a" value="cheers" />
	
		<p>
		<label>Name:</label>
		<input type="text" id="loc_name" name="loc_name" required />
		</p>
		
		<p>
		<label>Address:</label>
		<input type="text" id="loc_address" name="loc_address" required />
		</p>
		
		<p>
		<label>City:</label>
		<input type="text" id="loc_city" name="loc_city" value="<?= $_REQUEST['city'] ?>" required />
		</p>
		
		<p>
		<label>State:</label>
		<select name='loc_state' id='loc_state'>
		<option value="-"> </option>
			<?php 
			
				$stateList = $this->location_form_model->getStateList(); 
				foreach($stateList as $key => $value) {
					if(strtoupper($_REQUEST['state']) == $value['stateabr']) { $selected = 'SELECTED'; } else { $selected = ''; }
					echo '<option id="loc_state" name="loc_state" value="' . $value['stateabr'] . '" maxlength="2" ' . $selected . ' required>' . $value['statename'] . '</option>';
				}
			?>
		</select>
		</p>
		
		<p>
		<label>Phone:</label>
		<input type="text"  id="loc_phone" name="loc_phone" />
		</p>
		
		<p>
		<label>Website:</label>
		<input type="text" name="loc_weblink" placeholder="http://" />
		</p>
		
		<!-- <p>
		<label>Background Image</label>
			<input type="file" id="loc_fileinput" name="loc_fileinput" />
		</p> -->

		<h2>Happy Hour Details</h2>
		
		<ul style="list-style: none; padding: 0;">
			<li><input type="checkbox" name="loc_beer" value="1" CHECKED /> Beer</li>
			<li><input type="checkbox" name="loc_wine" value="1" /> Wine</li>
			<li><input type="checkbox" name="loc_cocktails" value="1" /> Cocktails</li>
			<li><input type="checkbox" name="loc_food" value="1" /> Food</li>
		</ul>
		
		<p>
		<label>Location Type</label>
			<select name="loc_ambiance">
			<option value="Sports Bar">Sports Bar</option>
			<option value="Restaurant" SELECTED>Restaurant</option>
			<option value="Taphouse/Beer Garden">Taphouse/Beer Garden</option>
			<option value="Brewery Taps Only">Brewery Taps Only</option>
			<option value="Brewery w/restaurant">Brewery w/restaurant</option>
			<option value="Winery">Winery</option>
			<option value="Wine Bar">Wine Bar</option>
			<option value="Wine Bar w/restaurant">Wine Bar w/restaurant</option>
			</select>
		</p>

		<p id="happyhour_label"><i class="fa fa-plus-square" aria-hidden="true"></i> Add WEEKLY HAPPY HOUR times</p>
		
		<div id="happyhour_hours">
		<p><b>WEEKLY Happy Hour Times</b><br />
		<span style="font-size: .8em;">(These hours are not the locations normal business hours. They are only the Happy Hour times).</span>
		</p>
		<p>
		<input type="checkbox" name="loc_hours_WK_group[]" id="loc_hours_WK_group" CHECKED value="1"> M
		<input type="checkbox" name="loc_hours_WK_group[]" id="loc_hours_WK_group" CHECKED value="2"> T
		<input type="checkbox" name="loc_hours_WK_group[]" id="loc_hours_WK_group" CHECKED value="3"> W 
		<input type="checkbox" name="loc_hours_WK_group[]" id="loc_hours_WK_group" CHECKED value="4"> Th 
		<input type="checkbox" name="loc_hours_WK_group[]" id="loc_hours_WK_group" CHECKED value="5"> F 
		<input type="checkbox" name="loc_hours_WK_group[]" id="loc_hours_WK_group" value="6"> Sa 
		<input type="checkbox" name="loc_hours_WK_group[]" id="loc_hours_WK_group" value="7"> Su 

		<br /><br />
		
		<!-- <input type="text" name="loc_hours_MF_time" placeholder="4P-6P" size="12" required /> -->
		<select style="width:45%; display: inline-block; background-position-x: 90%;" name="loc_hours_MF_time_START">
			<option value="-" selected>START</option>
			<option value="01:00:00" class="AM">1:00 AM</option>
			<option value="02:00:00" class="AM">2:00 AM</option>
			<option value="03:00:00" class="AM">3:00 AM</option>
			<option value="04:00:00" class="AM">4:00 AM</option>
			<option value="05:00:00" class="AM">5:00 AM</option>
			<option value="06:00:00" class="AM">6:00 AM</option>
			<option value="07:00:00" class="AM">7:00 AM</option>
			<option value="08:00:00" class="AM">8:00 AM</option>
			<option value="09:00:00" class="AM">9:00 AM</option>
			<option value="10:00:00" class="AM">10:00 AM</option>
			<option value="11:00:00" class="AM">11:00 AM</option>
			<option value="12:00:00">12:00 PM</option>
			<option value="13:00:00">1:00 PM</option>
			<option value="14:00:00">2:00 PM</option>
			<option value="14:30:00">2:30 PM</option>
			<option value="15:00:00">3:00 PM</option>
			<option value="15:30:00">3:30 PM</option>
			<option value="16:00:00">4:00 PM</option>
			<option value="16:30:00">4:30 PM</option>
			<option value="17:00:00">5:00 PM</option>
			<option value="17:30:00">5:30 PM</option>
			<option value="18:00:00">6:00 PM</option>
			<option value="19:00:00">7:00 PM</option>
			<option value="20:00:00">8:00 PM</option>
			<option value="21:00:00">9:00 PM</option>
			<option value="22:00:00">10:00 PM</option>
			<option value="23:00:00">11:00 PM</option>
			<option value="00:00:00">MIDNIGHT/CLOSE</option>
		</select>
		&mdash;
		<select style="width:45%; display: inline-block;  background-position-x: 90%;" name="loc_hours_MF_time_END">
		<option value="-" selected>END</option>
						<option value="01:00:00" class="AM">1:00 AM</option>
			<option value="02:00:00" class="AM">2:00 AM</option>
			<option value="03:00:00" class="AM">3:00 AM</option>
			<option value="04:00:00" class="AM">4:00 AM</option>
			<option value="05:00:00" class="AM">5:00 AM</option>
			<option value="06:00:00" class="AM">6:00 AM</option>
			<option value="07:00:00" class="AM">7:00 AM</option>
			<option value="08:00:00" class="AM">8:00 AM</option>
			<option value="09:00:00" class="AM">9:00 AM</option>
			<option value="10:00:00" class="AM">10:00 AM</option>
			<option value="11:00:00" class="AM">11:00 AM</option>
			<option value="12:00:00">12:00 PM</option>
			<option value="13:00:00">1:00 PM</option>
			<option value="14:00:00">2:00 PM</option>
			<option value="14:30:00">2:30 PM</option>
			<option value="15:00:00">3:00 PM</option>
			<option value="15:30:00">3:30 PM</option>
			<option value="16:00:00">4:00 PM</option>
			<option value="16:30:00">4:30 PM</option>
			<option value="17:00:00">5:00 PM</option>
			<option value="17:30:00">5:30 PM</option>
			<option value="18:00:00">6:00 PM</option>
			<option value="18:30:00">6:30 PM</option>
			<option value="19:00:00">7:00 PM</option>
			<option value="20:00:00">8:00 PM</option>
			<option value="21:00:00">9:00 PM</option>
			<option value="22:00:00">10:00 PM</option>
			<option value="23:00:00">11:00 PM</option>
			<option value="00:00:00">MIDNIGHT/CLOSE</option>
		</select>
			
		<textarea id="loc_hours_MF_desc" name="loc_hours_MF_desc" placeholder="example: Happy hour description, such as $2 off all draft beers" style="width: 100%; height: 100px;" required></textarea>
		<p id="happyhour_close">close</p>
		
		</div>
		
		<p id="happyhour2"><i class="fa fa-plus-square" aria-hidden="true"></i> Add LATE-NIGHT HAPPY HOUR times</p>
		
		<div id="secondhappyhour">
		<label><b>Late-Night Happy Hour</b><br />
		<span style="font-size: .8em;">(If the location has a Late-Night Happy Hour on the same day as a regular Happy Hour. Example: Wed-Sat 10PM to 1AM in additition to their usual Happy Hour times)</span></label><br /><br />
		<input type="checkbox" name="loc_hours_WK2_group[]" id="loc_hours_WK2_group" CHECKED value="1"> M
		<input type="checkbox" name="loc_hours_WK2_group[]" id="loc_hours_WK2_group" CHECKED value="2"> T
		<input type="checkbox" name="loc_hours_WK2_group[]" id="loc_hours_WK2_group" CHECKED value="3"> W 
		<input type="checkbox" name="loc_hours_WK2_group[]" id="loc_hours_WK2_group" CHECKED value="4"> Th 
		<input type="checkbox" name="loc_hours_WK2_group[]" id="loc_hours_WK2_group" CHECKED value="5"> F 
		<input type="checkbox" name="loc_hours_WK2_group[]" id="loc_hours_WK2_group" value="6"> Sa 
		<input type="checkbox" name="loc_hours_WK2_group[]" id="loc_hours_WK2_group" value="7"> Su 
		<br />

		<select style="width:35%; display: inline-block; background-position-x: 90%;" name="loc_hours_MF_time_START_2">
			<option value="-" selected>START</option>
						<option value="01:00:00" class="AM">1:00 AM</option>
			<option value="02:00:00" class="AM">2:00 AM</option>
			<option value="03:00:00" class="AM">3:00 AM</option>
			<option value="04:00:00" class="AM">4:00 AM</option>
			<option value="05:00:00" class="AM">5:00 AM</option>
			<option value="06:00:00" class="AM">6:00 AM</option>
			<option value="07:00:00" class="AM">7:00 AM</option>
			<option value="08:00:00" class="AM">8:00 AM</option>
			<option value="09:00:00" class="AM">9:00 AM</option>
			<option value="10:00:00" class="AM">10:00 AM</option>
			<option value="11:00:00" class="AM">11:00 AM</option>
			<option value="12:00:00">12:00 PM</option>
			<option value="13:00:00">1:00 PM</option>
			<option value="14:00:00">2:00 PM</option>
			<option value="14:30:00">2:30 PM</option>
			<option value="15:00:00">3:00 PM</option>
			<option value="15:30:00">3:30 PM</option>
			<option value="16:00:00">4:00 PM</option>
			<option value="16:30:00">4:30 PM</option>
			<option value="17:00:00">5:00 PM</option>
			<option value="17:30:00">5:30 PM</option>
			<option value="18:00:00">6:00 PM</option>
			<option value="19:00:00">7:00 PM</option>
			<option value="20:00:00">8:00 PM</option>
			<option value="20:30:00">8:30 PM</option>
			<option value="21:00:00">9:00 PM</option>
			<option value="21:30:00">9:30 PM</option>
			<option value="22:00:00">10:00 PM</option>
			<option value="22:30:00">10:30 PM</option>
			<option value="23:00:00">11:00 PM</option>
			<option value="23:30:00">11:30 PM</option>
			<option value="00:00:00">MIDNIGHT/CLOSE</option>
		</select>
		&mdash;
		<select style="width:35%; display: inline-block;  background-position-x: 90%;" name="loc_hours_MF_time_END_2">
			<option value="-" selected>END</option>
						<option value="01:00:00" class="AM">1:00 AM</option>
			<option value="02:00:00" class="AM">2:00 AM</option>
			<option value="03:00:00" class="AM">3:00 AM</option>
			<option value="04:00:00" class="AM">4:00 AM</option>
			<option value="05:00:00" class="AM">5:00 AM</option>
			<option value="06:00:00" class="AM">6:00 AM</option>
			<option value="07:00:00" class="AM">7:00 AM</option>
			<option value="08:00:00" class="AM">8:00 AM</option>
			<option value="09:00:00" class="AM">9:00 AM</option>
			<option value="10:00:00" class="AM">10:00 AM</option>
			<option value="11:00:00" class="AM">11:00 AM</option>
			<option value="12:00:00">12:00 PM</option>
			<option value="13:00:00">1:00 PM</option>
			<option value="14:00:00">2:00 PM</option>
			<option value="14:30:00">2:30 PM</option>
			<option value="15:00:00">3:00 PM</option>
			<option value="15:30:00">3:30 PM</option>
			<option value="16:00:00">4:00 PM</option>
			<option value="16:30:00">4:30 PM</option>
			<option value="17:00:00">5:00 PM</option>
			<option value="17:30:00">5:30 PM</option>
			<option value="18:00:00">6:00 PM</option>
			<option value="18:30:00">6:30 PM</option>
			<option value="19:00:00">7:00 PM</option>
			<option value="20:00:00">8:00 PM</option>
			<option value="20:30:00">8:30 PM</option>
			<option value="21:00:00">9:00 PM</option>
			<option value="21:30:00">9:30 PM</option>
			<option value="22:00:00">10:00 PM</option>
			<option value="22:30:00">10:30 PM</option>
			<option value="23:00:00">11:00 PM</option>
			<option value="23:30:00">11:30 PM</option>
			<option value="00:00:00">MIDNIGHT/CLOSE</option>
		</select>
			
		<textarea id="loc_hours_MF_desc_2" name="loc_hours_MF_desc_2" placeholder="If same as first Happy Hour then skip this box." style="width: 100%; height: 75px;" required></textarea>
		
		<p id="happyhour2_close">close</p>
		
		</div>

		<p id="happyhourweekend_label"><i class="fa fa-plus-square" aria-hidden="true"></i> Add a WEEKEND Happy Hour Times</p>
		<br />

		<div id="happyhourweekend_area">
		<label><b>WEEKEND Happy Hour</b><br />
		<span style="font-size: .8em;">(Example: 11AM-1PM. Sometimes Weekend Happy Hours are also different in what's offered during the weekly happy hours. They may not have $1 of beer and wine, but $3 Mimosas or Bloody Marys. Use the decription area below so that information will be displayed instead.)</span></label><br /><br />
		<input type="checkbox" name="loc_hours_WKND_group[]" id="loc_hours_WKND_group" checked value="6"> Saturday 
		<input type="checkbox" name="loc_hours_WKND_group[]" id="loc_hours_WKND_group" checked value="7"> Sunday
		<br />
		<select style="width:35%; display: inline-block; background-position-x: 90%;" name="loc_hours_SU_time_START">
			<option value="" SELECTED>START</option>
						<option value="01:00:00" class="AM">1:00 AM</option>
			<option value="02:00:00" class="AM">2:00 AM</option>
			<option value="03:00:00" class="AM">3:00 AM</option>
			<option value="04:00:00" class="AM">4:00 AM</option>
			<option value="05:00:00" class="AM">5:00 AM</option>
			<option value="06:00:00" class="AM">6:00 AM</option>
			<option value="07:00:00" class="AM">7:00 AM</option>
			<option value="08:00:00" class="AM">8:00 AM</option>
			<option value="09:00:00" class="AM">9:00 AM</option>
			<option value="10:00:00" class="AM">10:00 AM</option>
			<option value="11:00:00" class="AM">11:00 AM</option>
			<option value="12:00:00">12:00 PM</option>
			<option value="13:00:00">1:00 PM</option>
			<option value="14:00:00">2:00 PM</option>
			<option value="14:30:00">2:30 PM</option>
			<option value="15:00:00">3:00 PM</option>
			<option value="15:30:00">3:30 PM</option>
			<option value="16:00:00">4:00 PM</option>
			<option value="16:30:00">4:30 PM</option>
			<option value="17:00:00">5:00 PM</option>
			<option value="18:00:00">6:00 PM</option>
			<option value="18:30:00">6:30 PM</option>
			<option value="19:00:00">7:00 PM</option>
			<option value="20:00:00">8:00 PM</option>
			<option value="21:00:00">9:00 PM</option>
			<option value="22:00:00">10:00 PM</option>
			<option value="23:00:00">11:00 PM</option>
			<option value="00:00:00">MIDNIGHT/CLOSE</option>
		</select>
		&mdash;
		<select style="width:35%; display: inline-block;  background-position-x: 90%;" name="loc_hours_SU_time_END">
			<option value="" SELECTED>END</option>
						<option value="01:00:00" class="AM">1:00 AM</option>
			<option value="02:00:00" class="AM">2:00 AM</option>
			<option value="03:00:00" class="AM">3:00 AM</option>
			<option value="04:00:00" class="AM">4:00 AM</option>
			<option value="05:00:00" class="AM">5:00 AM</option>
			<option value="06:00:00" class="AM">6:00 AM</option>
			<option value="07:00:00" class="AM">7:00 AM</option>
			<option value="08:00:00" class="AM">8:00 AM</option>
			<option value="09:00:00" class="AM">9:00 AM</option>
			<option value="10:00:00" class="AM">10:00 AM</option>
			<option value="11:00:00" class="AM">11:00 AM</option>
			<option value="12:00:00">12:00 PM</option>
			<option value="13:00:00">1:00 PM</option>
			<option value="14:00:00">2:00 PM</option>
			<option value="14:30:00">2:30 PM</option>
			<option value="15:00:00">3:00 PM</option>
			<option value="15:30:00">3:30 PM</option>
			<option value="16:00:00">4:00 PM</option>
			<option value="16:30:00">4:30 PM</option>
			<option value="17:00:00">5:00 PM</option>
			<option value="18:00:00">6:00 PM</option>
			<option value="18:30:00">6:30 PM</option>
			<option value="19:00:00">7:00 PM</option>
			<option value="20:00:00">8:00 PM</option>
			<option value="21:00:00">9:00 PM</option>
			<option value="22:00:00">10:00 PM</option>
			<option value="23:00:00">11:00 PM</option>
			<option value="00:00:00">MIDNIGHT/CLOSE</option>	
		</select>
		<textarea name="loc_hours_SU_desc" placeholder="example: Happy Hour description, such as Sunday $2 Mimosa's" style="width: 100%; height: 100px;"></textarea>
		
		<p id="happyhourweekend_close">close</p>

		</div>
		
		<label><b>WEEKLY HAPPY HOUR MENU SPECIALS / EVENTS</b></label><br />
		<span style="font-size: .8em;">(Example: Taco Tuesdays, Trivia Mondays, $3 Pint Thursdays)</span></b><br /><br />
		<!-- Monday<br /> --><input type="text" name="loc_hours_special_M" placeholder="Monday" style="width: 100%;" /><br />
		<!-- Tuesday<br /> --><input type="text" name="loc_hours_special_T" placeholder="Tuesday" style="width: 100%;" /><br />
		<!-- Wednesday<br /> --><input type="text" name="loc_hours_special_W" placeholder="Wednesday" style="width: 100%;" /><br />
		<!-- Thursday<br /> --><input type="text" name="loc_hours_special_R" placeholder="Thursday" style="width: 100%;" /><br />
		<!-- Friday<br /> --><input type="text" name="loc_hours_special_F" placeholder="Friday" style="width: 100%;" /><br />
		<!-- Saturday<br /> --><input type="text" name="loc_hours_special_S" placeholder="Saturday" style="width: 100%;" /><br />
		<!-- Sunday<br /> --><input type="text" name="loc_hours_special_U" placeholder="Sunday" style="width: 100%;" /><br /><br />
		</p>

		<h2>Ratings</h2>

		<p>
		<label>Price</label>
		<select id="loc_rating_price" name="loc_rating_price">
			<option value="-" SELECTED></option>
			<option value="1">$</option>
			<option value="2">$$</option>
			<option value="3">$$$</option>
			<option value="4">$$$$</option>
		</select>
		</p>
		
		<p>
		<label>Ambiance</label>
		<select id="loc_rating_ambiance" name="loc_rating_ambiance">
			<!-- <option value="-" SELECTED></option>
			<option value="4">Excellent</option>
			<option value="3">Good</option>
			<option value="2">Fair</option>
			<option value="1">Awful</option> -->
			
			<option value="4">Casual, interesting, unique and original</option>
			<option value="3">Typical Restaurant/Bar</option>
			<option value="2">A bit on the fancy side</option>
			<option value="1">Your local dive bar</option>
		</select>
		</p>
		
		<p>
		<label>Noise (during happy hour times)</label>
		<select id="loc_rating_noise" name="loc_rating_noise">
		<option value="-" SELECTED></option>
			<option value="1">A) normal conversation</option>
			<option value="2">B) conference room meeting</option>
			<option value="3">C) Have to talk like you're on bluetooth in your car</option>
			<option value="4">D) Can't hear your own thoughts</option>
		</select>
		</p>
		
		<p>
		<label>Beer Selection</label>
		<select id="loc_rating_beer" name="loc_rating_beer">
		<option value="-" SELECTED></option>
			<option value="4">A) Awesome Selection! More than 20 taps</option>
			<option value="3">B) Pretty Damn Decent Selection with less than 10 taps</option>
			<option value="2">C) Not my first choice, some bottle and only few drafts</option>
			<option value="1">D) Bottle Only - Beer fridge at home has better selection</option>
			<option value="0">No Beer Served</option>
		</select>
		</p>
		
		<p>
		<label>Wine Selection</label>
		<select id="loc_rating_wine" name="loc_rating_wine">
		<option value="-" SELECTED></option>
			<option value="4">A) Amazing! Like being in a winery</option>
			<option value="3">B) Wonderful. Excellent selection of reds, whites, etc.</option>
			<option value="2">C) Lovely. Easy to find something you will like</option>
			<option value="1">D) Better selection at home</option>
			<option value="0">No Wine Served</option>
		</select>
		</p>
		
		<p>
		<label>Cocktail Selection</label>
		<select id="loc_rating_cocktails" name="loc_rating_cocktails">
		<option value="-" SELECTED></option>
			<option value="4">A) Amazing! High-end brands, generous pours/option>
			<option value="3">B) Brands are midrange, medium pours</option>
			<option value="2">C) Brands are midrange, cheap-ass pours though</option>
			<option value="1">D) I make better drinks at home</option>
			<option value="0">No Cocktails Served</option>
		</select>
		</p>
		
		<p>
		<label>Food Selection</label>
		<select id="loc_rating_food" name="loc_rating_food">
		<option value="-" SELECTED></option>
			<option value="4">A) These Appetizers could be meals</option>
			<option value="3">B) Smaller portions of regular appetizers</option>
			<option value="2">C) Chips & Salsa, wings, sliders, etc.</option>
			<option value="1">D) Everything is dropped in a basket of oil for 3-5 minutes</option>
			<option value="0">No Food Served</option>
		</select>
		</p>

		<p>
		<label>Your email (required for stewardship of this location)</label>
		<input type="text" id="loc_user" name="loc_user" placeholder="example.email@yahoo.com" required /><br />
		<input type="checkbox" name="loc_notify" value="1" /> Notify me when someone edits this happy hour
		</p>
		
		<p style="background-color: rgba(0,0,0, .1); padding: 10px;">
		<label>Feedback</label>
		Please take a moment and let us know what we are missing or doing right.<br /><br />
		<textarea name="loc_feedback" style="width: 100%; height: 100px"></textarea>
		</p>
		
		
		<p id="submitForm" class="button" style="text-align: center; margin-bottom: 40px;" value="Add Location">Add Happy Hour Location</p>
		
	</form>
	
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/view/snip_footer.php'); ?>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/view/snip_analytics.php'); ?>

</body>
</html>
