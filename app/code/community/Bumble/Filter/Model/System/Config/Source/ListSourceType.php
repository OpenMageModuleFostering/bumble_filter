<?php

class Bumble_Filter_Model_System_Config_Source_ListSourceType
{
    public function toOptionArray()
    {
        return array(
        	array('value'=>'latest', 'label'=>Mage::helper('bumble_filter')->__('Latest Filters') ),
            array('value'=>'hit', 'label'=>Mage::helper('bumble_filter')->__('Most Read') ),

        );
    }    
}
