<?php 
/*------------------------------------------------------------------------
 # BumbleTheme Filter Module 
 # ------------------------------------------------------------------------
 # author:    BumbleTheme.Com
 # copyright: Copyright (C) 2012 http://www.bumbletheme.com. All Rights Reserved.
 # @license: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 # Websites: http://www.bumbletheme.com
 # Technical Support:  http://www.bumbletheme.com/
-------------------------------------------------------------------------*/
class Bumble_Filter_Block_Filternav extends Bumble_Filter_Block_List 
{

	var $_show = true;
	protected $_config = array();
	/**
	 * Contructor
	 */
	public function __construct($attributes = array()){

		parent::__construct( $attributes );
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
	      if($return == "" && !$default) {
	        $return = $default;
	      }

	    }

	    return $return;
	}

	public function _toHtml(){
		$this->_show = $this->getGeneralConfig("show");
 		$enable_scroll = $this->getModuleConfig("enable_filternavmodule");
 		
		if(!$this->_show || !$enable_scroll) return;

		$this->setTemplate( "bumble/filter/block/filternav.phtml" );
		
		$collection = Mage::getModel( "bumble_filter/filter" )->getCollection();
		$limit = $this->getModuleConfig("filternav_limit");
		if($limit != "" || (int)$limit >0) {
			$collection->setPageSize($limit)->setCurPage(1);
		}
		$this->assign( "filters", $collection );
		return parent::_toHtml();	
	}
	 
}
?>