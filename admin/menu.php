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

$dirname       = \basename(\dirname(__DIR__));
$moduleHandler = \xoops_getHandler('module');
$xoopsModule   = XoopsModule::getByDirname($dirname);
$moduleInfo    = $moduleHandler->get($xoopsModule->getVar('mid'));
$sysPathIcon32 = $moduleInfo->getInfo('sysicons32');

$adminmenu[] = [
    'title' => _MI_SPOTIFYAPI_ADMENU1,
    'link'  => 'admin/index.php',
    'icon'  => $sysPathIcon32 . '/dashboard.png',
];
$adminmenu[] = [
    'title' => _MI_SPOTIFYAPI_ADMENU2,
    'link'  => 'admin/feedback.php',
    'icon'  => $sysPathIcon32 . '/mail_foward.png',
];
$adminmenu[] = [
    'title' => _MI_SPOTIFYAPI_ABOUT,
    'link'  => 'admin/about.php',
    'icon'  => $sysPathIcon32 . '/about.png',
];
