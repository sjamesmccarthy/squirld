<?php

/**
 * @Author: James McCarthy <sjamesmccarthy>
 * @Date:   05-02-2017 12:11:58
 * @Email:  james@jmccarthy.xyz
 * @Filename: ajax.php
 * @Last modified by:   sjamesmccarthy
 * @Last modified time: 05-17-2017 7:01:43
 * @Copyright: 2017
 */

$to ='not.happy@squirld.com';
$subject = "SQUIRLD REPORT: INACCURATE DATA for " . $_POST['reportListingName'];
$txt = "INACCURATE DATA for " . $_POST['reportListingName'] . " / " . $_POST['reportListingId'] . "\n" . $_POST['reportText'];

if($_POST['reportEmail']) {
	$txt .= "Reported by " . $_POST['reportEmail'];
}

$headers = "From: hikerbikerwriter@gmail.com" . "\r\n";
$headers .= "Reply-To: hikerbikerwriter@gmail.com";
mail($to,$subject,$txt,$headers);

echo "CHEERS AND THANK YOU!<br /><span style='font-size: .6em;'>report sent to " . $to . "</span>";
