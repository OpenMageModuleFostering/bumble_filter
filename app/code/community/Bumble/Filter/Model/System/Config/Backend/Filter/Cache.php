<?php
class Bumble_Filter_Model_System_Config_Backend_Filter_Cache extends Mage_Core_Model_Config_Data {
    protected function _afterSave() {
	    Mage::app()->cleanCache( array(
		        Mage_Core_Model_Store::CACHE_TAG,
		        Mage_Cms_Model_Block::CACHE_TAG,
		        Bumble_Filter_Model_Filter::CACHE_BLOCK_SCROLL_TAG
		    ) );
	    
	    Mage::app()->cleanCache( array(
	        Mage_Core_Model_Store::CACHE_TAG,
	        Mage_Cms_Model_Block::CACHE_TAG,
	        Bumble_Filter_Model_Filter::CACHE_BLOCK_LIST_TAG
	    ) );

	    Mage::app()->cleanCache( array(
		        Mage_Core_Model_Store::CACHE_TAG,
		        Mage_Cms_Model_Block::CACHE_TAG,
		        Bumble_Filter_Model_Filter::CACHE_WIDGET_SCROLL_TAG
		    ) );
	    
	    Mage::app()->cleanCache( array(
	        Mage_Core_Model_Store::CACHE_TAG,
	        Mage_Cms_Model_Block::CACHE_TAG,
	        Bumble_Filter_Model_Filter::CACHE_WIDGET_LIST_TAG
	    ) );
	}
}