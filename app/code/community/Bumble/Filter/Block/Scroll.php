<?php 
/*------------------------------------------------------------------------
  * Bumble Extension Module 
  * @package             SocialConnect
  * @author              Lanzillo Ferdinando, Sabatino Francesco
  * @copyright           Copyright (C) 2016 http://www.armah.it/armah-extension-en/ All Rights Reserved.
  * @license             http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
  * @Technical-Support   http://www.armah.it/armah-extension-en/
---------------------------------------------------------------------------*/
class Bumble_Filter_Block_Scroll extends Bumble_Filter_Block_List 
{
	protected $_config = array();
	var $_show = true;
	var $_filter_group = null;

	/**
	 * Contructor
	 */
	public function __construct($attributes = array())
	{

  		parent::__construct($attributes);

  		 /*End Cache Block*/
        
  		$template = "";
  		$namelayout = "scroll";
  		if($this->getCarouselConfig("enable_owl_carousel")){
  			$namelayout = "carousel";
  		}


  		if($this->hasData("template") && $this->getData("template")) {
        	$template = $this->getData("template");
        } elseif(isset($attributes['template']) && $attributes['template']) {
        	$template = $attributes['template'];
        } else{
 			$template = "bumble/filter/block/".$namelayout.".phtml";
 		}

      	$this->setTemplate($template);
			
		/*Cache Block*/
        $enable_cache = $this->getConfig("enable_cache", 1 );
        if(!$enable_cache) {
          $cache_lifetime = null;
        } else {
          $cache_lifetime = $this->getConfig("cache_lifetime", 86400 );
          $cache_lifetime = (int)$cache_lifetime>0?$cache_lifetime: 86400;
        }

        $this->addData(array('cache_lifetime' => $cache_lifetime));

        $this->addCacheTag(array(
          Mage_Core_Model_Store::CACHE_TAG,
          Mage_Cms_Model_Block::CACHE_TAG,
          Bumble_Filter_Model_Filter::CACHE_BLOCK_SCROLL_TAG
        ));


	}
	/**
     * Get Key pieces for caching block content
     *
     * @return array
     */
    public function getCacheKeyInfo()
    {
        return array(
           'ARMAH_BRAND_BLOCK_SCROLL',
           $this->getNameInLayout(),
           Mage::app()->getStore()->getId(),
           Mage::getDesign()->getPackageName(),
           Mage::getDesign()->getTheme('template'),
           Mage::getSingleton('customer/session')->getCustomerGroupId(),
           'template' => $this->getTemplate(),
        );
    }
    /**
     * overrde the value of the extension's configuration
     *
     * @return string
     */
    function setConfig($key, $value) {	
    	$this->_config[$key] = $value;
    	return $this;
    }

	public function getGeneralConfig( $val, $default = "" ){ 

		return Mage::getStoreConfig( "bumble_filter/general_setting/".$val );
	}
	public function getCarouselConfig( $key, $default = null ){
		return $this->getConfig( $key,$default,"carousel_setting" );
	}

	public function getModuleConfig( $val, $default = "" ){
		$return = "";
	    $value = $this->getData($val);
	    //Check if has widget config data
	    if($this->hasData($val) && $value !== null) {

	      if($value == "true") {
	        return 1;
	      } elseif($value == "false") {
	        return 0;
	      }
	      return $value;
	      
	    } else {

	      if(isset($this->_config[$val])){
	        $return = $this->_config[$val];
	      }else{
	        $return = Mage::getStoreConfig("bumble_filter/module_setting/".$val );
	      }
	      if($return == "" && $default) {
	        $return = $default;
	      }

	    }

	    return $return;
	}
	
	protected function _toHtml(){
		$this->_show = $this->getGeneralConfig("show");
		$enable_scroll = $this->getConfig("enable_scrollmodule");
		$limit = (int)$this->getConfig('itemvisiable');

		
		if(!$this->_show || !$enable_scroll) return;
		$collection = Mage::getModel( 'bumble_filter/group' )
						->getCollection();
						
		
		
		$collection->addFieldToFilter('is_active', 1)
					->setOrder( 'position', 'ASC' );

		if($limit){
			$collection ->setPageSize($limit);
		}
		
		
		$extension = ".html";
		foreach( $collection as $model ){
			if(!$model->getLink()){

				$id=$model->getFilterId();
				$filterGroup = Mage::getModel('bumble_filter/filter')->load($id);
				$resroute = $filterGroup->getIdentifier();
				Mage::getModel('core/url_rewrite')->loadByIdPath('bumblefilter/filter/'.$id())
							->setIdPath('bumblefilter/filter/'.$id())
							->setRequestPath($resroute .'/'.$model->getIdentifier().$extension  )
							->setTargetPath('bumblefilter/filter/view/id/'.$id())
							->save();
			}	
		}
		
		$this->assign('filters', $collection );
		$this->assign('resroute',$resroute);
		return parent::_toHtml();
		
	}

	public function getMoreViewLink() {
		if($this->_filter_group) {
			return $this->_filter_group->getCategoryLink();
		}else{
			$resroute = Mage::getStoreConfig('bumble_filter/general_setting/route');
			if ($resroute == "") {
				$resroute = "bumblefilter";
			}
			return Mage::getBaseUrl().$resroute;
		}
		return;
	}

}	