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

use XoopsModules\Spotifyapi\{Spotifyapi_db, Spotifyapi_Helper, SpotifyWebAPI};
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
$db = new Spotifyapi_db();

$options = [
    'auto_refresh' => true,
];

$api = new SpotifyWebAPI($options);

// Fetch the saved access token from somewhere. A session for example.
$accessToken = $db->getConfig('accessToken');

//$accessToken = $_SESSION['accessToken'];
$api->setAccessToken($accessToken);

$calansarki = $api->getMyRecentTracks(array('limit' => 50));

//$value = json_encode($calansarki);
$value = json_decode(json_encode($calansarki), true);
//print_r($value);
//echo $_GET['callback']."(".json_encode($value).");";
for ($i = 0; $i < count($value['items']); $i++) {

    if ($value['items'][$i]['track']["album"]["images"][0]["url"] != "") {
        $db->image = $value['items'][$i]['track']["album"]["images"][0]["url"];
    } else {
        $db->image = XOOPS_URL . "/modules/spotifyapi/assets/images/defaultalbumcover.png";
        //echo $db->image;
    }

    try {
        $dta = new DateTime($value['items'][$i]['played_at'], new DateTimeZone('UTC'));
    } catch (Exception $e) {
    }
    $dta->setTimezone(new DateTimeZone('Europe/Copenhagen'));
    $db->times = $dta->format('d-m-Y H:i:s');

    $db->artist = $value['items'][$i]['track']["artists"][0]["name"];

    if (!empty($value['items'][$i]['track']["artists"][0]["external_urls"]["spotify"])) {
        $db->artisturl = $value['items'][$i]['track']["artists"][0]["external_urls"]["spotify"];
    } else {
        $db->artisturl = "";
    }
    $db->title = $value['items'][$i]['track']["name"];

    $db->albumtitle = $value['items'][$i]['track']["album"]["name"];

    $dt = new DateTime($value['items'][$i]['track']["album"]['release_date']);
    $dt->format('Y');
    $db->release_year = $dt->format('Y');

    if (!empty($value['items'][$i]['track']["popularity"])) {
        $db->popularity = $value['items'][$i]['track']["popularity"];
    } else {
        $db->popularity = 0;
    }

    if (!empty($value['items'][$i]["context"]["external_urls"]["spotify"])) {
        $db->userplaylist = $value['items'][$i]["context"]["external_urls"]["spotify"];
    } else {
        $db->userplaylist = "";
    }
    //$db->updateurls();

    if (!$db->songexists() and !$db->songdublicate()) {
        $db->loadSave($type = 'save');
    }
}

header("Content-type: application/javascript");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");

//header("Content-type: application/javascript");
//echo json_encode($value, true);
die();
