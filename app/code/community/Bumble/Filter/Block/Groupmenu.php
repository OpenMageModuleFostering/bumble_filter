<?php
 /*------------------------------------------------------------------------
  * Bumble Extension Module 
  * @package             SocialConnect
  * @author              Lanzillo Ferdinando, Sabatino Francesco
  * @copyright           Copyright (C) 2016 http://www.armah.it/armah-extension-en/ All Rights Reserved.
  * @license             http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
  * @Technical-Support   http://www.armah.it/armah-extension-en/
------------------------------------------------------------------------------*/

class Bumble_Filter_Block_Groupmenu extends Bumble_Filter_Block_List {

	var $_show = true;
  /**
   * Contructor
   */
  public function __construct($attributes = array())
  {

    $this->_show = $this->getGeneralConfig("show");
    if(!$this->_show) return;

    parent::__construct( $attributes );
	  $my_template = "bumble/filter/groupmenu.phtml";
    $this->setTemplate($my_template);

  }

  public function _toHtml(){
    // if(!$this->getConfig("enable_groupmodule")) {
    //   return ;
    // }
    $collection = $this->getCategoryFilter();
    // Assign html
    $this->setCategoryFilters($collection);
    return parent::_toHtml(); 
  }
  public function getCategoryFilter(){
    $filter = Mage::getStoreConfig( 'bumble_filter/module_setting/filter_group' );
    $filter = explode(',', $filter);
    //var_dump($filter);
    //$limit = (int)$this->getGeneralConfig("limit_group",8);
    $collection = Mage::getModel('bumble_filter/filter')->getCollection();
    //->addFieldToFilter("is_active", array("eq" => 1))
    //->setPageSize($limit)
    if($filter[0] != 0)
    {
    
      $collection->addFieldToFilter("filter_id", array("in" => array($filter)));
    
    }
    
    $collection->setOrder("filter_id","DESC");

    return $collection;
  }

}