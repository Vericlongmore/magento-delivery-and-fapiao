class More_Mobile_RedirectController extends Mage_Core_Controller_Front_Action
{
    
    protected function _addNotice($message)
    {
        Mage::getSingleton('core/session')->addNotice($message);
        return $this;
    }

    public function customerAction()
    {
        $this->_addNotice(Mage::helper('Moremobile')->__('The opportunity of using this tab is not supported yet'));

        $this->_redirect('customer/account');
    }


}
