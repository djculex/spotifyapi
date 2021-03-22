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
	$db = new db();
	$block = $db->getSongs();
	/*
	$tpl = new \XoopsTpl();
	$tpl->caching = 0;
	$tpl->display(XOOPS_ROOT_PATH . "/modules/spotifyapi/templates/blocks/spotify_block.tpl");
	//$tpl->display('db:spotify_block.tpl');
	*/
	//var_dump("<pre>",$block,"</pre>");
	return $block;
}