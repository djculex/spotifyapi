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

// 
$moduleDirName      = \basename(__DIR__);
$moduleDirNameUpper = \mb_strtoupper($moduleDirName);
require_once XOOPS_ROOT_PATH .'/modules/spotifyapi/include/functions.php';
// ------------------- Informations ------------------- //
$modversion = [
	'name'                => _MI_SPOTIFYAPI_NAME,
	'version'             => 1.07,
	'description'         => _MI_SPOTIFYAPI_DESC,
	'author'              => 'TDM XOOPS',
	'author_mail'         => 'culex@culex.com',
	'author_website_url'  => 'http://culex.dk',
	'author_website_name' => 'culex DK',
	'credits'             => 'culex',
	'license'             => 'GPL 2.0 or later',
	'license_url'         => 'http://www.gnu.org/licenses/gpl-3.0.en.html',
	'help'                => 'page=help',
	'release_info'        => 'release_info',
	'release_file'        => XOOPS_URL . '/modules/spotifyapi/docs/release_info file',
	'release_date'        => '2021/03/10',
	'manual'              => 'link to manual file',
	'manual_file'         => XOOPS_URL . '/modules/spotifyapi/docs/install.txt',
	'min_php'             => '7.0',
	'min_xoops'           => '2.5.9',
	'min_admin'           => '1.2',
	'min_db'              => ['mysql' => '5.5', 'mysqli' => '5.5'],
	'image'               => 'assets/images/logoModule.png',
	'dirname'             => \basename(__DIR__),
	'dirmoduleadmin'      => 'Frameworks/moduleclasses/moduleadmin',
	'sqlfile'             => ['mysql' => 'sql/mysql.sql'],
	
	// Tables created by sql file (without prefix!)
	'tables'              => [
        $moduleDirName . '_' . 'music',
	],
	
	'sysicons16'          => '../../Frameworks/moduleclasses/icons/16',
	'sysicons32'          => '../../Frameworks/moduleclasses/icons/32',
	'modicons16'          => 'assets/icons/16',
	'modicons32'          => 'assets/icons/32',
	'demo_site_url'       => 'www.culex.dk',
	'demo_site_name'      => '---',
	'support_url'         => 'https://xoops.org/modules/newbb',
	'support_name'        => 'Support Forum',
	'module_website_url'  => 'www.xoops.org',
	'module_website_name' => 'XOOPS Project',
	'release'             => '05/25/2020',
	'module_status'       => 'Beta 3',
	'system_menu'         => 1,
	'hasAdmin'            => 1,
	'hasMain'             => 1,
	'adminindex'          => 'admin/index.php',
	'adminmenu'           => 'admin/menu.php',
	'onInstall'           => 'include/install.php',
	'onUninstall'         => 'include/uninstall.php',
	'onUpdate'            => 'include/update.php',
];
// ------------------- Templates ------------------- //
$modversion['templates'] = [
	// Admin templates
	['file' => 'spotifyapi_admin_about.tpl', 'description' => '', 'type' => 'admin'],
	['file' => 'spotifyapi_admin_header.tpl', 'description' => '', 'type' => 'admin'],
	['file' => 'spotifyapi_admin_index.tpl', 'description' => '', 'type' => 'admin'],
	['file' => 'spotifyapi_admin_footer.tpl', 'description' => '', 'type' => 'admin'],
	// User templates
	['file' => 'spotifyapi_header.tpl', 'description' => ''],
	['file' => 'spotifyapi_index.tpl', 'description' => ''],
	['file' => 'spotifyapi_breadcrumbs.tpl', 'description' => ''],
	['file' => 'spotifyapi_footer.tpl', 'description' => ''],
	['file' => 'spotifyapi_indexfile.tpl', 'description' => ''],
	//blocks
	//['file'	=>	'spotify_recentlyplayed_block.tpl', 'description' => ''],
	//['file'	=>	'spotify_block.tpl', 'description' => ''],
];

// Blocks
// options[0] - NumberToDisplay: any positive integer
$modversion['blocks'][] = [
    'file'        => 'recentlyplayed.php',
    'name'        => _MI_SPOTIFYAPI_BLOCK_RECELTLYPLAYED_NAME,
    'description' => 'Shows users recently played song from spotify',
    // irmtfan
    'show_func'   => 'b_spotifyapi_show_recently_played',
    'options'     => '',
    'edit_func'   => '',
    'template'    => 'spotify_block.tpl',
];

