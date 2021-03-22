<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @author          XOOPS Project <www.xoops.org> <www.xoops.ir>
 */
\defined('XOOPS_ROOT_PATH') || die('Restricted access.');

/**
 * Class SpotifyapiCorePreload
 */
class SpotifyapiCorePreload extends \XoopsPreloadItem
{
    // to add PSR-4 autoloader
	
	public static function eventCoreHeaderAddmeta()
    {
		$GLOBALS['xoTheme']->addScript('browse.php?Frameworks/jquery/jquery.js');
		
		$url = XOOPS_URL . '/modules/spotifyapi/agent.php';
		$url2 = XOOPS_URL . '/modules/spotifyapi/request.php';
		$script  = "var spotifyagenturl = '" . $url . "';\n";
		$script2  = "var spotifyagenturl2 = '" . $url2 . "';\n"; 
		//$GLOBALS['xoTheme']->addScript($script);
		$GLOBALS['xoTheme']->addScript(null, array( 'type' => 'text/javascript' ), $script, 'spot');
		$GLOBALS['xoTheme']->addScript(null, array( 'type' => 'text/javascript' ), $script2, 'spot2');
		$GLOBALS['xoTheme']->addScript(XOOPS_URL . '/modules/spotifyapi/assets/js/spotifyapi-block.js');
		//$GLOBALS['xoTheme']->addScript(XOOPS_URL . '/modules/spotifyapi/assets/js/vticker.js');
	}
	
	
    /**
     * @param $args
     */
    public static function eventCoreIncludeCommonEnd($args)
    {
        include __DIR__ . '/autoloader.php';
    }
}
