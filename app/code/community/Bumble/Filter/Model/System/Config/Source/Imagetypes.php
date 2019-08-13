<?php

class Bumble_Filter_Model_System_Config_Source_Imagetypes
{
    public function toOptionArray()
    {
        return array(
        	array('value'=>'l', 'label'=>Mage::helper('bumble_filter')->__('Large')." (".Mage::getStoreConfig('bumble_filter/general_setting/large_imagesize') .")" ),
            array('value'=>'m', 'label'=>Mage::helper('bumble_filter')->__('Medium')." (".Mage::getStoreConfig('bumble_filter/general_setting/medium_imagesize') .")"),
            array('value'=>'s', 'label'=>Mage::helper('bumble_filter')->__('Small')." (".Mage::getStoreConfig('bumble_filter/general_setting/small_imagesize') .")"),

        );
    }    
}
