<?php
/**
 * aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/More-LICENSE.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This software is designed to work with Magento community edition and
 * its use on an edition other than specified is prohibited. aheadWorks does not
 * provide extension support in case of incorrect edition use.
 * =================================================================
 *
 * @category   More
 * @package    More_Mobile
 * @version    1.6.6
 * @copyright  Copyright (c) 2010-2012 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/More-LICENSE.txt
 */


class More_Mobile_SwitchController extends Mage_Core_Controller_Front_Action
{
    /**
     * List of not supported path parts
     * @var array
     */
    protected $_notSupportedPath = array(
        '/review/',
    );

    /**
     * Show notice to customer
     * @param string $message
     * @return More_Mobile_SwitchController
     */
    protected function _addNotice($message)
    {
        Mage::getSingleton('core/session')->addNotice($message);
        return $this;
    }

    /**
     * Is refferer url allowed to show in Mobile
     * @return boolean
     */
    protected function _checkForSupport()
    {
        $refUrl  = $this->_getRefererUrl();
        $result = true;

        foreach ($this->_notSupportedPath as $part) {
            if (strpos($refUrl, $part) !== false) {
                $result = false;
            }
        }
        return $result;
    }


    /**
     * Redirect back
     * @param boolean $isMobile
     * @return null
     */
    protected function _goBack($isMobile = false)
    {
        if ($this->_checkForSupport() || !$isMobile) {
            $this->_redirectReferer();
        } else {
            $this->_addNotice(Mage::helper('Moremobile')->__('The opportunity of using this URL is not supported yet'));
            $this->_redirect('');
        }
        return;
    }

    /**
     * Retrives helper
     * @return More_Mobile_Helper_Data
     */
    protected function _helper()
    {
        return Mage::helper('Moremobile');
    }

    protected function _generateBackLink()
    {
        $referrerUrl = $this->_getRefererUrl();
        if (empty($referrerUrl)) {
            $referrerUrl = Mage::getBaseUrl();
        }
        $string = explode("?", $referrerUrl);
        if (count($string) > 1) {
            $referrerUrl = $string[0];
        }
        $symbol = "?";
        if (preg_match("#\?#is", $referrerUrl)) {
            $symbol = "&";
        }
        if ($fromStore = $this->_helper()->getFromStore()) {
            $currentStore = Mage::app()->getStore()->getCode();
            $referrerUrl .= "{$symbol}___store={$fromStore}&___from={$currentStore}";
            $symbol = "&";
        }
        return $referrerUrl . "{$symbol}key=" . time() . mt_rand();
    }

    public function toMobileAction()
    {
        $this->_helper()->setShowDesktop(false);
        if ($this->_checkForSupport() == false) {
            $this->_addNotice(Mage::helper('Moremobile')->__('The opportunity of using this URL is not supported yet'));
            $this->_redirectUrl(Mage::getBaseUrl());
            return;
        }
        Mage::app()->getResponse()->setRedirect($this->_generateBackLink());
    }

    public function toDesktopAction()
    {
        $this->_helper()->setShowDesktop(true);
        Mage::app()->getResponse()->setRedirect($this->_generateBackLink());
    }
}