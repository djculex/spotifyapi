<?php
/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright      {@link https://xoops.org/ XOOPS Project}
 * @license        {@link https://www.gnu.org/licenses/gpl-2.0.html GNU GPL 2 or later}
 * @package
 * @since
 * @author         XOOPS Development Team
 */
use XoopsModules\Spotifyapi\{
	Session,
	SpotifyWebAPI
};
include __DIR__ . '/preloads/autoloader.php';
require_once dirname(__DIR__,2) . '/mainfile.php';
//require_once XOOPS_ROOT_PATH . '/class/template.php';
require_once __DIR__ . '/header.php';
	$block = [];
	//$tpl = new \XoopsTpl();
	//$tpl->caching = 0;
	//$result = b_spotifyapi_show_recently_played();
	$block = file_get_contents(XOOPS_ROOT_PATH."/modules/spotifyapi/agent.php");
		print_r($block);
	//$tpl->assign('block', $result);	
	//$tpl->display(XOOPS_ROOT_PATH . "/modules/system/templates/blocks/system_block_online.tpl");
	//$tpl->display('db:system_block_online.tpl');
	//break;
    //return $block;
