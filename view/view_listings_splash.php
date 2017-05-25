<?php

	$myIconArray = array("glass", "smile-o");
	$icon_idx = array_rand($myIconArray, 1);	
?>

<html>
<head>
	<?php include($_SERVER['DOCUMENT_ROOT'] . '/view/snip_dochead_meta.php'); ?>
	
	<title>Squirld &mdash; Find Your Happy Hour</title>

	<script>
	$(document).ready(function () {
			
		$( "#splashtagline" ).fadeTo( 1500, .25, function() {

    			$( "#splashtagname" ).fadeIn(1000, function() { 
    				var left = $('#splashicon').offset().top;
 
    				$("#wrapper .splashtitle").css({top:top}).animate({"top":"-550px"}, 3000, function() { 
    					// $(location).attr('href', '/?a=currentlocations');
    				});
    				$(location).attr('href', '/?a=currentlocations');
    			})
  		});
 
  
	});
	</script>

</head>

<style>
html { 
        background: url('../images/bg2.jpg') no-repeat center center fixed; 
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
}

#wrapper {
	/*background-color: #0099cc;	*/
	color: #0099cc;
	 text-shadow: #FFF 1px 1px 15px;
    -webkit-font-smoothing: antialiased;
	min-height: 100%;
	min-width: 100%;
}

#wrapper .splashtitle {
position: absolute;
top: 25%;
	width: 100%;
	text-align: center;
	text-transform: uppercase;
}

#wrapper .splashtitle > h2 
{
	font-weight: 700;
	margin: 0;
}

#splashicon 
{

	font-size: 12em;
	margin: 0;
}

#splashtagline, #splashtagname
{
	font-size: 2.6em;
	text-align: center;
}

</style>

<body>

<div id="wrapper">

	<div class="splashtitle">
	<p id="splashicon"><i class="fa fa-<?= $myIconArray[$icon_idx] ?>" aria-hidden="true"></i></p>
	<h2 id="splashtagline">squirld</h2>
	<h2 id="splashtagname" style="display: none;">find your happy</h2>
	</div>
	
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/view/snip_analytics.php'); ?>

</body>
</html>
