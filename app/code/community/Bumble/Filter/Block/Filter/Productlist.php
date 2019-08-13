<?php
 /*------------------------------------------------------------------------
  * Bumble Extension Module 
  * @package             SocialConnect
  * @author              Lanzillo Ferdinando, Sabatino Francesco
  * @copyright           Copyright (C) 2016 http://www.armah.it/armah-extension-en/ All Rights Reserved.
  * @license             http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
  * @Technical-Support   http://www.armah.it/armah-extension-en/
--------------------------------------------------------------------------*/

class Bumble_Filter_Block_Filter_Productlist extends Mage_Catalog_Block_Product_List {
	
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
	
    protected function _prepareLayout()
    {
       # Mage::setIsDeveloperMode(true);
         $breadcrumbs = $this->getLayout()->getBlock('breadcrumbs');
        if ($breadcrumbs) {
            $title =  $this->getFilter()->getTitle();
            $group= $this->getFilter()->getFilterId();
            $filterGroup = Mage::getModel('bumble_filter/filter')->load($group);
            $TitleGroup= $filterGroup->getTitle();
            $identifierGroup= $filterGroup->getIdentifier();

            $breadcrumbs->addCrumb('home', array( 'label' => $this->__('Home'),  'title' => $this->__('Go to Home Page'), 'link'  => Mage::getBaseUrl() ))->addCrumb('filters', array(
                'label' => $this->__($TitleGroup),
                'title' => $this->__($TitleGroup),
				'link'  => Mage::getUrl( $identifierGroup )
            ))
			->addCrumb('item', array(
                'label' => $title,
                'title' => $title,
            ));
        }
		if ($this->getFilter()->getPagetitle()) {
			$title = $this->getFilter()->getPagetitle();
		} else {
	        $title = $this->__("Filter - %s", $this->getFilter()->getTitle());
		}
        $this->getLayout()->getBlock('head')->setTitle($title);

		if ($this->getFilter()->getMetaKeywords()) {
			$keywords = $this->getFilter()->getMetaKeywords();
			$this->getLayout()->getBlock('head')->setKeywords($keywords);
		}

		if ($this->getFilter()->getMetaDescription()) {
			$description = $this->getFilter()->getMetaDescription();
			$this->getLayout()->getBlock('head')->setDescription($description);
		}
        return parent::_prepareLayout();
    }


    public function getHeaderText()
    {
        if( $this->getFilter()->getTitle() ) {
            return Mage::helper('filters')->__("Filter - '%s'", $this->htmlEscape($this->getFilter()->getTitle()));
        } else {
            return false;
        }
    }
    public function getFilter() {
        return Mage::registry('current_group');

    }

    public function getAllManu()
    {
        $product = Mage::getModel('catalog/product');
        $attributes = Mage::getResourceModel('eav/entity_attribute_collection')
        ->setEntityTypeFilter($product->getResource()->getTypeId())
                ->addFieldToFilter('attribute_code', 'manufacturer'); //can be changed to any attribute
        $attribute = $attributes->getFirstItem()->setEntity($product->getResource());
        $manufacturers = $attribute->getSource()->getAllOptions(false);
        
        return $manufacturers;
    }
    


