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

use XoopsModules\Spotifyapi;
use XoopsModules\Spotifyapi\Constants;

require_once dirname(__DIR__, 2) . '/mainfile.php';
include __DIR__ . '/preloads/autoloader.php';
require_once __DIR__ . '/include/common.php';
require_once XOOPS_ROOT_PATH . '/class/template.php';
$GLOBALS['xoopsLogger']->activated = false;
$block = [];

/** @var Spotifyapi\Helper $helper */
$helper = Spotifyapi\Spotifyapi_Helper::getInstance();
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