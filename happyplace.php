<?php
/**
 * @Author: James McCarthy <sjamesmccarthy>
 * @Date:   05-02-2017 12:11:58
 * @Email:  james@jmccarthy.xyz
 * @Filename: happyplace.php
 * @Last modified by:   sjamesmccarthy
 * @Last modified time: 05-17-2017 6:57:00
 * @Copyright: 2017
 */

ini_set('display_errors', 1);
date_default_timezone_set('America/Los_Angeles');

error_reporting(E_ALL ^ E_NOTICE);

spl_autoload_register(function ($class) {
    include 'controller/' . $class . '.php';
});

if(!isset($_REQUEST['a'])) { $_REQUEST['a'] = 'showform'; }

$location_detail = new LocationDetail;

	$layout = $location_detail->showLocation($_REQUEST['id']);

	print $layout;

?>
