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
// ------------------- Informations ------------------- //
$modversion = [
	'name'                => _MI_SPOTIFYAPI_NAME,
	'version'             => 1.0,
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
	'min_db'              => ['mysql' => '5.6', 'mysqli' => '5.6'],
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
	'demo_site_url'       => 'not yet',
	'demo_site_name'      => '---',
	'support_url'         => 'https://xoops.org/modules/newbb',
	'support_name'        => 'Support Forum',
	'module_website_url'  => 'www.xoops.org',
	'module_website_name' => 'XOOPS Project',
	'release'             => '05/25/2020',
	'module_status'       => 'Beta 1',
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

// Redirect URI

$modversion['config'][] = [
	'name'        => 'spotifyapiredirecturi',
	'title'       => '_MI_SPOTIFYAPI_REDIRECTURI',
	'description' => '_MI_SPOTIFYAPI_REDIRECTURI_DESC',
	'formtype'    => 'textbox',
	'valuetype'   => '',
	'default'     => XOOPS_URL . '/modules/spotifyapi/agent.php',
];

// Number of songs to show
$modversion['config'][] = [
	'name'        => 'spotifyapinumbertoshow',
	'title'       => '_MI_SPOTIFYAPI_NUMBOFSONGSTOSHOW',
	'description' => '_MI_SPOTIFYAPI_NUMBOFSONGSTOSHOW_DESC',
	'formtype'    => 'select',
	'valuetype'   => 'int',
	'default'     => 10,
	'options'     => [10 => '10', 20 => '20', 30 => '30', 40 => '40'],
];



/* Keywords
$modversion['config'][] = [
	'name'        => 'keywords',
	'title'       => '_MI_SPOTIFYAPI_KEYWORDS',
	'description' => '_MI_SPOTIFYAPI_KEYWORDS_DESC',
	'formtype'    => 'textbox',
	'valuetype'   => 'text',
	'default'     => 'spotifyapi, ',
];
// Number column
$modversion['config'][] = [
	'name'        => 'numb_col',
	'title'       => '_MI_SPOTIFYAPI_NUMB_COL',
	'description' => '_MI_SPOTIFYAPI_NUMB_COL_DESC',
	'formtype'    => 'select',
	'valuetype'   => 'int',
	'default'     => 1,
	'options'     => [1 => '1', 2 => '2', 3 => '3', 4 => '4'],
];
// Divide by
$modversion['config'][] = [
	'name'        => 'divideby',
	'title'       => '_MI_SPOTIFYAPI_DIVIDEBY',
	'description' => '_MI_SPOTIFYAPI_DIVIDEBY_DESC',
	'formtype'    => 'select',
	'valuetype'   => 'int',
	'default'     => 1,
	'options'     => [1 => '1', 2 => '2', 3 => '3', 4 => '4'],
];
// Table type
$modversion['config'][] = [
	'name'        => 'table_type',
	'title'       => '_MI_SPOTIFYAPI_TABLE_TYPE',
	'description' => '_MI_SPOTIFYAPI_DIVIDEBY_DESC',
	'formtype'    => 'select',
	'valuetype'   => 'int',
	'default'     => 'bordered',
	'options'     => ['bordered' => 'bordered', 'striped' => 'striped', 'hover' => 'hover', 'condensed' => 'condensed'],
];
// Panel by
$modversion['config'][] = [
	'name'        => 'panel_type',
	'title'       => '_MI_SPOTIFYAPI_PANEL_TYPE',
	'description' => '_MI_SPOTIFYAPI_PANEL_TYPE_DESC',
	'formtype'    => 'select',
	'valuetype'   => 'text',
	'default'     => 'default',
	'options'     => ['default' => 'default', 'primary' => 'primary', 'success' => 'success', 'info' => 'info', 'warning' => 'warning', 'danger' => 'danger'],
];
// Advertise
$modversion['config'][] = [
	'name'        => 'advertise',
	'title'       => '_MI_SPOTIFYAPI_ADVERTISE',
	'description' => '_MI_SPOTIFYAPI_ADVERTISE_DESC',
	'formtype'    => 'textarea',
	'valuetype'   => 'text',
	'default'     => '',
];
// Bookmarks
$modversion['config'][] = [
	'name'        => 'bookmarks',
	'title'       => '_MI_SPOTIFYAPI_BOOKMARKS',
	'description' => '_MI_SPOTIFYAPI_BOOKMARKS_DESC',
	'formtype'    => 'yesno',
	'valuetype'   => 'int',
	'default'     => 0,
];
// Make Sample button visible?
$modversion['config'][] = [
	'name'        => 'displaySampleButton',
	'title'       => 'CO_' . $moduleDirNameUpper . '_' . 'SHOW_SAMPLE_BUTTON',
	'description' => 'CO_' . $moduleDirNameUpper . '_' . 'SHOW_SAMPLE_BUTTON_DESC',
	'formtype'    => 'yesno',
	'valuetype'   => 'int',
	'default'     => 1,
];
*/
// Maintained by
$modversion['config'][] = [
	'name'        => 'maintainedby',
	'title'       => '_MI_SPOTIFYAPI_MAINTAINEDBY',
	'description' => '_MI_SPOTIFYAPI_MAINTAINEDBY_DESC',
	'formtype'    => 'textbox',
	'valuetype'   => 'text',
	'default'     => 'https://xoops.org/modules/newbb',
];
