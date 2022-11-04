<?php
use XoopsModules\Spotifyapi\{
	Session,
	SpotifyWebAPI,
	db
};
require_once dirname(__DIR__) . '/include/common.php';
require_once XOOPS_ROOT_PATH . '/class/template.php';

function b_spotifyapi_show_recently_played()
{
	$helper = \XoopsModules\Spotifyapi\Helper::getInstance();
	$timez = $helper->getConfig('spotifyapitimezones');
	date_default_timezone_set($timez);
	//sleep(5);
	$db = new db();
	$block = $db->getSongs();
	return $block;
	
}