<?php

class Bumble_Filter_Block_System_Config_Form_Field_Devnames2  extends Mage_Adminhtml_Block_Abstract implements Varien_Data_Form_Element_Renderer_Interface
{
	public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $useContainerId = $element->getData('use_container_id');
        return  sprintf('<tr class="system-fieldset-sub-head" id="row_%s"><td colspan="5" class="bumble-description">
					
					   <h4 style="float:left;">'.$this->__( "Sabatino Francesco" ) .'</h4> 
					   <h4 style="float:right;"><a href="mailto:francesco-sabatino@hotmail.it">'.$this->__( "E-mail : francesco-sabatino@hotmail.it" ) .'</a></h4>
					   
					   </td></tr>
					   ', $element->getHtmlId(), $element->getHtmlId(), $element->getLabel()
        );
    }
}
?>