    protected function _getProductCollection()    {
       #echo ini_set('max_execution_time', 600);
        $filter = Mage::registry('current_group');
        $filter_id= $filter->getFilterId();
        $getLabel= $filter->getTabs();

        $group_filter= Mage::getModel('bumble_filter/filter')->load($filter_id);
        $bra= $group_filter->getManufacturer();
        $use = $group_filter->getUsemanufacturer();
        if($bra != 'all')
        {
            $filtertype= $group_filter->getManufacturer();   
        }
        else
        {
            
                foreach ($this->getAllManu() as $manufacturer){
                    if($getLabel == $manufacturer['label'])
                    {
                   $filtertype= $manufacturer['value'] ;
                    }
                }
                

        }
        $pricelt= $_GET['pricelt'];
        $pricegt= $_GET['pricegt'];
        $filtercat = $group_filter->getCategory();
        
        $extraatt = $group_filter->getExtraatt();
        
        if($group_filter->getSubatt() != null){
        $subatt= $group_filter->getSubatt();
        }else{
            
            $product = Mage::getModel('catalog/product');
        $attributes = Mage::getResourceModel('eav/entity_attribute_collection')
        ->setEntityTypeFilter($product->getResource()->getTypeId())
                ->addFieldToFilter('attribute_code', $extraatt); //can be changed to any attribute
        $attribute = $attributes->getFirstItem()->setEntity($product->getResource());
        $manufacturers = $attribute->getSource()->getAllOptions(false);
            $subatt= array();
            foreach ($manufacturers as $manufacturer){
                    
                   $subatt[]= $manufacturer['value'] ;
                    
                }
            
        }
        //var_dump($subatt);
        
        
        if($_GET['category'] != "" || $filtercat != "")
        {
            if($_GET['category'] != "")
            {
                $cat = $_GET['category'];
            }else{
                $cat = $filtercat;
            }



            
            if (is_null($this->_productCollection)) {
            $_category = Mage::getModel('catalog/category')->load($cat);
            //var_dump($cat);
			$collection = Mage::getModel('catalog/product')->getCollection()
                ->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
               ->addCategoryFilter($_category)
                ->addStoreFilter(Mage::app()->getStore()->getId())
                ->addUrlRewrite();

            if($extraatt != null)
            { 
                $collection->addAttributeToFilter($extraatt, array('in'=>array($subatt)));
            }

            if($pricelt != null && $pricegt == null )
            {
                $collection->addAttributeToFilter('price', array('lt' => $pricelt));
            }
            if($pricegt != null && $pricelt == null)
            {
                $collection->addAttributeToFilter('price', array('gt' => $pricegt));
            }
            if($pricegt != null && $pricelt != null)
            {
                $collection->addAttributeToFilter('price', array('gt' => $pricegt));
                $collection->addAttributeToFilter('price', array('lt' => $pricelt));
            }
            

            if($bra != "" && $use == 1)
            {
                $collection->addFieldToFilter(array(
                                    array('attribute'=>'manufacturer','eq'=>$filtertype),
                                    
                    ));
            }
            
            $this->_productCollection = $collection;
            Mage::getSingleton('catalog/product_status')->addSaleableFilterToCollection($this->_productCollection);
            Mage::getSingleton('catalog/product_visibility')->addVisibleInSiteFilterToCollection($this->_productCollection);
            }
        }else{
            if (is_null($this->_productCollection)) {
            
            $collection = Mage::getModel('catalog/product')->getCollection()
                ->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
               ->addStoreFilter(Mage::app()->getStore()->getId())
               ->addUrlRewrite();
            if($extraatt != null)
            { 
                $collection->addAttributeToFilter($extraatt, array('in'=>array($subatt)));
            }
            if($pricelt != null && $pricegt == null )
            {
                $collection->addAttributeToFilter('price', array('lt' => $pricelt));
            }
            if($pricegt != null && $pricelt == null)
            {
                $collection->addAttributeToFilter('price', array('gt' => $pricegt));
            }
            if($pricegt != null && $pricelt != null)
            {
                $collection->addAttributeToFilter('price', array('gt' => $pricegt));
                $collection->addAttributeToFilter('price', array('lt' => $pricelt));
            }

            if($bra != "" && $use == 1)
            {
                $collection->addFieldToFilter(array(
                                    array('attribute'=>'manufacturer','eq'=>$filtertype),
                                    
                    ));
            }
          
            $this->_productCollection = $collection;
            Mage::getSingleton('catalog/product_status')->addSaleableFilterToCollection($this->_productCollection);
            Mage::getSingleton('catalog/product_visibility')->addVisibleInSiteFilterToCollection($this->_productCollection);
            }
        }


        

        return $this->_productCollection;
    }

	public function getLoadedProductCollection() {

		return $this->_getProductCollection();
	}
   

}
?>