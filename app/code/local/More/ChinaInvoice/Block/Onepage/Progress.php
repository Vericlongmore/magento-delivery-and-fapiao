<?php

class More_ChinaInvoice_Block_Onepage_Progress extends Mage_Checkout_Block_Onepage_Progress
{
    /**
     * Get checkout steps codes
     * add step 'fapiao'
     * @return array
     */
    public function _getStepCodes()
    {
        return array('login', 'billing', 'shipping', 'shipping_method', 'payment', 'fapiao','review');
    }
}
