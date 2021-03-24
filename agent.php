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

		//$calansarki = $api->getMyCurrentPlaybackInfo($options);
		$calansarki = $api->getMyRecentTracks($options,array('limit'=>$helper->getConfig('spotifyapinumbertoshow')));
		
		//$value = json_encode($calansarki);
		$value = json_decode(json_encode($calansarki), true);
		//var_dump("<pre>",$value,"</pre>");
		//echo $_GET['callback']."(".json_encode($value).");";
		for ($i=0; $i < count($value['items']); $i++) {		
				$db->image = $value['items'][$i]['track']["album"]["images"][0]["url"];

				$dta = new DateTime($value['items'][$i]['played_at'], new DateTimeZone('UTC'));
				$dta->setTimezone(new DateTimeZone('Europe/Copenhagen'));
				$db->times = $dta->format('d-m-Y H:i:s');
				
				$db->artist = $value['items'][$i]['track']["artists"][0]["name"];
				
				$db->title = $value['items'][$i]['track']["name"];
				
				$db->albumtitle = $value['items'][$i]['track']["album"]["name"];
				
				$dt = new DateTime($value['items'][$i]['track']["album"]['release_date']);
				$dt->format('Y');
				$db->release_year = $dt->format('Y');
				
				if ($db->songexists() == false){
					$db->loadSave($type='save');
				}
		}
		header("Content-Type: application/json; charset=UTF-8");
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
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Credentials: true");
		header("Access-Control-Max-Age: 1000");
		header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
		header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");
		header('Content-type: application/json');

		header('Location: ' . $session->getAuthorizeUrl($options)."&origin=*&callback=".$_GET['callback']);
		die();
		
	}	
