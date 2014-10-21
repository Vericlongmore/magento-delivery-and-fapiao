<?php

require_once 'Mage/Checkout/controllers/OnepageController.php';

class More_ChinaInvoice_OnepageController extends Mage_Checkout_OnepageController
{
    public function doSomestuffAction()
    {
        if (true) {
            $result['update_section'] = array(
                'name' => 'payment-method',
                'html' => $this->_getPaymentMethodsHtml()
            );
        } else {
            $result['goto_section'] = 'shipping';
        }
    }

    public function savePaymentAction()
    {
        $this->_expireAjax();
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost('payment', array());
            /*
            * first to check payment information entered is correct or not
            */

            try {
                $result = $this->getOnepage()->savePayment($data);
            } catch (Mage_Payment_Exception $e) {
                if ($e->getFields()) {
                    $result['fields'] = $e->getFields();
                }
                $result['error'] = $e->getMessage();
            }
            catch (Exception $e) {
                $result['error'] = $e->getMessage();
            }
            $redirectUrl = $this->getOnePage()->getQuote()->getPayment()->getCheckoutRedirectUrl();
            if (empty($result['error']) && !$redirectUrl) {
                $this->loadLayout('checkout_onepage_fapiao');

                $result['goto_section'] = 'fapiao';
            }

            if ($redirectUrl) {
                $result['redirect'] = $redirectUrl;
            }

            $this->getResponse()->setBody(Zend_Json::encode($result));
        }
    }

    public function saveFaPiaoAction()
    {
        $this->_expireAjax();
        if ($this->getRequest()->isPost()) {

            //Grab the submited value heared for us value
            $faPiao = $this->getRequest()->getPost();
            if ($faPiao['need_invoice'] === '1') {
                unset($faPiao['need_invoice']);
                Mage::getSingleton('core/session')->setFapiao($faPiao);
                //Mage::getSingleton('core/session')->set('fapiao',$faPiao);
            } else {

                Mage::getSingleton('core/session')->setFapiao(null);

            }
           // $result = array();
            $result = $this->getOnepage()->saveFapiao($faPiao);

            $redirectUrl = $this->getOnePage()->getQuote()->getPayment()->getCheckoutRedirectUrl();
            if (empty($result['error']) && !$redirectUrl) {
                $this->loadLayout('checkout_onepage_deliverydate');

                $result['goto_section'] = 'deliverydate';
            }

            if ($redirectUrl) {
                $result['redirect'] = $redirectUrl;
            }

            $this->getResponse()->setBody(Zend_Json::encode($result));
        }
    }
    public function saveDeliveryDateAction(){
         $this->_expireAjax();
        if ($this->getRequest()->isPost()) {
            //Grab the submited value heared for us value
            $data = $this->getRequest()->getPost();           
            //Mage::getSingleton('core/session')->set('deliverydate',$data);
            Mage::getSingleton('core/session')->setDeliverydate($data);
           // $result = array();
            $result = $this->getOnepage()->saveDeliverydate($data);
            //$redirectUrl = $this->getOnePage()->getQuote()->getDeliverydate()->getCheckoutRedirectUrl();
            //if (!$redirectUrl) {
                $this->loadLayout('checkout_onepage_review');
                $result['goto_section']   = 'review';
                $result['update_section'] = array(
                    'name' => 'review',
                    'html' => $this->_getReviewHtml()
                );

            //}

            //if ($redirectUrl) {
               // $result['redirect'] = $redirectUrl;
            //}

            $this->getResponse()->setBody(Zend_Json::encode($result));
        }
        
    }
   
    /**
     * save checkout billing address
     */
    public function saveBillingAction()
    {
        if ($this->_expireAjax()) {
            return;
        }
        if ($this->getRequest()->isPost()) {
            $data             = $this->getRequest()->getPost('billing', array());
            $data['district'] = null;

            if (!empty($data['district_id'])) {
                $data['district'] = Mage::getModel('chinaregion/district')->load($data['district_id'])->getName();
            }

            if (!empty($data['city_id'])) {
                $data['city'] = Mage::getModel('chinaregion/city')->load($data['city_id'])->getName();
            }

            $customerAddressId = $this->getRequest()->getPost('billing_address_id', false);

            if (isset($data['email'])) {
                $data['email'] = trim($data['email']);
            }
            $result = $this->getOnepage()->saveBilling($data, $customerAddressId);

            if (!isset($result['error'])) {
                /* check quote for virtual */
                if ($this->getOnepage()->getQuote()->isVirtual()) {
                    $result['goto_section']   = 'payment';
                    $result['update_section'] = array(
                        'name' => 'payment-method',
                        'html' => $this->_getPaymentMethodsHtml()
                    );
                } elseif (true || isset($data['use_for_shipping']) && $data['use_for_shipping'] == 1) {
                    $result['goto_section']   = 'shipping_method';
                    $result['update_section'] = array(
                        'name' => 'shipping-method',
                        'html' => $this->_getShippingMethodsHtml()
                    );

                    $result['allow_sections']       = array('shipping');
                    $result['duplicateBillingInfo'] = 'true';
                } else {
                    $result['goto_section'] = 'shipping';
                }
            }

            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
        }
    }

    /**
     * Shipping address save action
     */
    public function saveShippingAction()
    {
        if ($this->_expireAjax()) {
            return;
        }
        if ($this->getRequest()->isPost()) {
            $data              = $this->getRequest()->getPost('shipping', array());
            $customerAddressId = $this->getRequest()->getPost('shipping_address_id', false);
            $result            = $this->getOnepage()->saveShipping($data, $customerAddressId);

            if (!isset($result['error'])) {
                $result['goto_section']   = 'shipping_method';
                $result['update_section'] = array(
                    'name' => 'shipping-method',
                    'html' => $this->_getShippingMethodsHtml()
                );
            }
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
        }
    }


}
