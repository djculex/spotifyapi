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

$GLOBALS['xoopsOption']['template_main'] = 'spotifyapi_indexfile.tpl';

$GLOBALS['xoopsLogger']->activated = true;

include __DIR__ . '/header.php';
include XOOPS_ROOT_PATH.'/header.php';

$helper = \XoopsModules\Spotifyapi\Helper::getInstance();
$timez = $helper->getConfig('spotifyapitimezones');

// Bootstrap CSS
//$GLOBALS['xoTheme']->addStylesheet('https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css');
$GLOBALS['xoTheme']->addScript('https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js');

$d = new db();
date_default_timezone_set($timez);

$td = $d->getTop();
$yd = $d->getLwTop();

$chart = [];
$i = 0;

foreach($td as $tv) {
	$chart[$i]['lw'] = _SPOTIFYAPI_NEWCHARTENTRY;
	foreach ($yd as $yv) {
		$chart[$i]['tw'] = (int) $tv['pos'];
		 		
		if ($tv['artist'] == $yv['artist'] AND $tv['title'] == $yv['title']){
			$chart[$i]['lw'] = (int) $yv['pos']; 
		} 
		$chart[$i]['artist'] = $tv['artist']; 
		$chart[$i]['title'] = $tv['title']; 
		$chart[$i]['image'] = $tv['image']; 
		$chart[$i]['album'] = $tv['album']; 
		$chart[$i]['year'] = (int) $tv['releaseyear']; 
		$chart[$i]['artlink'] = $tv['artistlink'];
		$chart[$i]['pop'] = (int) $tv['popularity']; 		
	}
	$i += 1;
}


$tit = sprintf(_SPOTIFYAPI_CHARTTITLE, $d->selecttoplimit);
$sub = sprintf(
	_SPOTIFYAPI_CHARTSUBTITLE, 
	date_format(date_create_from_format('d-m-Y H:i:s', $d->lastweek), 'd-m-Y'), 
	date_format(date_create_from_format('d-m-Y H:i:s', $d->today), 'd-m-Y')
);
$GLOBALS['xoopsTpl']->assign('title',$tit);
$GLOBALS['xoopsTpl']->assign('subtitle',$sub);
$GLOBALS['xoopsTpl']->assign('chart', $chart);	
include XOOPS_ROOT_PATH.'/footer.php';