// ------------------- Config ------------------- //

// Client id
$modversion['config'][] = [
	'name'        => 'spotifyapiclientid',
	'title'       => '_MI_SPOTIFYAPI_CLIENTID',
	'description' => '_MI_SPOTIFYAPI_CLIENTID_DESC',
	'formtype'    => 'textbox',
	'valuetype'   => 'text',
	'default'     => '',
];

// Client secret
$modversion['config'][] = [
	'name'        => 'spotifyapiclientsecret',
	'title'       => '_MI_SPOTIFYAPI_CLIENTSECRET',
	'description' => '_MI_SPOTIFYAPI_CLIENTSECRET_DESC',
	'formtype'    => 'textbox',
	'valuetype'   => 'text',
	'default'     => '',
];

// Refresh link
$modversion['config'][] = [
	'name'        => 'spotifyapirefreshauth',
	'title'       => '_MI_SPOTIFYAPI_REFRESHAUTH',
	'description' => '_MI_SPOTIFYAPI_REFRESHAUTH_DESC',
	'formtype'    => 'textbox',
	'valuetype'   => 'text',
	'default'     => XOOPS_URL . '/modules/spotifyapi/auth.php',
];

// Redirect URI

$modversion['config'][] = [
	'name'        => 'spotifyapiredirecturi',
	'title'       => '_MI_SPOTIFYAPI_REDIRECTURI',
	'description' => '_MI_SPOTIFYAPI_REDIRECTURI_DESC',
	'formtype'    => 'textbox',
	'valuetype'   => '',
	'default'     => XOOPS_URL . '/modules/spotifyapi/callback.php',
];

$modversion['config'][] = [
	'name'        => 'spotifyapitimezones',
	'title'       => '_MI_SPOTIFYAPI_USERTIMEZONE',
	'description' => '_MI_SPOTIFYAPI_USERTIMEZONE_DESC',
	'formtype'    => 'select',
	'valuetype'   => 'text',
	'options'	  => spotifyapi_gettimeZones(),
];

// Number of songs to show
$modversion['config'][] = [
	'name'        => 'spotifyapinumbertoshow',
	'title'       => '_MI_SPOTIFYAPI_NUMBOFSONGSTOSHOW',
	'description' => '_MI_SPOTIFYAPI_NUMBOFSONGSTOSHOW_DESC',
	'formtype'    => 'textbox',
	'valuetype'   => 'int',
	'default'     => 10,
];

// Number of songs to show in charts
$modversion['config'][] = [
	'name'        => 'spotifyapinumshowcharts',
	'title'       => '_MI_SPOTIFYAPI_NUMBOFSONGSTOSHOWCHART',
	'description' => '_MI_SPOTIFYAPI_NUMBOFSONGSTOSHOWCHART_DESC',
	'formtype'    => 'textbox',
	'valuetype'   => 'int',
	'default'     => 20,
];

// Weekday to start / stop counting.
$modversion['config'][] = [
	'name'        => 'spotifyapidaytostartc',
	'title'       => '_MI_SPOTIFYAPI_DAYTOSTARTC',
	'description' => '_MI_SPOTIFYAPI_DAYTOSTARTC_DESC',
	'formtype'    => 'select',
	'valuetype'   => 'int',
	'default'     => _MI_SPOTIFYAPI_SATURDAY,
	'options'	  => [ 
			_MI_SPOTIFYAPI_MONDAY 		=> 1,
			_MI_SPOTIFYAPI_TUESDAY 		=> 2,
			_MI_SPOTIFYAPI_WEDNESDAY 	=> 3,
			_MI_SPOTIFYAPI_THURSDAY 	=> 4,
			_MI_SPOTIFYAPI_FRIDAY 		=> 5,
			_MI_SPOTIFYAPI_SATURDAY 	=> 6,
			_MI_SPOTIFYAPI_SUNDAY 		=> 7
		],
];

// Maintained by
$modversion['config'][] = [
	'name'        => 'maintainedby',
	'title'       => '_MI_SPOTIFYAPI_MAINTAINEDBY',
	'description' => '_MI_SPOTIFYAPI_MAINTAINEDBY_DESC',
	'formtype'    => 'textbox',
	'valuetype'   => 'text',
	'default'     => 'https://xoops.org/modules/newbb',
];
