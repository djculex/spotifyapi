<?php
use XoopsModules\Spotifyapi\{
	Session,
	SpotifyWebAPI
};

use XoopsModules\Spotifyapi;
use XoopsModules\Spotifyapi\Constants;

require_once dirname(__DIR__,2) . '/mainfile.php';
include __DIR__ . '/preloads/autoloader.php';
require_once __DIR__ . '/include/common.php';
require_once __DIR__ . '/include/functions.php';
require_once XOOPS_ROOT_PATH . '/class/template.php';
require_once XOOPS_ROOT_PATH . '/modules/spotifyapi/blocks/recentlyplayed.php';
$helper = \XoopsModules\Spotifyapi\Helper::getInstance();

	$tpl = new \XoopsTpl();
	$tpl->caching = 0;
	$result = b_spotifyapi_show_recently_played();
	$tpl->assign('block', $result);
	$tpl->display(XOOPS_ROOT_PATH . "/modules/spotifyapi/templates/blocks/spotify_recentlyplayed_block.tpl");
	spotifyapi_cors();
	//$tpl->display('db:spotify_recentlyplayed_block');

$GLOBALS['xoopsLogger']->activated = false;