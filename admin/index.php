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

declare(strict_types=1);

/*
 * Spotify Api module for xoops
 *
 * @copyright 2020 XOOPS Project (https://xooops.org)
 * @license   GPL 2.0 or later
 * @package   spotifyapi
 * @since     1.0
 * @min_xoops 2.5.9
 * @author    TDM XOOPS - Email:<culex@culex.com> - Website:<http://culex.dk>
 */

use Xmf\Module\Admin;
use XoopsModules\Spotifyapi;

require_once \dirname(__DIR__) . '/preloads/autoloader.php';
require __DIR__ . '/header.php';

// Template Index
$templateMain = 'spotifyapi_admin_index.tpl';

// Count elements
// InfoBox Statistics
$adminObject->addInfoBox(_AM_SPOTIFYAPI_STATISTICS);
// Info elements
$adminObject->addInfoBoxLine(\sprintf('<label>' . 'No statistics' . '</label>', 0));
// Render Index
$GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('index.php'));

$GLOBALS['xoopsTpl']->assign('index', $adminObject->displayIndex());
// End Test Data
require __DIR__ . '/footer.php';
