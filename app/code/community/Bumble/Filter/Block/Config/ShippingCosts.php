<?php
class Bumble_Filter_Block_Config_ShippingCosts 
    extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{
    public function _prepareToRender()
    {
        $this->addColumn('from_price', array(
            'label' => Mage::helper('bumble_filter')->__('Gruppo'),
            'renderer' => $this->_getRenderer(),
            'style' => 'width:200px',
            
        ));
        
 
        $this->_addAfter = false;
        $this->_addButtonLabel = Mage::helper('bumble_filter')->__('Add');
    }

    protected function  _getRenderer() 
    {
        if (!$this->_itemRenderer) {
            $this->_itemRenderer = $this->getLayout()->createBlock(
                'bumble_filter/config_adminhtml_form_field_country', '',
                array('is_render_to_js_template' => true)
            );
        }
        return $this->_itemRenderer;
    }
 
    protected function _prepareArrayRow(Varien_Object $row)
    {
        $row->setData(
            'option_extra_attr_' . $this->_getRenderer()
                ->calcOptionHash($row->getData('from_price')),
            'selected="selected"'
        );
    }

}