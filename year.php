<?php

use XoopsModules\Spotifyapi\{
	Session,
	SpotifyWebAPI,
	db,
	form
};

use XoopsModules\Spotifyapi;
use XoopsModules\Spotifyapi\Constants;

require_once dirname(__DIR__,2) . '/mainfile.php';
include __DIR__ . '/preloads/autoloader.php';
require_once __DIR__ . '/include/common.php';

$GLOBALS['xoopsLogger']->activated = false;

//include __DIR__ . '/header.php';
include XOOPS_ROOT_PATH.'/header.php';

$helper = \XoopsModules\Spotifyapi\Helper::getInstance();
$timez = $helper->getConfig('spotifyapitimezones');

$d = new db();
$form = new form();
date_default_timezone_set($timez);

$year = (isset($_POST['year'])) ? $_POST['year'] : '';

header('Content-Type: application/json');
echo json_encode($d->getWeeks($year));

//include XOOPS_ROOT_PATH.'/footer.php';
