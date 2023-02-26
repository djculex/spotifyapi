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
use XoopsModules\Spotifyapi\{
	Session,
	SpotifyWebAPI,
	Spotifyapi_db,
	Spotifyapi_form
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

$helper = \XoopsModules\Spotifyapi\Spotifyapi_Helper::getInstance();
$timez = $helper->getConfig('spotifyapitimezones');

$GLOBALS['xoTheme']->addScript('https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js');

$d = new Spotifyapi_db();
$form = new Spotifyapi_form();
date_default_timezone_set($timez);
$weekly = 0;
	
$td = $d->getTopSingleWeek();
$yd = $d->getLwTopSingleWeek();

$tit = sprintf(_SPOTIFYAPI_CHARTTITLE, $d->selecttoplimit);

$weekly = 1;
$sub = sprintf(
	_SPOTIFYAPI_CHARTSUBTITLE, 
	$d->chart_day_count,
	date_format(date_create_from_format('d-m-Y', $d->thisweek_start), 'd-m-Y'), 
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
$GLOBALS['xoopsTpl']->assign('chart', $d->parseArrayDouble($td, $yd));

$GLOBALS['xoopsTpl']->assign('lastweek_text', _SPOTIFYAPI_STARTTIME);
$GLOBALS['xoopsTpl']->assign('weeklyLink', XOOPS_URL . "/modules/spotifyapi/week.php");	
$GLOBALS['xoopsTpl']->assign('weekly', $weekly);	

$GLOBALS['xoopsTpl']->assign('alltime', _SPOTIFYAPI_ALLTIME_TOP);
$GLOBALS['xoopsTpl']->assign('alltimeLink', XOOPS_URL . "/modules/spotifyapi/alltime.php");

$GLOBALS['xoopsTpl']->assign('starttimeLink', XOOPS_URL . "/modules/spotifyapi");

$GLOBALS['xoopsTpl']->assign('lastmonth', _SPOTIFYAPI_LASTMONTH_TOP);
$GLOBALS['xoopsTpl']->assign('lastmonthLink', XOOPS_URL . "/modules/spotifyapi/month.php");

include XOOPS_ROOT_PATH.'/footer.php';
