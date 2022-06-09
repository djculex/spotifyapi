<?php

use XoopsModules\Spotifyapi\{
	Session,
	SpotifyWebAPI,
	db,
	form
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

$GLOBALS['xoTheme']->addScript('https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js');

$d = new db();
$form = new form();
date_default_timezone_set($timez);
$weekly = 0;
$chart = [];
$i = 0;

$d->lastweek_end = date('d-m-Y', strtotime($d->thisweek_end . " -30 days"));
$td = $d->getTopSingleWeek();
	
foreach($td as $tv) {
	$chart[$i]['tw'] = (int) $tv['pos'];
	$chart[$i]['artist'] = $tv['artist']; 
	$chart[$i]['title'] = $tv['title']; 
	$chart[$i]['image'] = $tv['image']; 
	$chart[$i]['album'] = $tv['album']; 
	$chart[$i]['year'] = (int) $tv['releaseyear']; 
	$chart[$i]['artlink'] = $tv['artistlink'];
	$chart[$i]['pop'] = (int) $tv['popularity']; 		
	$i += 1;
}

 
$tit = sprintf(_SPOTIFYAPI_CHARTTITLE, $d->selecttoplimit);

$weekly = 0;
		
$sub = sprintf(
	_SPOTIFYAPI_CHARTSUBTITLE, 
	$d->chart_day_count,
	date_format(date_create_from_format('d-m-Y', $d->lastweek_end), 'd-m-Y'), 
	$d->chart_day_count,
	date_format(date_create_from_format('d-m-Y', $d->thisweek_end), 'd-m-Y')
);

$dropstart = $form->dropdown('startingDate', $d->parseDistinctDates($d->getDistinctYears(),$arg='year') , $selected = null, $sep = '');
$dropend = $form->dropdown('endDate', array('',''));
$radiobtn = $form->radiobutton('charttype', 'charttype', _SPOTIFYAPI_CHARTACCUMUM);

$GLOBALS['xoopsTpl']->assign('title',$tit);
$GLOBALS['xoopsTpl']->assign('subtitle',$sub);
$GLOBALS['xoopsTpl']->assign('dropstart',$dropstart);
$GLOBALS['xoopsTpl']->assign('dropend',$dropend);
$GLOBALS['xoopsTpl']->assign('radiobutton',$radiobtn); 
$GLOBALS['xoopsTpl']->assign('sbmit',$form->submitBtn('spotifyapisubmitbutton', _SPOTIFYAPI_FILTER_TITLE, $sep = '<br>'));
$GLOBALS['xoopsTpl']->assign('chart', $chart);

$GLOBALS['xoopsTpl']->assign('lastweek_text', _SPOTIFYAPI_STARTTIME);
$GLOBALS['xoopsTpl']->assign('weeklyLink', XOOPS_URL . "/modules/spotifyapi/week.php");	
$GLOBALS['xoopsTpl']->assign('weekly', $weekly);	

$GLOBALS['xoopsTpl']->assign('alltime', _SPOTIFYAPI_ALLTIME_TOP);
$GLOBALS['xoopsTpl']->assign('alltimeLink', XOOPS_URL . "/modules/spotifyapi/alltime.php");

$GLOBALS['xoopsTpl']->assign('starttimeLink', XOOPS_URL . "/modules/spotifyapi");

$GLOBALS['xoopsTpl']->assign('lastmonth', _SPOTIFYAPI_LASTMONTH_TOP);
$GLOBALS['xoopsTpl']->assign('lastmonthLink', XOOPS_URL . "/modules/spotifyapi/month.php");

include XOOPS_ROOT_PATH.'/footer.php';