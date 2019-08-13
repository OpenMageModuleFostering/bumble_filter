<?php

class Bumble_Filter_Block_System_Config_Form_Field_Responsive480  extends Mage_Adminhtml_Block_Abstract implements Varien_Data_Form_Element_Renderer_Interface
{
	public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $useContainerId = $element->getData('use_container_id');
        return  sprintf('<tr class="system-fieldset-sub-head" id="row_%s"><td colspan="5" class="bumble-description">
					   					
							<h3 style="font-size:1.1em;border-bottom:1px solid grey;"><strong>Responsive 480</strong></h3>
					   </td></tr>', $element->getHtmlId(), $element->getHtmlId(), $element->getLabel()
        );
    }
}
?>