<?php

class Bumble_Filter_Model_System_Config_Source_ListFilter extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
    public function getAllOptions()
    { 
		
        if (!$this->_options) {
			$this->_options  = array( array("value"=>"0", "label"=>"--- None ---") );
            $collection = Mage::getModel( "bumble_filter/filter" )->getCollection();
			
			foreach( $collection as $filter ){
				$this->_options[] = array("value"=>$filter->getId(), "label"=>$filter->getTitle() ); 
			}			
        }
        return $this->_options;
    }
}