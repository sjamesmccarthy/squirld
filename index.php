<?php
/**
 * @Author: James McCarthy <sjamesmccarthy>
 * @Date:   05-02-2017 12:11:58
 * @Email:  james@jmccarthy.xyz
 * @Filename: index.php
 * @Last modified by:   sjamesmccarthy
 * @Last modified time: 05-17-2017 6:56:49
 * @Copyright: 2017
 */

ini_set('display_errors', 1);
error_reporting(E_ALL ^ E_NOTICE);

spl_autoload_register(function ($class) {
    include 'controller/' . $class . '.php';
});

if(!isset($_REQUEST['a'])) { $_REQUEST['a'] = 'showsplash'; }

$listings= new Listings;

switch($_REQUEST['a']) {

	case "states":
	$layout = $listings->showStates();
	break;

	case "cities":
	$layout = $listings->showCities($_REQUEST['state']);
	break;

	case "locations":
	$layout= $listings->showLocations($_REQUEST['city'],$_REQUEST['state']);
	break;

	case "currentlocations":
	// $listings->showCurrentLocations(0,0);
	include($_SERVER['DOCUMENT_ROOT'] . '/view/view_listings_currentlocations.php');
	break;

	case "showsplash":
	$layout = $listings->showSplash();
	break;

}

	print $layout;

?>
