<?php
 /*------------------------------------------------------------------------
  * Bumble Extension Module 
  * @package             SocialConnect
  * @author              Lanzillo Ferdinando, Sabatino Francesco
  * @copyright           Copyright (C) 2016 http://www.armah.it/armah-extension-en/ All Rights Reserved.
  * @license             http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
  * @Technical-Support   http://www.armah.it/armah-extension-en/
--------------------------------------------------------------------------*/

class Bumble_Filter_Block_Adminhtml_Category_Edit_Tab_Param extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $_model = Mage::registry('category_data');
        $form = new Varien_Data_Form();
        $this->setForm($form);

        $fieldset = $form->addFieldset('category_params', array('legend'=>Mage::helper('bumble_filter')->__('Parameter')));
		
		$fieldset->addField('template', 'select', array(
			'label'     => Mage::helper('bumble_filter')->__('Template'),
			'name'      => 'param[template]',
			'values'    => array( 0=> $this->__("No"), 1=> $this->__("Yes") )
		));
		
		$fieldset->addField('show_childrent', 'select', array(
			'label'     => Mage::helper('bumble_filter')->__('Show Childrent'),
			'name'      => 'param[show_childrent]',
			'values'    => array( 0=> $this->__("No"), 1=> $this->__("Yes") )
		));
		
		$fieldset->addField('primary_cols', 'text', array(
			'label'     => Mage::helper('bumble_filter')->__('Show Childrent'),
			'name'      => 'param[primary_cols]',
			'default'   => '2'
		));
		 
        
		if ( Mage::getSingleton('adminhtml/session')->getCategoryData() )
		  {
			  $form->setValues(Mage::getSingleton('adminhtml/session')->getCategoryData());
			  Mage::getSingleton('adminhtml/session')->getCategoryData(null);
		  } elseif ( Mage::registry('category_data') ) {
			  $form->setValues(Mage::registry('category_data')->getData());
		  }
        
        return parent::_prepareForm();
    }
	
	  public function getParentToOptionArray() {
		$catCollection = Mage::getModel('bumble_filter/category')
					->getCollection();
		$id = Mage::registry('category_data')->getId();
		if($id) {
			$catCollection->addFieldToFilter('category_id', array('neq' => $id));
		}
		$option = array();
		$option[] = array( 'value' => 0, 
						   'label' => 'Top Level');
		foreach($catCollection as $cat) {
			$option[] = array( 'value' => $cat->getId(), 
							   'label' => $cat->getTitle() );
		}
		return $option;
    }
}
