<?php
/**
 * Insert meta tags, landing page and landing dialog if setting set
 *
 * @author PixelCrab <cs@pixelcrab.at>
 * @copyright 2016 PixelCrab
 * @global JTLSmarty $smarty
 * @global Plugin $oPlugin
 */

if (class_exists('Shop')) {
    require_once $oPlugin->cFrontendPfad . '../include/class.pcfwl.helper.php';
    $pcfwlHelper = pcfwlHelper::getInstance($oPlugin);

    // Insert meta tag if neccessary
    if ($pcfwlHelper->getConfig('insert_meta') === 'on') {
        $ageHash = md5(filter_input(INPUT_SERVER, 'SERVER_ADDR').$oPlugin->dInstalliert);
        pq('head')
            ->prepend('<meta name="age-de-meta-label" content="age=' . $pcfwlHelper->getConfig('min_age') . ' hash: ' . $ageHash . ' v=1.0 kind=sl protocol=all" />')
            ->prepend('<meta name="age-meta-label" content="age=' . $pcfwlHelper->getConfig('min_age') . '" />');
    }

    $session = Session::getInstance();
    $isBot   = isset($_SERVER['HTTP_USER_AGENT'])
        ? $session::getIsCrawler($_SERVER['HTTP_USER_AGENT'])
        : false;

    // Don't show the warning to bots if its not allowed to them ;)
    if (!$isBot || ($isBot && $pcfwlHelper->getConfig('allow_bots') === 'on')) {
        // If we insert a dialog and/or a landing page we have to assign our smarty values
        if (($pcfwlHelper->getConfig('show_landing_page') === 'on' && $pcfwlHelper->fskAccept() === false) ||
        ($pcfwlHelper->getConfig('show_dialog') === 'on' && $pcfwlHelper->fskAccept() === false)) {
            $pcfwlHelper->assignSmartyValues();
        }
        
        // Insert landing page
        if ($pcfwlHelper->getConfig('show_landing_page') === 'on' && $pcfwlHelper->fskAccept() === false) {
            $pcfwlHelper->insertLandingPage();
        }
        
        // Insert dialog
        if ($pcfwlHelper->getConfig('show_dialog') === 'on' && $pcfwlHelper->fskAccept() === false) {
            $pcfwlHelper->insertDialog();
        }
    }
}
