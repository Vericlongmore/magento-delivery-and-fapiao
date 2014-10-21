<?php
class More_ChinaInvoice_Model_Observer
{

    const ORDER_ATTRIBUTE_FHC_ID = 'fapiao';

    /**
     * Event Hook: checkout_type_onepage_save_order
     *
     * @author Branko Ajzele
     *
     * @param $observer Varien_Event_Observer
     */
    public function hookToOrderSaveEvent($observer)
    {
        $order=Mage::getModel('sales/order')->load($observer->getEvent()->getOrder()->getId());
        $faPiao = Mage::getSingleton('core/session')->getFapiao();
        if ($faPiao) {
            foreach ($faPiao as $key => $value) {
                $order->setData($key, $value);
            }
        }
        $deliverydate = Mage::getSingleton('core/session')->getDeliverydate();
        if ($deliverydate) {
            foreach ($deliverydate as $key => $value) {
                $order->setData($key, $value);
            }
        }
        $order->save();

    }


}