<?php

declare(strict_types=1);

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * Spotify Api module for xoops
 *
 * @copyright      2020 XOOPS Project (https://xooops.org)
 * @license        GPL 2.0 or later
 * @package        spotifyapi
 * @since          1.0
 * @min_xoops      2.5.9
 * @author         TDM XOOPS - Email:<culex@culex.com> - Website:<http://culex.dk>
 */
if (!\defined('XOOPS_ICONS32_PATH')) {
	\define('XOOPS_ICONS32_PATH', XOOPS_ROOT_PATH . '/Frameworks/moduleclasses/icons/32');
}
if (!\defined('XOOPS_ICONS32_URL')) {
	\define('XOOPS_ICONS32_URL', XOOPS_URL . '/Frameworks/moduleclasses/icons/32');
}
\define('SPOTIFYAPI_DIRNAME', 'spotifyapi');
\define('SPOTIFYAPI_PATH', XOOPS_ROOT_PATH . '/modules/' . SPOTIFYAPI_DIRNAME);
\define('SPOTIFYAPI_URL', XOOPS_URL . '/modules/' . SPOTIFYAPI_DIRNAME);
\define('SPOTIFYAPI_ICONS_PATH', SPOTIFYAPI_PATH . '/assets/icons');
\define('SPOTIFYAPI_ICONS_URL', SPOTIFYAPI_URL . '/assets/icons');
\define('SPOTIFYAPI_IMAGE_PATH', SPOTIFYAPI_PATH . '/assets/images');
\define('SPOTIFYAPI_IMAGE_URL', SPOTIFYAPI_URL . '/assets/images');
\define('SPOTIFYAPI_UPLOAD_PATH', XOOPS_UPLOAD_PATH . '/' . SPOTIFYAPI_DIRNAME);
\define('SPOTIFYAPI_UPLOAD_URL', XOOPS_UPLOAD_URL . '/' . SPOTIFYAPI_DIRNAME);
\define('SPOTIFYAPI_UPLOAD_FILES_PATH', SPOTIFYAPI_UPLOAD_PATH . '/files');
\define('SPOTIFYAPI_UPLOAD_FILES_URL', SPOTIFYAPI_UPLOAD_URL . '/files');
\define('SPOTIFYAPI_UPLOAD_IMAGE_PATH', SPOTIFYAPI_UPLOAD_PATH . '/images');
\define('SPOTIFYAPI_UPLOAD_IMAGE_URL', SPOTIFYAPI_UPLOAD_URL . '/images');
\define('SPOTIFYAPI_UPLOAD_SHOTS_PATH', SPOTIFYAPI_UPLOAD_PATH . '/images/shots');
\define('SPOTIFYAPI_UPLOAD_SHOTS_URL', SPOTIFYAPI_UPLOAD_URL . '/images/shots');
\define('SPOTIFYAPI_ADMIN', SPOTIFYAPI_URL . '/admin/index.php');
$localLogo = SPOTIFYAPI_IMAGE_URL . '/tdmxoops_logo.png';
// Module Information
$copyright = "<a href='http://culex.dk' title='culex DK' target='_blank'><img src='" . $localLogo . "' alt='culex DK' /></a>";
include_once XOOPS_ROOT_PATH . '/class/xoopsrequest.php';
include_once SPOTIFYAPI_PATH . '/include/functions.php';
