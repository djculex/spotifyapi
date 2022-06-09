<?php
use XoopsModules\Spotifyapi\{
	Session,
	SpotifyWebAPI,
	db,
	form
};

use XoopsModules\Spotifyapi;
use XoopsModules\Spotifyapi\Constants;
use Xmf\Request;

require_once dirname(__DIR__,2) . '/mainfile.php';
include __DIR__ . '/preloads/autoloader.php';
require_once __DIR__ . '/include/common.php';
require_once XOOPS_ROOT_PATH . '/class/template.php';
$helper = \XoopsModules\Spotifyapi\Helper::getInstance();
$GLOBALS['xoopsLogger']->activated = true;

	// Get Action type
	$op = Request::getString('op', "", 'GET');
	$myToken = Request::getString('data', "", 'GET');
	
	//classes
	$database = new db();
	$form = new form();
	
	switch ($op) {
    case 'spotiGetEndDates':
		default:
		$ret = $database->parseDistinctDates($database->getDistinctReducedStartDates($myToken), $arg='');
		echo $form->dropdown('finishedDate', $ret , $selected = null, $sep = '<br>');
		//getToken ($clientid, $clientsecret);
		break;
	}