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

use Xmf\Request;
use XoopsModules\Spotifyapi;
use XoopsModules\Spotifyapi\{Spotifyapi_db};

require_once dirname(__DIR__, 2) . '/mainfile.php';
include __DIR__ . '/preloads/autoloader.php';
require_once __DIR__ . '/include/common.php';
require_once XOOPS_ROOT_PATH . '/class/template.php';
$GLOBALS['xoopsLogger']->activated = false;
$block = [];

$helper = Spotifyapi\Spotifyapi_Helper::getInstance();
$clientid = $helper->getConfig('spotifyapiclientid');
$clientsecret = $helper->getConfig('spotifyapiclientsecret');
$clientredirecturi = $helper->getConfig('spotifyapiredirecturi');

$token = getToken($clientid, $clientsecret);
$db = new Spotifyapi_db();

// Get Action type
$op = Request::getString('op', "", 'GET');
$myToken = Request::getString('token', "", 'GET');

switch ($op) {
    case 'token':
    default:
        getToken($clientid, $clientsecret);
        break;
    case 'getList':
        try {
            getRecentlyPlayed();
        } catch (Exception $e) {
        }
        break;
    case 'getOAuth':
        getOAuth($clientid, $clientsecret, $clientredirecturi);
        break;
}

function getOAuth($clientid, $clientsecret, $clientredirecturi): void
{
    $session = new XoopsModules\Spotifyapi\Spotifyapi_Session(
        $clientid,
        $clientsecret,
        $clientredirecturi
    );

    $sessionOptions = [
        'auto_refresh' => true,
    ];

    $api = new XoopsModules\Spotifyapi\SpotifyWebAPI($sessionOptions, $session);

    if (isset($_GET['code'])) {
        $session->requestAccessToken($_GET['code']);
        $api->setAccessToken($session->getAccessToken());

        $options = [
            'scope' => [
                'user-read-email',
                'user-read-currently-playing',
                'user-read-playback-state',
                'user-read-recently-played'
            ],
        ];
        //$GLOBALS['xoopsLogger']->activated = true;
        //$calansarki = $api->getMyCurrentPlaybackInfo($options);
        $calansarki = $api->getMyRecentTracks($options, array('limit' => $helper->getConfig('spotifyapinumbertoshow')));
        //$calansarki = $api->getMyRecentTracks($options,array('limit'=>50));
        //header("Content-Type: application/json; charset=UTF-8");
        //echo json_encode($calansarki);
        //$value = json_decode(json_encode($calansarki), true);
        echo $session->getAuthorizeUrl($options) . "&origin=agent";
        die();
    } else {
        $options = [
            'scope' => [
                'user-read-email',
                'user-read-currently-playing',
                'user-read-playback-state',
                'user-read-recently-played'
            ],
        ];
        //header('Location: ' . $session->getAuthorizeUrl($options)."&origin=agent2&callback=".$_GET['callback']);
        echo $session->getAuthorizeUrl($options) . "&origin=agent";
        die();
    }
}

function getToken($clientid, $clientsecret)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://accounts.spotify.com/api/token');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Basic ' . base64_encode($clientid . ':' . $clientsecret)));
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:x.x.x) Gecko/20041107 Firefox/x.x");
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $json = curl_exec($ch);
    $json = json_decode($json);
    curl_close($ch);
    header("Content-Type: application/json; charset=UTF-8");
    echo json_encode($json->access_token);
}

function getTokenStat($clientid, $clientsecret)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://accounts.spotify.com/api/token');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Basic ' . base64_encode($clientid . ':' . $clientsecret)));
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:x.x.x) Gecko/20041107 Firefox/x.x");
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $json = curl_exec($ch);
    $json = json_decode($json);
    curl_close($ch);
    return $json->access_token;
}

/**
 * @throws Exception
 */
function getRecentlyPlayed()
{
    $token = getTokenStat($clientid, $clientsecret);
    //print_r($token);
    $data = array(
        "limit" => 50,
    );

    $full_url = "https://api.spotify.com/v1/me/player/recently-played?" . http_build_query($data);
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $full_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer ' . $token
        )
    ));

    $value = curl_exec($curl);
    $value = json_decode($value, true);

    for ($i = 0; $i < count($value['items']); $i++) {
        if ($value['items'][$i]['track']["album"]["images"][0]["url"] != "") {
            $db->image = $value['items'][$i]['track']["album"]["images"][0]["url"];
        } else {
            $db->image = XOOPS_URL . "/modules/spotifyapi/assets/images/defaultalbumcover.png";
            //echo $db->image;
        }

        $dta = new DateTime($value['items'][$i]['played_at'], new DateTimeZone('UTC'));
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

        if (!$db->songexists()) {
            $db->loadSave($type = 'save');
        }
    }
    //header("Content-Type: application/json; charset=UTF-8");
    //echo json_encode($value);
}













/*
$session = new XoopsModules\Spotifyapi\Session(
    $clientid,
    $clientsecret,
    $clientredirecturi
);

$sessionOptions = [
    'auto_refresh' => true,
];

$api = new XoopsModules\Spotifyapi\SpotifyWebAPI($sessionOptions, $session);

if (isset($_GET['code'])) {
    $db = new db();
    $session->requestAccessToken($_GET['code']);
    $api->setAccessToken($session->getAccessToken());

    $options = [
        'scope' => [
            'user-read-email',
            'user-read-currently-playing',
            'user-read-playback-state',
            'user-read-recently-played'
        ],
    ];
    //$GLOBALS['xoopsLogger']->activated = true;
    //$calansarki = $api->getMyCurrentPlaybackInfo($options);
    //$calansarki = $api->getMyRecentTracks($options,array('limit'=>$helper->getConfig('spotifyapinumbertoshow')));
    $calansarki = $api->getMyRecentTracks($options,array('limit'=>50));

    //$value = json_encode($calansarki);
    $value = json_decode(json_encode($calansarki), true);
    //print_r($value);
    //echo $_GET['callback']."(".json_encode($value).");";
    for ($i=0; $i < count($value['items']); $i++) {

            if ($value['items'][$i]['track']["album"]["images"][0]["url"] != ""){
                $db->image = $value['items'][$i]['track']["album"]["images"][0]["url"];
            } else {
                $db->image = XOOPS_URL . "/modules/spotifyapi/assets/images/defaultalbumcover.png";
                //echo $db->image;
            }

            $dta = new DateTime($value['items'][$i]['played_at'], new DateTimeZone('UTC'));
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

            if (!empty($value['items'][$i]["context"]["external_urls"]["spotify"])){
                $db->userplaylist = $value['items'][$i]["context"]["external_urls"]["spotify"];
            } else {
                $db->userplaylist = "";
            }
            //$db->updateurls();

            if ($db->songexists() == false){
                $db->loadSave($type='save');
            }
    }
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Max-Age: 1000");
    header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
    header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");
    header("Content-type: application/javascript");
    //echo json_encode($value, true);
    die();
} else {
    $options = [
        'scope' => [
            'user-read-email',
            'user-read-currently-playing',
            'user-read-playback-state',
            'user-read-recently-played'
        ],
    ];
    //header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Origin: ");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Max-Age: 1000");
    header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
    header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");
    header("Content-type: application/javascript");

    header('Location: ' . $session->getAuthorizeUrl($options)."&origin=*&callback=".$_GET['callback']);
    die();

}
*/
