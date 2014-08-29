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


class More_Mobile_DownloadableController extends Mage_Core_Controller_Front_Action
{
    protected function _addNotice($message)
    {
        Mage::getSingleton('core/session')->addNotice($message);
        return $this;
    }
    
    
    public function nowayAction()
    {
        $this->_addNotice(Mage::helper('downloadable')->__('The link is not available.'));
        $this->_redirectReferer();
    }
}
