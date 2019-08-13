<?php
 /*------------------------------------------------------------------------
  * Bumble Extension Module 
  * @package             SocialConnect
  * @author              Lanzillo Ferdinando, Sabatino Francesco
  * @copyright           Copyright (C) 2016 http://www.armah.it/armah-extension-en/ All Rights Reserved.
  * @license             http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
  * @Technical-Support   http://www.armah.it/armah-extension-en/
--------------------------------------------------------------------------*/
class Bumble_Filter_Block_Adminhtml_Filter_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('filter_form');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bumble_filter')->__('Group Information'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label'     => Mage::helper('bumble_filter')->__('General Information'),
            'title'     => Mage::helper('bumble_filter')->__('General Information'),
            'content'   => $this->getLayout()->createBlock('bumble_filter/adminhtml_filter_edit_tab_form')->toHtml(),
        ));
		$this->addTab('form_section_seo', array(
            'label'     => Mage::helper('bumble_filter')->__('SEO'),
            'title'     => Mage::helper('bumble_filter')->__('SEO'),
            'content'   => $this->getLayout()->createBlock('bumble_filter/adminhtml_filter_edit_tab_meta')->toHtml(),
        ));
		/*
		$this->addTab('form_section_params', array(
            'label'     => Mage::helper('bumble_filter')->__('Parameters'),
            'title'     => Mage::helper('bumble_filter')->__('Parameters'),
            'content'   => $this->getLayout()->createBlock('bumble_filter/adminhtml_filter_edit_tab_param')->toHtml(),
        ));
		*/
        return parent::_beforeToHtml();
    }
}