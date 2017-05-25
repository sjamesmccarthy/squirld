<?php
/**
 * @Author: James McCarthy <sjamesmccarthy>
 * @Date:   05-02-2017 12:11:58
 * @Email:  james@jmccarthy.xyz
 * @Filename: makinghappy.php
 * @Last modified by:   sjamesmccarthy
 * @Last modified time: 05-17-2017 7:00:46
 * @Copyright: 2017
 */

ini_set('display_errors', 1);
error_reporting(E_ALL ^ E_NOTICE);

spl_autoload_register(function ($class) {
    include 'controller/' . $class . '.php';
});

if(!isset($_REQUEST['a'])) { $_REQUEST['a'] = 'showform'; }

$location = new LocationForm;

switch($_REQUEST['a']) {

	case "cheers":
	$layout = $location->LocationFormAdd();
	break;

	case "roundtwo":
	$layout = $location->LocationFormEdit($_REQUEST['tap']);
	break;

	case "cashout":
	$layout= $locations->LocationFormDelete($_REQUEST['cashout']);
	break;

	case "showform":
	$layout = $location->showForm();
	break;

}


	//$core->CreatePage();
	print $layout;

?>
