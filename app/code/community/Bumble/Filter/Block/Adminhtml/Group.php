<?php
 /*------------------------------------------------------------------------
  * Bumble Extension Module 
  * @package             SocialConnect
  * @author              Lanzillo Ferdinando, Sabatino Francesco
  * @copyright           Copyright (C) 2016 http://www.armah.it/armah-extension-en/ All Rights Reserved.
  * @license             http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
  * @Technical-Support   http://www.armah.it/armah-extension-en/
---------------------------------------------------------------------------*/
class Bumble_Filter_Block_Adminhtml_Group extends Mage_Adminhtml_Block_Widget_Form_Container {

    public function __construct() {
    
        $this->_controller = 'adminhtml_group';
        $this->_blockGroup = 'bumble_filter';
        $this->_headerText = Mage::helper('bumble_filter')->__('Filter Manager');

        parent::__construct();
        $this->setTemplate('bumble_filter/group.phtml');
        $helper =  Mage::helper('bumble_filter/data');
        /*End init meida files*/
        $mediaHelper =  Mage::helper('bumble_filter/media');  
    }

    public function listAssign(){
      $module = 0;
     // $items = $this->getListProducts();
        $menus = array();
        $menus["grouptabs"] =  array("link"=>$this->getUrl('*/group/index'),"title"=>$this->__("Group Filter"));
        $menus["tabs"] =  array("link"=>$this->getUrl('*/*/index'),"title"=>$this->__("Filter"));
        
        $this->assign( "menus", $menus);
        $this->assign( "menu_active", "group");
        $this->assign( "module", $module++);
    }

    public function getEffectConfig( $key ){
      return $this->getConfig( $key, "effect_setting" );
    }
    /**
     * get value of the extension's configuration
     *
     * @return string
     */
    function getConfig( $key, $panel='bumble_filter' ){
      if(isset($this->_config[$key])) {
        return $this->_config[$key];
      } else {
        return Mage::getStoreConfig("bumble_filter/$panel/$key");
      }
    }

    protected function _prepareLayout() {
  
        $this->setChild('add_new_button',
                $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                'label'     => Mage::helper('bumble_filter')->__('Add Record'),
                'onclick'   => "setLocation('".$this->getUrl('*/*/add')."')",
                'class'   => 'add'
                ))
        );
        $this->setChild('mass_rewrite_button',
                $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                'label'     => Mage::helper('bumble_filter')->__('Mass Generate Rewrite URLs'),
                'onclick'   => "setLocation('".$this->getUrl('*/*/massRewrite')."')",
                'class'   => 'mass'
                ))
        );
    $this->setChild('mass_resize_button',
                $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                'label'     => Mage::helper('bumble_filter')->__('Mass Resize Logo'),
                'onclick'   => "setLocation('".$this->getUrl('*/*/massResize')."')",
                'class'   => 'mass'
                ))
        );
        $this->setChild('grid', $this->getLayout()->createBlock('bumble_filter/adminhtml_group_grid', 'group.grid'));
        return parent::_prepareLayout();
    }


    public function getMassRewriteCatButtonHtml(){
      return $this->getChildHtml('mass_rewrite_button');
  }
  public function getMassResizeButtonHtml(){
      return $this->getChildHtml('mass_resize_button');
  }
    public function getAddNewButtonHtml() {
        return $this->getChildHtml('add_new_button');
    }
    public function getGridHtml() {
        return $this->getChildHtml('grid');
    }
    //public function getStoreSwitcherHtml() {
     //   return $this->getChildHtml('store_switcher');
    //}
}




