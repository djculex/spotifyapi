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

include_once 'common.php';

// ---------------- Admin Main ----------------
\define('_MI_SPOTIFYAPI_NAME', 'Spotify Api');
\define('_MI_SPOTIFYAPI_DESC', 'This module is for doing following...');
// ---------------- Admin Menu ----------------
\define('_MI_SPOTIFYAPI_ADMENU1', 'Dashboard');
\define('_MI_SPOTIFYAPI_ADMENU2', 'Feedback');
\define('_MI_SPOTIFYAPI_ABOUT', 'About');

// Blocks
\define('_MI_SPOTIFYAPI_BLOCK_RECELTLYPLAYED_NAME', 'Spotify recent played');
\define('_MI_SPOTIFYAPI_NUMBOFSONGSTOSHOW', 'Number of songs to show');
\define('_MI_SPOTIFYAPI_NUMBOFSONGSTOSHOW_DESC', 'Display a list of songs from your spotify');
\define('_MI_SPOTIFYAPI_NUMBOFSONGSTOSHOWCHART', 'Songs in chart');
\define('_MI_SPOTIFYAPI_NUMBOFSONGSTOSHOWCHART_DESC', 'Display a chart of your played songs');
\define('_MI_SPOTIFYAPI_CLIENTSECRET', 'Spotify client secret');
\define('_MI_SPOTIFYAPI_CLIENTSECRET_DESC', 'When you register your application, Spotify provides you a Client Secret.');
\define('_MI_SPOTIFYAPI_CLIENTID', 'Spotify client id');
\define('_MI_SPOTIFYAPI_CLIENTID_DESC', 'When you register your application, Spotify provides you a Client ID.');
\define('_MI_SPOTIFYAPI_REFRESHAUTH', 'Renew OAuth for spotify');
\define('_MI_SPOTIFYAPI_REFRESHAUTH_DESC', 'At times the OAuth needs to be renewed. Tell sign is that the block latest played is not updating. Click the link and sign in, and you\'re good to go.');
\define('_MI_SPOTIFYAPI_REDIRECTURI','Spotify redirect uri');
\define('_MI_SPOTIFYAPI_REDIRECTURI_DESC','<br><strong>**** Default value is the file you need to set on https://developer.spotify.com/dashboard/applications/ as your rediret uri ****</strong> <br><br>The URI to redirect to after the user grants or denies permission. This URI needs to have been entered in the Redirect URI whitelist that you specified when you registered your application. The value of redirect_uri here must exactly match one of the values you entered when you registered your application, including upper or lowercase, terminating slashes, and such.');

// Config
\define('_MI_SPOTIFYAPI_KEYWORDS', 'Keywords');
\define('_MI_SPOTIFYAPI_KEYWORDS_DESC', 'Insert here the keywords (separate by comma)');
\define('_MI_SPOTIFYAPI_NUMB_COL', 'Number Columns');
\define('_MI_SPOTIFYAPI_NUMB_COL_DESC', 'Number Columns to View.');
\define('_MI_SPOTIFYAPI_DIVIDEBY', 'Divide By');
\define('_MI_SPOTIFYAPI_DIVIDEBY_DESC', 'Divide by columns number.');
\define('_MI_SPOTIFYAPI_TABLE_TYPE', 'Table Type');
\define('_MI_SPOTIFYAPI_TABLE_TYPE_DESC', 'Table Type is the bootstrap html table.');
\define('_MI_SPOTIFYAPI_PANEL_TYPE', 'Panel Type');
\define('_MI_SPOTIFYAPI_PANEL_TYPE_DESC', 'Panel Type is the bootstrap html div.');
\define('_MI_SPOTIFYAPI_IDPAYPAL', 'Paypal ID');
\define('_MI_SPOTIFYAPI_IDPAYPAL_DESC', 'Insert here your PayPal ID for donactions.');
\define('_MI_SPOTIFYAPI_ADVERTISE', 'Advertisement Code');
\define('_MI_SPOTIFYAPI_ADVERTISE_DESC', 'Insert here the advertisement code');
\define('_MI_SPOTIFYAPI_MAINTAINEDBY', 'Maintained By');
\define('_MI_SPOTIFYAPI_MAINTAINEDBY_DESC', 'Allow url of support site or community');
\define('_MI_SPOTIFYAPI_BOOKMARKS', 'Social Bookmarks');
\define('_MI_SPOTIFYAPI_BOOKMARKS_DESC', 'Show Social Bookmarks in the single page');
\define('_MI_SPOTIFYAPI_FACEBOOK_COMMENTS', 'Facebook comments');
\define('_MI_SPOTIFYAPI_FACEBOOK_COMMENTS_DESC', 'Allow Facebook comments in the single page');
\define('_MI_SPOTIFYAPI_DISQUS_COMMENTS', 'Disqus comments');
\define('_MI_SPOTIFYAPI_DISQUS_COMMENTS_DESC', 'Allow Disqus comments in the single page');
\define('_MI_SPOTIFYAPI_USERTIMEZONE','Set user timezone');
\define('_MI_SPOTIFYAPI_USERTIMEZONE_DESC','Adjust your time here. If the date & time in the block is off compared with your time. Set the apropiate timezone here');
\define('_MI_SPOTIFYAPI_DAYTOSTARTC','Start of chart week');
\define('_MI_SPOTIFYAPI_DAYTOSTARTC_DESC','Day to start counting. F.I. The chart runs from saturday to saturdat, monday - monday');

// --- GLOBALS --- 
\define('_MI_SPOTIFYAPI_MONDAY', 'Monday');
\define('_MI_SPOTIFYAPI_TUESDAY', 'Tuesday');
\define('_MI_SPOTIFYAPI_WEDNESDAY', 'Wednesday');
\define('_MI_SPOTIFYAPI_THURSDAY', 'Thursday');
\define('_MI_SPOTIFYAPI_FRIDAY', 'Friday');
\define('_MI_SPOTIFYAPI_SATURDAY', 'Saturday');
\define('_MI_SPOTIFYAPI_SUNDAY', 'Sunday');

// ---------------- End ----------------
