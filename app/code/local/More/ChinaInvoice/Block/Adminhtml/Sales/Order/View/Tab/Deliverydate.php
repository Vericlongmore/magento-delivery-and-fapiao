<?php
/**
 * Created by  More@dev
 *
 * @file Fapiao.php
 * @author More <874938361@qq.com>
 * @copyright More 
 * @link http://blog.itiwin.cn
 *
 *
 */

class More_ChinaInvoice_Block_Adminhtml_Sales_Order_View_Tab_Deliverydate
    extends Mage_Adminhtml_Block_Template
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{

    protected $_chat = null;

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('deliverydate/deliverydate.phtml');
    }

    public function getTabLabel() {
        return $this->__('Delivery Date');
    }

    public function getTabTitle() {
        return $this->__('Delivery Date');
    }

    public function canShowTab() {
        return true;
    }

    public function isHidden() {
        return false;
    }

    public function getOrder(){
        return Mage::registry('current_order');
    }
}