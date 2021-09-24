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
	$db = new db();
	
	$session = new XoopsModules\Spotifyapi\Session(
		$clientid,
		$clientsecret,
		$clientredirecturi
	);

	$state = $_GET['state'];

	// Fetch the stored state value from somewhere. A session for example
	
	$storedState = $_SESSION['state'];
	
	if ($state !== $storedState) {
		// The state returned isn't the same as the one we've stored, we shouldn't continue
		die('State mismatch');
	}

	// Request a access token using the code from Spotify
	$session->requestAccessToken($_GET['code']);
	$db->code = $_GET['code'];
	$db->setConfig('code');
	
	
	$accessToken = $session->getAccessToken();
	$refreshToken = $session->getRefreshToken();

	// Store the access and refresh tokens somewhere. In a session for example
	$db->accessToken = $accessToken;
	$db->refreshToken = $refreshToken;
	$db->setConfig('accessToken');
	$db->setConfig('refreshToken');

	// Send the user along and fetch some data!
	header('Location: app.php');
	die();