<?php
/**
 * Bumbletheme
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Bumbletheme EULA that is bundled with
 * this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.bumbletheme.com/LICENSE-1.0.html
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the extension
 * to newer versions in the future. If you wish to customize the extension
 * for your needs please refer to http://www.bumbletheme.com/ for more information
 *
 * @category   Bumble
 * @package    Bumble_Blog
 * @copyright  Copyright (c) 2014 Bumbletheme (http://www.bumbletheme.com/)
 * @license    http://www.bumbletheme.com/LICENSE-1.0.html
 */

/**
 * Bumble Blog Extension
 *
 * @category   Bumble
 * @package    Bumble_Filter
 * @author     Bumbletheme Dev Team <bumbletheme@gmail.com>
 */
class Bumble_Filter_Block_Filter_Toolbar extends Mage_Core_Block_Template
{

  public function __construct($attributes = array())
  {
    parent::__construct( $attributes );
    if(!Mage::getStoreConfig('bumble_filter/general_setting/show')) {
      return;
    }
  }

  protected function _prepareLayout() {

  }
  public function getTotal() {
    return Mage::registry('paginateTotal');
  }

  public function getPages() {
    return ceil(($this->getTotal())/(int)$this->getLimitPerPage() );
  }

  public function getLimitPerPage(){
    return Mage::registry('paginateLimitPerPage');
  }

  public function getCurrentLink() {
    $module = $this->getRequest()->getModuleName();
    $controller = $this->getRequest()->getControllerName();
    $module = strtolower($module);
    if($module == "bumble_filter" || $module == "bumblefilter"){
      if($controller == "filter" || $controller == "index") {
        $filter_group_url = $this->getRequest()->getParam('category');
        //echo $filter_group_url;
        if (isset($filter_group_url)) {
            $filter_group = $filter_group_url;
        }else{
            $filter_group = $this->getRoute();
        }
        if( (int)$filter_group ) {
          return Mage::getModel('bumble_filter/group')->load((int)$filter_group)->getCategoryLink();
        } else {
          $route = $this->getRoute(); 
          return  Mage::getBaseUrl().$route;
        }
        
        
      }
    }
    return;
  }
}