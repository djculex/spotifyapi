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

if (isset($templateMain)) {
	$GLOBALS['xoopsTpl']->assign('maintainedby', $helper->getConfig('maintainedby'));
	$GLOBALS['xoopsTpl']->display("db:{$templateMain}");
}

xoops_cp_footer();
