<html>
<head>
	<?php include($_SERVER['DOCUMENT_ROOT'] . '/view/snip_dochead_meta.php'); ?>

	<title>Squirld Thanks You!</title>

	<script>
	$(document).ready(function () {



	});
	</script>

</head>

<body>

<div id="wrapper">

<div class="header">
	<h2>LOCATION ADDED!</h2>
	<div id="back-button">
	<a href='/?a=locations&city=<?= $_REQUEST['city'] ?>&state=<?= $_REQUEST['state'] ?>'><i class="fa fa-arrow-left" aria-hidden="true"></i></a>
	</div>
</div>

<div class="content">

	<p style="padding: 20px 20px 0 20px; text-align: center; font-size: 2.0em; font-weight: 700; margin-bottom: 0;">Cheers!<br/>
	<span style="font-size: 1.6em;">A tap has been Added!</span>
	</p>

	<p style="text-align: center; padding-bottom: 40px;">
		<a href='/?a=states&city=<?= $_REQUEST['city'] ?>&state=<?= $_REQUEST['state'] ?>'>STATES</a> /
		<a href='/?a=cities&city=<?= $_REQUEST['city'] ?>&state=<?= $_REQUEST['state'] ?>'>CITIES</a> /
		<a href='/happyplace.php?id=<?= $_REQUEST['id'] ?>'>VIEW YOUR LISTING</a>

	</p>

	<p class='button' style='clear: both; text-align: center; margin: 0;'><a href='makinghappy.php'>Add Another Happy Hour Location</a>
	</p>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/view/snip_footer.php'); ?>
/**
 * @Author: James McCarthy <sjamesmccarthy>
 * @Date:   12-25-2016 9:36:16
 * @Email:  james@jmccarthy.xyz
 * @Filename: view_location_form_thankyou.php
 * @Last modified by:   sjamesmccarthy
 * @Last modified time: 05-18-2017 6:58:33
 * @Copyright: 2017
 */


</div>
</body>
</html>
