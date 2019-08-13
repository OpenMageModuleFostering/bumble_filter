<?php
/*------------------------------------------------------------------------
  * Bumble Extension Module 
  * @package             SocialConnect
  * @author              Lanzillo Ferdinando, Sabatino Francesco
  * @copyright           Copyright (C) 2016 http://www.armah.it/armah-extension-en/ All Rights Reserved.
  * @license             http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
  * @Technical-Support   http://www.armah.it/armah-extension-en/
---------------------------------------------------------------------------*/

class Bumble_Filter_Model_Filter extends Mage_Core_Model_Abstract
{
	const CACHE_BLOCK_SCROLL_TAG = "bumble_filter_scroll";
	const CACHE_BLOCK_LIST_TAG = "bumble_filter_scroll";
	const CACHE_WIDGET_SCROLL_TAG = "bumble_filter_widget_scroll";
	const CACHE_WIDGET_LIST_TAG = "bumble_filter_widget_scroll";

    protected function _construct() {	
        $this->_init('bumble_filter/filter');
    }
	
	public function getLink(){
		return  Mage::getBaseUrl().Mage::getModel('core/url_rewrite')->loadByIdPath('bumblefilter/filter/'.$this->getId())->getRequestPath();
	}
	
	public function getImageUrl($type='l') {
		$tmp = explode("/", $this->getFile());
		$imageName = $type."-".$tmp[count($tmp)-1];
		return Mage::getBaseUrl( Mage_Core_Model_Store::URL_TYPE_MEDIA)."resized/".$imageName;
	}
	
	public function getIconUrl( ) {
		return Mage::getBaseUrl( Mage_Core_Model_Store::URL_TYPE_MEDIA)."/".$this->getIcon();
	}
	
	public function getFileUrl(){
		return Mage::getBaseUrl( Mage_Core_Model_Store::URL_TYPE_MEDIA)."/".$this->getFile();
	}
}