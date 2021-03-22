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

include_once __DIR__ . '/common.php';

// ---------------- Admin Index ----------------
\define('_AM_SPOTIFYAPI_STATISTICS', 'Statistics');
// There are
// ---------------- Admin Files ----------------
// There aren't
// Save/Delete
\define('_AM_SPOTIFYAPI_FORM_OK', 'Successfully saved');
\define('_AM_SPOTIFYAPI_FORM_DELETE_OK', 'Successfully deleted');
\define('_AM_SPOTIFYAPI_FORM_SURE_DELETE', "Are you sure to delete: <b><span style='color : Red;'>%s </span></b>");
\define('_AM_SPOTIFYAPI_FORM_SURE_RENEW', "Are you sure to update: <b><span style='color : Red;'>%s </span></b>");
// Buttons
// Lists
// ---------------- Admin Classes ----------------
// General
\define('_AM_SPOTIFYAPI_FORM_UPLOAD', 'Upload file');
\define('_AM_SPOTIFYAPI_FORM_UPLOAD_NEW', 'Upload new file: ');
\define('_AM_SPOTIFYAPI_FORM_UPLOAD_SIZE', 'Max file size: ');
\define('_AM_SPOTIFYAPI_FORM_UPLOAD_SIZE_MB', 'MB');
\define('_AM_SPOTIFYAPI_FORM_UPLOAD_IMG_WIDTH', 'Max image width: ');
\define('_AM_SPOTIFYAPI_FORM_UPLOAD_IMG_HEIGHT', 'Max image height: ');
\define('_AM_SPOTIFYAPI_FORM_IMAGE_PATH', 'Files in %s :');
\define('_AM_SPOTIFYAPI_FORM_ACTION', 'Action');
\define('_AM_SPOTIFYAPI_FORM_EDIT', 'Modification');
\define('_AM_SPOTIFYAPI_FORM_DELETE', 'Clear');
// ---------------- Admin Others ----------------
\define('_AM_SPOTIFYAPI_ABOUT_MAKE_DONATION', 'Submit');
\define('_AM_SPOTIFYAPI_SUPPORT_FORUM', 'Support Forum');
\define('_AM_SPOTIFYAPI_DONATION_AMOUNT', 'Donation Amount');
\define('_AM_SPOTIFYAPI_MAINTAINEDBY', ' is maintained by ');
// ---------------- End ----------------
