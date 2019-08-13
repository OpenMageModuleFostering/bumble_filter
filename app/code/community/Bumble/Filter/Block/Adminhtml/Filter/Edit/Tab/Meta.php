<?php
/*------------------------------------------------------------------------
  * Bumble Extension Module 
  * @package             SocialConnect
  * @author              Lanzillo Ferdinando, Sabatino Francesco
  * @copyright           Copyright (C) 2016 http://www.armah.it/armah-extension-en/ All Rights Reserved.
  * @license             http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
  * @Technical-Support   http://www.armah.it/armah-extension-en/
---------------------------------------------------------------------------*/
class Bumble_Filter_Block_Adminhtml_Filter_Edit_Tab_Meta extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $_model = Mage::registry('filter_data');
        $form = new Varien_Data_Form();
        $this->setForm($form);

		$fieldset = $form->addFieldset('category_meta', array('legend'=>Mage::helper('bumble_filter')->__('Meta Information')));
        
		
		$fieldset->addField('meta_keywords', 'editor', array(
			'label'     => Mage::helper('bumble_filter')->__('Meta Keywords'),
			'class'     => '',
			'required'  => false,
			'name'      => 'meta_keywords',
			'style'     => 'width:600px;height:100px;',
			'wysiwyg'   => false
		));
		$fieldset->addField('meta_description', 'editor', array(
			'label'     => Mage::helper('bumble_filter')->__('Meta Description'),
			'class'     => '',
			'required'  => false,
			'name'      => 'meta_description',
			'style'     => 'width:600px;height:100px;',
			'wysiwyg'   => false
		));
		
        
		if ( Mage::getSingleton('adminhtml/session')->getFilterData() )
		  {
			  $form->setValues(Mage::getSingleton('adminhtml/session')->getFilterData());
			  Mage::getSingleton('adminhtml/session')->getFilterData(null);
		  } elseif ( Mage::registry('filter_data') ) {
			  $form->setValues(Mage::registry('filter_data')->getData());
		  }
        
        return parent::_prepareForm();
    }
	
	  
}
