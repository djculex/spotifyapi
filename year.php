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

use XoopsModules\Spotifyapi\{Spotifyapi_db, Spotifyapi_form, Spotifyapi_Helper};
use XoopsModules\Spotifyapi\Constants;

require_once dirname(__DIR__, 2) . '/mainfile.php';
include __DIR__ . '/preloads/autoloader.php';
require_once __DIR__ . '/include/common.php';

$GLOBALS['xoopsLogger']->activated = false;

//include __DIR__ . '/header.php';
include XOOPS_ROOT_PATH . '/header.php';

$helper = Spotifyapi_Helper::getInstance();
$timez = $helper->getConfig('spotifyapitimezones');

$d = new Spotifyapi_db();
$form = new Spotifyapi_form();
date_default_timezone_set($timez);

$year = (isset($_POST['year'])) ? $_POST['year'] : '';

header('Content-Type: application/json');
echo json_encode($d->getWeeks($year));

//include XOOPS_ROOT_PATH.'/footer.php';
