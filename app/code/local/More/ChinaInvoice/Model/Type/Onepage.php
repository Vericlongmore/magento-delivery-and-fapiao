<?php


class More_ChinaInvoice_Model_Type_Onepage extends Mage_Checkout_Model_Type_Onepage
{
    public function initCheckout()
    {
        $checkout = $this->getCheckout();
        if (is_array($checkout->getStepData())) {
            foreach ($checkout->getStepData() as $step=>$data) {
                if (!($step==='login'
                    || Mage::getSingleton('customer/session')->isLoggedIn() && $step==='billing')) {
                    $checkout->setStepData($step, 'allow', false);
                }
            }
        }

        $checkout->setStepData('fapiao', 'allow', true);

        /*
        * want to laod the correct customer information by assiging to address
        * instead of just loading from sales/quote_address
        */
        $customer = Mage::getSingleton('customer/session')->getCustomer();
        if ($customer) {
            $this->getQuote()->assignCustomer($customer);
        }
        if ($this->getQuote()->getIsMultiShipping()) {
            $this->getQuote()->setIsMultiShipping(false);
            $this->getQuote()->save();
        }
        return $this;
    }



    public function saveFapiao($data){
        if (empty($data)) {
            return array('error' => -1, 'message' => $this->_helper->__('Invalid data.'));
        }

        foreach ($data as $key => $value) {
            if (!empty($value)){
                $this->getQuote()->setData($key, $value);
            }
        }
        $this->getQuote()->collectTotals();
        $this->getQuote()->save();

        $this->getCheckout()
            ->setStepData('deliverydate', 'allow', true)
            ->setStepData('fapiao', 'complete', true);

        return array();
    }
    public function saveDeliverydate($data){
        if (empty($data)) {
            return array('error' => -1, 'message' => $this->_helper->__('Invalid data.'));
        }

        foreach ($data as $key => $value) {
            if (!empty($value)){
                $this->getQuote()->setData($key, $value);
            }
        }
        $this->getQuote()->collectTotals();
        $this->getQuote()->save();

        $this->getCheckout()
            ->setStepData('review', 'allow', true)
            ->setStepData('deliverydate', 'complete', true);

        return array();
    }




}
