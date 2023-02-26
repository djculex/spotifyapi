<?php
/**
 * Spotify Api module for xoops
 *
 * @package    spotifyapi
 * @subpackage page-level
 * @author     djculex <culex@culex.dk>
 * @copyright  2023 Michael Albertsen (www.culex.dk)
 * @since      1.0
 * @min_xoops  2.5.9
 */

use XoopsModules\Spotifyapi\Spotifyapi_db;
use XoopsModules\Spotifyapi\Spotifyapi_Helper;

require_once dirname(__DIR__) . '/include/common.php';
require_once XOOPS_ROOT_PATH . '/class/template.php';


/**
 * @return array $block
 */
function b_spotifyapi_show_recently_played()
{
    $helper = Spotifyapi_Helper::getInstance();
    $timez = $helper->getConfig('spotifyapitimezones');
    date_default_timezone_set($timez);
    $db = new Spotifyapi_db();
    return $db->getSongs();
}
