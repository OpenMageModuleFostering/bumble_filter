<?php
class Bumble_Filter_Model_System_Config_Backend_Filter_checkQty extends Mage_Core_Model_Config_Data
{

    protected function _beforeSave(){
        $value     = $this->getValue();
        	if ((!is_numeric($value) && !empty($value)) || $value < 0) {
        	    throw new Exception(Mage::helper('bumble_filter')->__('Qty of products must be numeric.'));
        	}
        return $this;
    }

}
