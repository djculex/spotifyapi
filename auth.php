<?php
use XoopsModules\Spotifyapi\{
	Session,
	SpotifyWebAPI,
	db
};

use XoopsModules\Spotifyapi;
use XoopsModules\Spotifyapi\Constants;

require_once dirname(__DIR__,2) . '/mainfile.php';
include __DIR__ . '/preloads/autoloader.php';
require_once __DIR__ . '/include/common.php';
require_once XOOPS_ROOT_PATH . '/class/template.php';
$helper = \XoopsModules\Spotifyapi\Helper::getInstance();
$GLOBALS['xoopsLogger']->activated = false;
	$block = [];
	
	/** @var Spotifyapi\Helper $helper */
    $helper = Spotifyapi\Helper::getInstance();
	$clientid = $helper->getConfig('spotifyapiclientid');
	$clientsecret = $helper->getConfig('spotifyapiclientsecret');
	$clientredirecturi = $helper->getConfig('spotifyapiredirecturi');
	
	$session = new XoopsModules\Spotifyapi\Session(
		$clientid,
		$clientsecret,
		$clientredirecturi
	);

	$state = $session->generateState();
	$_SESSION['state'] = $state;
	$options = [
		'scope' => [
				'user-read-currently-playing',
				'user-read-recently-played'
		],
		'state' => $state,
	];
	header("Content-type: application/javascript");
	header('Location: ' . $session->getAuthorizeUrl($options));
	die();