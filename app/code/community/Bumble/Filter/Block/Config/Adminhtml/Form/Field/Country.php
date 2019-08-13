<?php
class Bumble_Filter_Block_Config_Adminhtml_Form_Field_Country
    extends Mage_Core_Block_Html_Select
{
    public function _toHtml()
    {
        $option = Mage::getSingleton('bumble_filter/options')
            ->toOptionArray();
        foreach ($option as $options) {
            $this->addOption($options['value'], $options['label']);
        }
 
        return parent::_toHtml();
    }
 
    public function setInputName($value)
    {
        return $this->setName($value);
    }
}