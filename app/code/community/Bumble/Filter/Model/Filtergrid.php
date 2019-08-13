<?php

class Bumble_Filter_Model_Filtergrid
{
    //$store = Mage::getStoreConfig('bumble_filter/section_one/module_head_one');
    

    public function toOptionArray() {

        
        $catCollection = Mage::getModel('bumble_filter/group')
                    ->getCollection();
        
        $option = array();
        $option[] = array( 'value' => "", 
                           'label' => 'Select Group Filter');
        foreach($catCollection as $cat) {
            $option[] = array( 'value' => $cat->getId(), 
                               'label' => $cat->getName() );
        }
        return $option;
    }
     
}