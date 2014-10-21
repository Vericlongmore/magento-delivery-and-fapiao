<?php

class More_ChinaInvoice_Block_Onepage_Fapiao extends Mage_Checkout_Block_Onepage_Abstract
{
    protected function _construct()
    {    	
        $this->getCheckout()->setStepData('fapiao', array(
            'label'     => Mage::helper('chinainvoice')->__('Invoice Information'),
            'is_show'   => true
        ));
        
        parent::_construct();
    }
}