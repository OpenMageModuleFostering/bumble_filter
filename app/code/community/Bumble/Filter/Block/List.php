<?php
/*------------------------------------------------------------------------
  * Bumble Extension Module 
  * @package             SocialConnect
  * @author              Lanzillo Ferdinando, Sabatino Francesco
  * @copyright           Copyright (C) 2016 http://www.armah.it/armah-extension-en/ All Rights Reserved.
  * @license             http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
  * @Technical-Support   http://www.armah.it/armah-extension-en/
---------------------------------------------------------------------------*/
class Bumble_Filter_Block_List extends Mage_Core_Block_Template 
{
	/**
	 * @var string $_config
	 * 
	 * @access protected
	 */
	protected $_config = array();
	
	/**
	 * @var string $_config
	 * 
	 * @access protected
	 */
	protected $_listDesc = array();
	
	/**
	 * @var string $_config
	 * 
	 * @access protected
	 */
	protected $_show = 0;
	protected $_theme = "";
	
	/**
	 * Contructor
	 */
	public function __construct($attributes = array())
	{
		$this->convertAttributesToConfig($attributes);
		parent::__construct();

		$cms_block_id = $this->getConfig('cmsblock');
		$cms = "";
 		if($cms_block_id){
 			$cms = Mage::getSingleton('core/layout')->createBlock('cms/block')->setBlockId($cms_block_id)->toHtml();
 		}
 		$this->assign( "cms", $cms );

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
          Bumble_Filter_Model_Filter::CACHE_BLOCK_LIST_TAG
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
           'ARMAH_BRAND_BLOCK_LIST',
           $this->getNameInLayout(),
           Mage::app()->getStore()->getId(),
           Mage::getDesign()->getPackageName(),
           Mage::getDesign()->getTheme('template'),
           Mage::getSingleton('customer/session')->getCustomerGroupId(),
           'template' => $this->getTemplate(),
        );
    }

	public function convertAttributesToConfig($attributes = array()) {
      if($attributes) {
        foreach($attributes as $key=>$val) {
            $this->setConfig($key, $val);
        }
      }
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
	public function getConfig( $val, $default = "", $group = "module_setting" ){
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
	        if($return == "true") {
	            $return = 1;
	          } elseif($return == "false") {
	            $return = 0;
	          }

	      }else{
	        $return = Mage::getStoreConfig("bumble_filter/{$group}/".$val );
	      }
	      if($return == "" && $default) {
	        $return = $default;
	      }

	    }

	    return $return;
	}

	public function getGeneralConfig( $val, $default = "" ){ 
		return Mage::getStoreConfig( "bumble_filter/general_setting/".$val );
	}


}
