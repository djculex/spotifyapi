<?php
/**
 * Spotify Api module for xoops
 *
 * @package    spotifyapi
 * @subpackage page-level
 * @author     Squiz Pty Ltd <products@squiz.net>
 * @copyright  2023 Michael Albertsen (culex.dk)
 * @since      1.0
 * @min_xoops  2.5.9
 */

declare(strict_types=1);

require __DIR__.'/header.php';

/*
 * Vars defined by inclusion of ./admin_header.php
 *
 * @var \XoopsModules\Spotifyapi\Admin $admin
 * @var \Xmf\Module\Admin $adminObject
 * @var \XoopsModules\Spotifyapi\Spotifyapi_Helper $helper
 * @var string $moduleDirName
 * @var string $moduleDirNameUpper
 */

$templateMain = 'spotifyapi_admin_about.tpl';
$GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('about.php'));
$adminObject->setPaypal('8Z2GHQWG3ENNS');
$GLOBALS['xoopsTpl']->assign('about', $adminObject->renderAbout(false));
require __DIR__.'/footer.php';
