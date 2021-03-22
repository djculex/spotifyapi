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
use XoopsModules\Spotifyapi;

include __DIR__ . '/preloads/autoloader.php';
require_once dirname(__DIR__, 2) . '/mainfile.php';

$moduleDirName = basename(__DIR__);
$modulePath    = XOOPS_ROOT_PATH . '/modules/' . $moduleDirName;

//require XOOPS_ROOT_PATH . '/header.php';

$helper = Spotifyapi\Helper::getInstance();
// Load language files
$helper->loadLanguage('main');

$myts   = \MyTextSanitizer::getInstance();

if (!isset($GLOBALS['xoTheme']) || !$GLOBALS['xoTheme'] instanceof \xos_opal_Theme) {
    require $GLOBALS['xoops']->path('class/theme.php');
    $GLOBALS['xoTheme'] = new \xos_opal_Theme();
}

if (!isset($GLOBALS['xoopsTpl']) || !$GLOBALS['xoopsTpl'] instanceof \XoopsTpl) {
    require $GLOBALS['xoops']->path('class/template.php');
    $GLOBALS['xoopsTpl'] = new \XoopsTpl();
}
