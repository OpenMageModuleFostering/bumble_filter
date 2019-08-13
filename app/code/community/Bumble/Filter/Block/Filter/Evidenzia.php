<?php
 /*------------------------------------------------------------------------
  * Bumble Extension Module 
  * @package             SocialConnect
  * @author              Lanzillo Ferdinando, Sabatino Francesco
  * @copyright           Copyright (C) 2016 http://www.armah.it/armah-extension-en/ All Rights Reserved.
  * @license             http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
  * @Technical-Support   http://www.armah.it/armah-extension-en/
----------------------------------------------------------------------------*/

class Bumble_Filter_Block_Filter_Evidenzia extends Mage_Catalog_Block_Product_List {
	
    var $_show = true;
    /**
     * Contructor
     */
    public function __construct($attributes = array())
    {
        $this->_show = $this->getGeneralConfig("show");
        
        if(!$this->_show) return;
        parent::__construct( $attributes );
    }

	public function getGeneralConfig( $val ){ 
		return Mage::getStoreConfig( "bumble_filter/general_setting/".$val );
	}
	
	public function getConfig( $val ){ 
		return Mage::getStoreConfig( "bumble_filter/module_setting/".$val );
	}
	

    protected function _getProductCollection()    {
        $filter = Mage::registry('current_filter');
        
       
        $evidenzia = $filter->getEvidenzia();
        
        

        if (is_null($this->_productCollection) && $evidenzia=='1') {


             $collection = Mage::getModel('catalog/product')->getCollection()
                ->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
                ->addAttributeToFilter('evidenzia',
                                    array('eq'=>'Yes')
                    )
                ->addStoreFilter(Mage::app()->getStore()->getId())
                
                ->addUrlRewrite();
                
                    
                $this->_productCollection = $collection;
                
                
            Mage::getSingleton('catalog/product_status')->addSaleableFilterToCollection($this->_productCollection);
            Mage::getSingleton('catalog/product_visibility')->addVisibleInSiteFilterToCollection($this->_productCollection);

        }

        return $this->_productCollection;
    }

	public function getLoadedProductCollection() {
		return $this->_getProductCollection();
	}

}

?>
	


