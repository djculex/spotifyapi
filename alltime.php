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

use XoopsModules\Spotifyapi\Spotifyapi_db;
use XoopsModules\Spotifyapi\Spotifyapi_form;
use XoopsModules\Spotifyapi\Spotifyapi_Helper;

require_once dirname(__DIR__, 2) . '/mainfile.php';
include __DIR__ . '/preloads/autoloader.php';
require_once __DIR__ . '/include/common.php';

$GLOBALS['xoopsOption']['template_main'] = 'spotifyapi_indexfile.tpl';

$GLOBALS['xoopsLogger']->activated = true;

include __DIR__ . '/header.php';
/*
 * Vars defined by inclusion of ./admin_header.php
 *
 * @var \XoopsModules\SpotifyapiAdmin $admin
 * @var \Xmf\Module\Admin $adminObject
 * @var \XoopsModules\Spotifyapi\Helper $helper
 * @var string $moduleDirName
 * @var string $moduleDirNameUpper
 */
include XOOPS_ROOT_PATH . '/header.php';

$helper = Spotifyapi_Helper::getInstance();
$timez = $helper->getConfig('spotifyapitimezones');

$GLOBALS['xoTheme']->addScript('https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js');

$d = new Spotifyapi_db();
$form = new Spotifyapi_form();
date_default_timezone_set($timez);
$weekly = 0;
$i = 0;


$d->thisweek_start = $d->getMinMaxDate($type = 'min');
$td = $d->getTopSingleWeek();

foreach ($td as $tv) {
    $chart[$i]['tw'] = (int)$tv['pos'];
    $chart[$i]['artist'] = $tv['artist'];
    $chart[$i]['title'] = $tv['title'];
    $chart[$i]['image'] = $tv['image'];
    $chart[$i]['album'] = $tv['album'];
    $chart[$i]['year'] = (int)$tv['releaseyear'];
    $chart[$i]['artlink'] = $tv['artistlink'];
    $chart[$i]['pop'] = (int)$tv['popularity'];
    $i += 1;
}

$tit = sprintf(_SPOTIFYAPI_CHARTTITLEALL, $d->selecttoplimit);


$weekly = 0;
$sub = sprintf(
    _SPOTIFYAPI_CHARTSUBTITLE,
    $d->chart_day_count,
    date_format(date_create_from_format('d-m-Y', $d->thisweek_start), 'd-m-Y'),
    $d->chart_day_count,
    date_format(date_create_from_format('d-m-Y', $d->thisweek_end), 'd-m-Y')
);


$dropstart = $form->dropdown('startingDate', $d->parseDistinctDates($d->getDistinctYears(), $arg = 'year'), $selected = null, $sep = '');
$dropend = $form->dropdown('endDate', array('', ''));
$radiobtn = $form->radiobutton('charttype', 'charttype', _SPOTIFYAPI_CHARTACCUMUM);

$GLOBALS['xoopsTpl']->assign('title', $tit);
$GLOBALS['xoopsTpl']->assign('subtitle', $sub);
$GLOBALS['xoopsTpl']->assign('dropstart', $dropstart);
$GLOBALS['xoopsTpl']->assign('dropend', $dropend);
$GLOBALS['xoopsTpl']->assign('radiobutton', $radiobtn);
$GLOBALS['xoopsTpl']->assign('sbmit', $form->submitBtn('spotifyapisubmitbutton', _SPOTIFYAPI_FILTER_TITLE, $sep = '<br>'));
$GLOBALS['xoopsTpl']->assign('chart', $d->parseArraySingle($td));

$GLOBALS['xoopsTpl']->assign('lastweek_text', _SPOTIFYAPI_STARTTIME);
$GLOBALS['xoopsTpl']->assign('weeklyLink', XOOPS_URL . "/modules/spotifyapi/week.php");
$GLOBALS['xoopsTpl']->assign('weekly', $weekly);

$GLOBALS['xoopsTpl']->assign('alltime', _SPOTIFYAPI_ALLTIME_TOP);
$GLOBALS['xoopsTpl']->assign('alltimeLink', XOOPS_URL . "/modules/spotifyapi/alltime.php");

$GLOBALS['xoopsTpl']->assign('starttimeLink', XOOPS_URL . "/modules/spotifyapi");

$GLOBALS['xoopsTpl']->assign('lastmonth', _SPOTIFYAPI_LASTMONTH_TOP);
$GLOBALS['xoopsTpl']->assign('lastmonthLink', XOOPS_URL . "/modules/spotifyapi/month.php");

include XOOPS_ROOT_PATH . '/footer.php';
