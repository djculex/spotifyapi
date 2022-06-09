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

$weekParam = isset($_GET['week']) ? $_GET['week'] : '';
$yearParam = isset($_GET['year']) ? $_GET['year'] : '';
$charttype = isset($_GET['type']) ? $_GET['type'] : '';

$d = new db();
$form = new form();
date_default_timezone_set($timez);
$weekly = 0;
$chart = [];
$i = 0;
$greatestgainer = 0;

if ($weekParam !="" && $yearParam !="") {
	if ($charttype == 'accumulated') {
		$td = $yd = array();
		$arg = $d->getStartAndEndDate($weekParam,$yearParam);
		$d->thisweek_start = $d->getMinMaxDate($type='min');
		$d->thisweek_end = date_format(date_create_from_format('d-m-Y H:i:s', $arg['end_date']), 'd-m-Y');
		$d->lastweek_start = date('d-m-Y', strtotime($d->thisweek_start." -1 week"));
		$d->lastweek_end = date('d-m-Y', strtotime($d->thisweek_end." -1 week"));
		
		$td = $d->getTopSingleWeek();
		$yd = $d->getLwTopSingleWeek();
	}
	if ($charttype == 'classic') {
		$td = $yd = array();
		$arg = $d->getStartAndEndDate($weekParam,$yearParam);
		$d->thisweek_start = date_format(date_create_from_format('d-m-Y H:i:s', $arg['start_date']), 'd-m-Y');
		$d->thisweek_end = date_format(date_create_from_format('d-m-Y H:i:s', $arg['end_date']), 'd-m-Y');
		$d->lastweek_start = date('d-m-Y', strtotime($d->thisweek_start." -1 week"));
		$d->lastweek_end = $d->thisweek_start;
		
		$td = $d->getTopSingleWeek();
		$yd = $d->getLwTopSingleWeek();
	}
	foreach($td as $tv) {
		$chart[$i]['lw'] = _SPOTIFYAPI_NEWCHARTENTRY;
		foreach ($yd as $yv) {
			$chart[$i]['tw'] = (int) $tv['pos'];
					
			if ($tv['artist'] == $yv['artist'] AND $tv['title'] == $yv['title']){
				$chart[$i]['lw'] = (int) $yv['pos']; 
				if ($yv['pos'] - $tv['pos'] > $greatestgainer) {
					$greatestgainer = $yv['pos'] - $tv['pos'];
					$greatestgainerid = $i;
				}
				if ($tv['pos'] > $yv['pos']) {
					$chart[$i]['dir'] = "&#8595;";
				}
				if ($tv['pos'] < $yv['pos']) {
					$chart[$i]['dir'] = "&#8593;";
				}
				if ($tv['pos'] == $yv['pos']) {
					$chart[$i]['dir'] = "&#183;";
				}
				
			} 
			$chart[$i]['artist'] = $tv['artist']; 
			$chart[$i]['title'] = $tv['title']; 
			$chart[$i]['image'] = $tv['image']; 
			$chart[$i]['album'] = $tv['album']; 
			$chart[$i]['year'] = (int) $tv['releaseyear']; 
			$chart[$i]['artlink'] = $tv['artistlink'];
			$chart[$i]['pop'] = (int) $tv['popularity']; 
			$chart[$i]['ggn'] = $chart[$i]['lw'] - $chart[$i]['tw'];
			if ($chart[$i]['ggn'] > $greatestgainer) {
				$greatestgainer = $chart[$i]['ggn'];
				$greatestgainerid = $i;
			}			
			$chart[$i]['gg'] = false;			
		}
		$i += 1;
	}
	$chart[$greatestgainerid]['gg'] = true;
} 

$tit = sprintf(_SPOTIFYAPI_CHARTTITLE, $d->selecttoplimit);

if ($weekParam !="" && $yearParam !="") {
		$weekly = 1;
		$sub = sprintf(
			_SPOTIFYAPI_CHARTSUBTITLE, 
			$d->chart_day_count,
			date_format(date_create_from_format('d-m-Y', $d->thisweek_start), 'd-m-Y'), 
			$d->chart_day_count,
			date_format(date_create_from_format('d-m-Y', $d->thisweek_end), 'd-m-Y')
		);
}

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
$GLOBALS['xoopsTpl']->assign('charttype',$charttype);

$GLOBALS['xoopsTpl']->assign('lastweek_text', _SPOTIFYAPI_STARTTIME);
$GLOBALS['xoopsTpl']->assign('weeklyLink', XOOPS_URL . "/modules/spotifyapi/week.php");	
$GLOBALS['xoopsTpl']->assign('weekly', $weekly);	

$GLOBALS['xoopsTpl']->assign('alltime', _SPOTIFYAPI_ALLTIME_TOP);
$GLOBALS['xoopsTpl']->assign('alltimeLink', XOOPS_URL . "/modules/spotifyapi/alltime.php");

$GLOBALS['xoopsTpl']->assign('starttimeLink', XOOPS_URL . "/modules/spotifyapi");

$GLOBALS['xoopsTpl']->assign('lastmonth', _SPOTIFYAPI_LASTMONTH_TOP);
$GLOBALS['xoopsTpl']->assign('lastmonthLink', XOOPS_URL . "/modules/spotifyapi/month.php");

include XOOPS_ROOT_PATH.'/footer.php';
