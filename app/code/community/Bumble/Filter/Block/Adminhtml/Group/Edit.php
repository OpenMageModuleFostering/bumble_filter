<?php
 /*------------------------------------------------------------------------
  * Bumble Extension Module 
  * @package             SocialConnect
  * @author              Lanzillo Ferdinando, Sabatino Francesco
  * @copyright           Copyright (C) 2016 http://www.armah.it/armah-extension-en/ All Rights Reserved.
  * @license             http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
  * @Technical-Support   http://www.armah.it/armah-extension-en/
---------------------------------------------------------------------------*/
class Bumble_Filter_Block_Adminhtml_Group_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {

    public function __construct() {
      $url = $_SERVER["REQUEST_URI"];
      $route =strtok($_SERVER["REQUEST_URI"],'?');
		    parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup  = 'bumble_filter';
        $this->_controller  = 'adminhtml_group';

        $this->_updateButton('save', 'label', Mage::helper('bumble_filter')->__('Save Group'));
        //$this->_updateButton('delete', 'label', Mage::helper('bumble_filter')->__('Delete Record'));
        $this->_removeButton('delete');
        
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);
        

        $this->_formScripts[] = "
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/*');
            }

        ";
         $this->_formScripts[] = "
            function PrevSave(){
                editForm.submit($('edit_form').action+'Prev/*');
            }
            
        ";
        $this->_formScripts[] = "
            function NextSave(){
                editForm.submit($('edit_form').action+'Next/*');
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
                   ->setSwitchUrl($this->getUrl('*/*/*/id/'.Mage::registry('group_data')->get('group_id'), array('store'=>null)))
           );
        }

        return parent::_prepareLayout();
    }
    public function getStoreSwitcherHtml() {
       return $this->getChildHtml('store_switcher');
    }
    public function getHeaderText()
    {
          return Mage::helper('bumble_filter')->__("Configuration Group");
        
    }
}