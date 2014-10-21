<?php

class More_ChinaInvoice_Block_Onepage_Deliverydate extends Mage_Checkout_Block_Onepage_Abstract
{
    protected function _construct()
    {    	
        $this->getCheckout()->setStepData('deliverydate', array('label'=>"delivery date"));
        parent::_construct();
    }

    public function getMethod()
    {
        return $this->getQuote()->getCheckoutMethod();
    }
}