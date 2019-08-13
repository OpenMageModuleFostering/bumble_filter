<?php
/*------------------------------------------------------------------------
  * Bumble Extension Module 
  * @package             SocialConnect
  * @author              Lanzillo Ferdinando, Sabatino Francesco
  * @copyright           Copyright (C) 2016 http://www.armah.it/armah-extension-en/ All Rights Reserved.
  * @license             http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
  * @Technical-Support   http://www.armah.it/armah-extension-en/
---------------------------------------------------------------------------*/

class Bumble_Filter_Block_Filter_List extends Mage_Catalog_Block_Product_List {
  
  var $_show = true;
  /**
   * Contructor
   */
  public function __construct($attributes = array())
  {
    

    parent::__construct( $attributes );

    $config_template = $this->getGeneralConfig("listing_layout");

    $my_template = "";
    if(isset($attributes['template']) && $attributes['template']) {

      $my_template = $attributes['template'];

    } elseif($this->hasData("template")) {
      $my_template = $this->getData("template");

    }else {
      $my_template = "bumble/filter/default.phtml";
    }

    $this->setTemplate($my_template);

  }

  public function getGeneralConfig( $val ){ 
    return Mage::getStoreConfig( "bumble_filter/general_setting/".$val );
  }
  
  public function getConfig( $val ){ 
    return Mage::getStoreConfig( "bumble_filter/module_setting/".$val );
  }
  protected function _getTitleCurrentGroup()
  {
    $url= $_SERVER['HTTP_HOST'];
         $url2 = $_SERVER["REQUEST_URI"];
         $route= str_replace($url, '', $url2);
         $route = str_replace('en/', '', $route);
         $route= str_replace('/', '', $route);
        $all = Mage::getModel('bumble_filter/filter')->getCollection();
        foreach ($all as $key) {
          if($route == $key->getIdentifier()){
                $title =  $this->__($key->getTitle());
          }
        }
        return $title;
  }
  
    protected function _prepareLayout()
    {
     //   $id     = $this->getRequest()->getParam('id');


        $title = $this->_getTitleCurrentGroup();
        $module = $this->getRequest()->getModuleName();
        $filter_group = $this->getRequest()->getParam('category');
        $route = $this->getGeneralConfig("route");
        if (!$route) {
          $route = $module;
        }
       

        $breadcrumbs = $this->getLayout()->getBlock('breadcrumbs');
        

        $breadcrumbs->addCrumb( 'bumble_filter', array( 'label' => $title,
          'title' => $title,
           ));
        //set title by list all filter
        $this->setTitleFilter($title);
        //set by group 
        if (isset($filter_group)) {
          //set tile by group
          $this->setTitleFilter($this->getGroup($filter_group)['name']);
          $breadcrumbs->addCrumb( 'bumble_group', array( 'label' => $this->getGroup($filter_group)['name'],
          'title' => Mage::helper('bumble_filter')->__($this->getGroup($filter_group)['name']),
          'link'  => Mage::getBaseUrl().$route.'/'.$this->getGroup($filter_group)['identifier'].'.html') );

          $this->getLayout()->getBlock('head')->setTitle($title."-".$this->getGroup($filter_group)['name']);
        }else{
          $this->getLayout()->getBlock('head')->setTitle($title);
        }

        $this->getCountingPost();

        return parent::_prepareLayout();
    }
  public function _toHtml(){
    $grid_col_ls = $this->getGeneralConfig("grid_col_ls");
    $grid_col_ls = $grid_col_ls?(int)$grid_col_ls:3;
    $grid_col_ms = $this->getGeneralConfig("grid_col_ms");
    $grid_col_ms = $grid_col_ms?(int)$grid_col_ms:3;
    $grid_col_ss = $this->getGeneralConfig("grid_col_ss");
    $grid_col_ss = $grid_col_ss?(int)$grid_col_ss:2;
    $grid_col_mss = $this->getGeneralConfig("grid_col_mss");
    $grid_col_mss = $grid_col_mss?(int)$grid_col_mss:1;

    $this->assign("grid_col_ls", $grid_col_ls);
    $this->assign("grid_col_ms", $grid_col_ms);
    $this->assign("grid_col_ss", $grid_col_ss);
    $this->assign("grid_col_mss", $grid_col_mss);
    return parent::_toHtml();
  }
  public function getLayoutMode() {
    return $this->getGeneralConfig("listing_layout");
  }
  public function getFilters(){
    $page = $this->getRequest()->getParam('page') ? $this->getRequest()->getParam('page') : 1;
    $page = (($page - 1) > 0)?($page-1):0;
    $limit = (int)$this->getGeneralConfig("list_limit");
    $keyword = $this->getRequest()->getParam( "search_query" );
    $keyword = trim($keyword);
    $filter_group_url = $this->getRequest()->getParam('category');
    $filter_group = 0;
    if (isset($filter_group_url)) {
        $filter_group = $filter_group_url;
    }else{

        $url= $_SERVER['HTTP_HOST'];
         $url2 = $_SERVER["REQUEST_URI"];
         $route= str_replace($url, '', $url2);
         $route = str_replace('en/', '', $route);
         $route= str_replace('/', '', $route);

         $resource = Mage::getSingleton('core/resource');
        $readConnection = $resource->getConnection('core_read');

        $query = "SELECT `filter_id` FROM  bumble_filter_filter WHERE `identifier`= '$route'";

        $results = $readConnection->fetchAll($query);
        $results = $results[0]['filter_id'];


        $filter_group = $results;
    }
    $grouparr = explode(",", $filter_group);

    $collection = Mage::getModel('bumble_filter/group')->getCollection();
    if($filter_group) {
       $collection->addFieldToFilter("filter_id", array("in" => $grouparr ));
    }
    

    if($keyword && strlen($keyword) >= 3) {
       $collection->addKeywordFilter($keyword);
    }

    $collection->getSelect()->limit($limit, $page*$limit);

    return $collection;

  }

  public function getCountingPost(){
    $limit = (int)$this->getGeneralConfig("list_limit");
    $keyword = $this->getRequest()->getParam( "search_query" );
    $keyword = trim($keyword);

    $filter_group_url = $this->getRequest()->getParam('category');

    if (isset($filter_group_url)) {
        $filter_group = $filter_group_url;
    }else{
        $filter_group = $this->getConfig('filter_group');
    }
    
    $collection = Mage::getModel('bumble_filter/filter')->getCollection();
    $grouparr = explode(",", $filter_group);
    if($filter_group) {
       $collection->addFieldToFilter("group_filter_id", array("in" => $grouparr ));
    }

    if($keyword && strlen($keyword) >= 3) {
        $collection->addKeywordFilter($keyword);
    }
    

    Mage::register( 'paginateTotal', count($collection) );
    Mage::register( "paginateLimitPerPage", $limit );
  }

 
  public function getFilter() {
      return Mage::registry('current_group');
  }
  public function getGroup($id_group) {
      $collection = Mage::getModel('bumble_filter/group')->load($id_group);
      $data = array('name' => $collection->getName(), 'identifier' => $collection->getIdentifier());
      return $data;
  }

}
?>