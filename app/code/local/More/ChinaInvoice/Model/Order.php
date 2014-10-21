<?php
class More_ChinaInvoice_Model_Order extends Mage_Sales_Model_Order
{
    public function sendNewOrderEmail()
    {
        $storeId = $this->getStore()->getId();

        if (!Mage::helper('sales')->canSendNewOrderEmail($storeId)) {
            return $this;
        }
        // Get the destination email addresses to send copies to
        $copyTo = $this->_getEmails(self::XML_PATH_EMAIL_COPY_TO);
        $copyMethod = Mage::getStoreConfig(self::XML_PATH_EMAIL_COPY_METHOD, $storeId);

        // Start store emulation process
        $appEmulation = Mage::getSingleton('core/app_emulation');
        $initialEnvironmentInfo = $appEmulation->startEnvironmentEmulation($storeId);

        try {
            // Retrieve specified view block from appropriate design package (depends on emulated store)
            $paymentBlock = Mage::helper('payment')->getInfoBlock($this->getPayment())
                ->setIsSecureMode(true);
            $paymentBlock->getMethod()->setStore($storeId);
            $paymentBlockHtml = $paymentBlock->toHtml();
        } catch (Exception $exception) {
            // Stop store emulation process
            $appEmulation->stopEnvironmentEmulation($initialEnvironmentInfo);
            throw $exception;
        }

        // Stop store emulation process
        $appEmulation->stopEnvironmentEmulation($initialEnvironmentInfo);

        // Retrieve corresponding email template id and customer name
        if ($this->getCustomerIsGuest()) {
            $templateId = Mage::getStoreConfig(self::XML_PATH_EMAIL_GUEST_TEMPLATE, $storeId);
            $customerName = $this->getBillingAddress()->getName();
        } else {
            $templateId = Mage::getStoreConfig(self::XML_PATH_EMAIL_TEMPLATE, $storeId);
            $customerName = $this->getCustomerName();
        }

        $mailer = Mage::getModel('core/email_template_mailer');
        $emailInfo = Mage::getModel('core/email_info');
        $emailInfo->addTo($this->getCustomerEmail(), $customerName);
        if ($copyTo && $copyMethod == 'bcc') {
            // Add bcc to customer email
            foreach ($copyTo as $email) {
                $emailInfo->addBcc($email);
            }
        }
        $mailer->addEmailInfo($emailInfo);

        // Email copies are sent as separated emails if their copy method is 'copy'
        if ($copyTo && $copyMethod == 'copy') {
            foreach ($copyTo as $email) {
                $emailInfo = Mage::getModel('core/email_info');
                $emailInfo->addTo($email);
                $mailer->addEmailInfo($emailInfo);
            }
        }

        //added by vicky
        Mage::log('orderId='.$this->getId(),null,'test.log');
        $order = $this->load($this->getId());
        Mage::log('bill_type='.$order->getBillType(),null,'test.log');
        $invoiceHtml='Invoice Type :'.$order->getBillType().'<br/>';
        if ($order->getBillTitle() == 'Units') {
            $title = $order->getBillCompany();
        } else {
            $title =$customerName ;
        }
        $invoiceHtml.='Invoice Payable :'.$title.'<br/>';
        $invoiceHtml.='Invoice Content :'.$order->getBillContent().'<br/>';
        if ($order->getBillType() == 'VAT Invoice'){
            $invoiceHtml.='Taxpayer identification number :'.$order->getBillTaxpayerId().'<br/>';
            $invoiceHtml.='Registered Address :'.$order->getBillAddress().'<br/>';
            $invoiceHtml.='Registered Telephone :'.$order->getBillPhone().'<br/>';
            $invoiceHtml.='The bank :'.$order->getBillBank().'<br/>';
            $invoiceHtml.='Bank account :'.$order->getBillAccount().'<br/>';
        }

        // Set all required params and send emails
        $mailer->setSender(Mage::getStoreConfig(self::XML_PATH_EMAIL_IDENTITY, $storeId));
        $mailer->setStoreId($storeId);
        $mailer->setTemplateId($templateId);
        $mailer->setTemplateParams(array(
                                        'order'        => $this,
                                        'billing'      => $this->getBillingAddress(),
                                        'payment_html' => $paymentBlockHtml,
                                        'invoice_html' => $invoiceHtml

                                   )
        );
        $mailer->send();

        $this->setEmailSent(true);
        $this->_getResource()->saveAttribute($this, 'email_sent');

        return $this;
    }
}

