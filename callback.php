<?php
/**
 * Spotify Api module for xoops
 *
 * @package    spotifyapi
 * @subpackage page-level
 * @author     Squiz Pty Ltd <products@squiz.net>
 * @copyright  2023 Michael Albertsen (www.culex.dk)
 * @since      1.0
 * @min_xoops  2.5.9
 */

use XoopsModules\Spotifyapi\{Spotifyapi_Helper};

require_once dirname(__DIR__, 2) . '/mainfile.php';
include __DIR__ . '/preloads/autoloader.php';
require_once __DIR__ . '/include/common.php';
require_once XOOPS_ROOT_PATH . '/class/template.php';
$helper = Spotifyapi_Helper::getInstance();
$GLOBALS['xoopsLogger']->activated = false;
$block = [];
$clientid = $helper->getConfig('spotifyapiclientid');
$clientsecret = $helper->getConfig('spotifyapiclientsecret');
$clientredirecturi = $helper->getConfig('spotifyapiredirecturi');
$db = new XoopsModules\Spotifyapi\Spotifyapi_db();

$session = new XoopsModules\Spotifyapi\Spotifyapi_Session(
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

// Request an access token using the code from Spotify
try {
    $session->requestAccessToken($_GET['code']);
} catch (\XoopsModules\Spotifyapi\SpotifyWebAPIAuthException $e) {
} catch (\XoopsModules\Spotifyapi\SpotifyWebAPIException $e) {
}
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
header("Content-type: application/javascript");
header('Location: app.php');
die();