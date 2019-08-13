<?php 
 /*------------------------------------------------------------------------
  * Bumble Extension Module 
  * @package             SocialConnect
  * @author              Lanzillo Ferdinando, Sabatino Francesco
  * @copyright           Copyright (C) 2016 http://www.armah.it/armah-extension-en/ All Rights Reserved.
  * @license             http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
  * @Technical-Support   http://www.armah.it/armah-extension-en/
------------------------------------------------------------------*/

class Bumble_Filter_FilterController extends Mage_Core_Controller_Front_Action
{  	
	public function indexAction(){
		 
	 	$show = $this->getGeneralConfig("show");
	 	if(!$show) {
	 		$this->getResponse()->setHeader('HTTP/1.1','404 Not Found');
			$this->getResponse()->setHeader('Status','404 File not found');
			$this->_redirect('404-notfound');
	 	}
	 	if($this->getRequest()->getParam('category')){
        		Mage::register('category_filter', $this->getRequest()->getParam('category'));
        }
		
	Mage::dispatchEvent(
            'catalog_controller_category_init_before',
            array(
                'controller_action' => $this
            )
        );
 
        $rootCategoryId = (int) Mage::app()->getStore()->getRootCategoryId();
        if (!$rootCategoryId) {
            $this->_forward('noRoute');
            return;
        }
 
        $rootCategory = Mage::getModel('catalog/category')
            ->load($rootCategoryId)
 
            // TODO: Fetch from config
            ->setName($this->__('Sale'))
            ->setMetaTitle($this->__('Sale'))
            ->setMetaDescription($this->__('Sale'))
            ->setMetaKeywords($this->__('Sale'));
 
        Mage::register('current_category', $rootCategory);
 
        Mage::getSingleton('catalog/session')
            ->setLastVisitedCategoryId($rootCategory->getId());
 
        try {
            Mage::dispatchEvent('catalog_controller_category_init_after',
                array(
                    'category' => $rootCategory,
                    'controller_action' => $this
                )
            );
        } catch (Mage_Core_Exception $e) {
            Mage::logException($e);
            return;
        }
 
        // Observer can change category
        if (!$rootCategory->getId()){
            $this->_forward('noRoute');
            return;
        }
 
        $this->loadLayout();
 
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('checkout/session');
 
        $this->renderLayout();
    }
	
	public function viewAction(){
		$show = $this->getGeneralConfig("show");
	 	if(!$show) {
	 		$this->getResponse()->setHeader('HTTP/1.1','404 Not Found');
			$this->getResponse()->setHeader('Status','404 File not found');
			$this->_redirect('404-notfound');
	 	}


		$id = (int) $this->getRequest()->getParam( 'id', false);

        $filter = Mage::getModel('bumble_filter/group')->load($id);
        Mage::register('current_group', $filter);
       

		$this->loadLayout();
		$this->renderLayout();
	}

	public function getGeneralConfig( $val, $default = "" ){ 
		return Mage::getStoreConfig( "bumble_filter/general_setting/".$val );
	}
}
?>