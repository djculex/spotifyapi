<?php

/**
 * Spotify Api module for xoops
 *
 * @package    spotifyapi
 * @subpackage page-level
 * @author     Squiz Pty Ltd <products@squiz.net>
 * @copyright  2023 Squiz Pty Ltd (ABN 77 084 670 600)
 * @since      1.0
 * @min_xoops  2.5.9
 */

use Xmf\Module\Admin;
use Xmf\Request;
use XoopsModules\Spotifyapi\Common\ModuleFeedback;

require __DIR__ . '/header.php';

/*
 * Vars defined by inclusion of ./admin_header.php
 *
 * @var \XoopsModules\Spotifyapi\Admin $admin
 * @var \Xmf\Module\Admin $adminObject
 * @var \XoopsModules\Spotifyapi\Spotifyapi_Helper $helper
 * @var string $moduleDirName
 * @var string $moduleDirNameUpper
 */

$adminObject = Admin::getInstance();

$feedback = new ModuleFeedback();

// It recovered the value of argument op in URL$.
$op                 = Request::getString('op', 'list');
$moduleDirName      = $GLOBALS['xoopsModule']->getVar('dirname');
$moduleDirNameUpper = \mb_strtoupper($moduleDirName);
\xoops_loadLanguage('feedback', $moduleDirName);

switch ($op) {
    case 'list':
    default:
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('feedback.php'));
        $feedback->name  = $GLOBALS['xoopsUser']->getVar('name');
        $feedback->email = $GLOBALS['xoopsUser']->getVar('email');
        $feedback->site  = XOOPS_URL;
        $form            = $feedback->getFormFeedback();
        $form->display();
        break;

    case 'send':
        // Security Check
        if (!$GLOBALS['xoopsSecurity']->check()) {
            \redirect_header('index.php', 3, \implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }

        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('feedback.php'));

        $your_name  = Request::getString('your_name', '');
        $your_site  = Request::getString('your_site', '');
        $your_mail  = Request::getString('your_mail', '');
        $fb_type    = Request::getString('fb_type', '');
        $fb_content = Request::getText('fb_content', '');
        $fb_content = \str_replace(["\r\n", "\n", "\r"], '<br>', $fb_content);
        // clean line break from dhtmltextarea
        $title       = \constant('CO_' . $moduleDirNameUpper . '_' . 'FB_SEND_FOR') . $GLOBALS['xoopsModule']->getVar('dirname');
        $body        = \constant('CO_' . $moduleDirNameUpper . '_' . 'FB_NAME') . ': ' . $your_name . '<br>';
        $body       .= \constant('CO_' . $moduleDirNameUpper . '_' . 'FB_MAIL') . ': ' . $your_mail . '<br>';
        $body       .= \constant('CO_' . $moduleDirNameUpper . '_' . 'FB_SITE') . ': ' . $your_site . '<br>';
        $body       .= \constant('CO_' . $moduleDirNameUpper . '_' . 'FB_TYPE') . ': ' . $fb_type . '<br><br>';
        $body       .= \constant('CO_' . $moduleDirNameUpper . '_' . 'FB_TYPE_CONTENT') . ':<br>';
        $body       .= $fb_content;
        $xoopsMailer = xoops_getMailer();
        $xoopsMailer->useMail();
        $xoopsMailer->setToEmails($GLOBALS['xoopsModule']->getInfo('author_mail'));
        $xoopsMailer->setFromEmail($your_mail);
        $xoopsMailer->setFromName($your_name);
        $xoopsMailer->setSubject($title);
        $xoopsMailer->multimailer->isHTML(true);
        $xoopsMailer->setBody($body);
        $ret = $xoopsMailer->send();
        if ($ret) {
            \redirect_header('index.php', 3, \constant('CO_' . $moduleDirNameUpper . '_' . 'FB_SEND_SUCCESS'));
        }

        // show form with content again
        $feedback->name    = $your_name;
        $feedback->email   = $your_mail;
        $feedback->site    = $your_site;
        $feedback->type    = $fb_type;
        $feedback->content = $fb_content;
        echo '<div style="text-align:center;width: 80%; padding: 10px; border: 2px solid #ff0000; color: #ff0000; margin-right:auto;margin-left:auto;">
            <h3>' . \constant('CO_' . $moduleDirNameUpper . '_' . 'FB_SEND_ERROR') . '</h3>
            </div>';
        $form = $feedback->getFormFeedback();
        $form->display();

        break;
}//end switch

require __DIR__ . '/footer.php';
