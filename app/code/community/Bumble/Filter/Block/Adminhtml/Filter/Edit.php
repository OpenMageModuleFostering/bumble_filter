<?php
 /*------------------------------------------------------------------------
  * Bumble Extension Module 
  * @package             SocialConnect
  * @author              Lanzillo Ferdinando, Sabatino Francesco
  * @copyright           Copyright (C) 2016 http://www.armah.it/armah-extension-en/ All Rights Reserved.
  * @license             http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
  * @Technical-Support   http://www.armah.it/armah-extension-en/
----------------------------------------------------------------------------*/

class Bumble_Filter_Block_Adminhtml_Filter_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
        $id     = $this->getRequest()->getParam('id');
        $collection = Mage::getModel('bumble_filter/filter')->load($id);       
        $this->_objectId = 'id';
        $this->_blockGroup  = 'bumble_filter';
        $this->_controller  = 'adminhtml_filter';

        
        //$this->_updateButton('delete', 'label', Mage::helper('bumble_filter')->__('Delete Record'));
		    $this->_removeButton('delete');
        if($collection->getFilterId() != null){
          //$this->_removeButton('save');
          $this->_updateButton('save', 'label', Mage::helper('bumble_filter')->__('Save Changes And Go in Configuration Group'));
          $this->_addButton('saveandrecord', array(
            'label'     => Mage::helper('adminhtml')->__('Save Changes'),
            'onclick'   => 'saveAndBackToManageGroups()',
            'class'     => 'save',
          ), -100);
          $this->_formScripts[] = "
            function saveAndBackToManageGroups(){
                editForm.submit($('edit_form').action+'backto/*');
            }
          ";
        }else{
          $this->_updateButton('save', 'label', Mage::helper('bumble_filter')->__('Save And Go in Configuration Group'));
        }
 
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
        
    }
    protected function _prepareLayout() {
         /**
         * Display store switcher if system has more one store
         */
        if (!Mage::app()->isSingleStoreMode()) {
            $this->setChild('store_switcher',
                   $this->getLayout()->createBlock('adminhtml/store_switcher')
                   ->setUseConfirm(false)
                   ->setSwitchUrl($this->getUrl('*/*/*/id/'.Mage::registry('filter_data')->getData('filter_id'), array('store'=>null)))
           );
        }

        return parent::_prepareLayout();
    }
    public function getStoreSwitcherHtml() {
       return $this->getChildHtml('store_switcher');
    }
    public function getHeaderText()
    {
        if( Mage::registry('filter_data')->getId() ) {
			return Mage::helper('bumble_filter')->__("Edit Record '%s'", $this->htmlEscape(Mage::registry('filter_data')->getTitle()));
		}else {
			return Mage::helper('bumble_filter')->__("Add New Group");
		}
	}
}