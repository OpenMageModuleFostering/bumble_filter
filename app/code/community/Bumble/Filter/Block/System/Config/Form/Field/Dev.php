<?php

class Bumble_Filter_Block_System_Config_Form_Field_Dev  extends Mage_Adminhtml_Block_Abstract implements Varien_Data_Form_Element_Renderer_Interface
{
	public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $useContainerId = $element->getData('use_container_id');
        return  sprintf('<tr class="system-fieldset-sub-head" id="row_%s"><td colspan="5" class="bumble-description">
					   <h3>'.$this->__("Developers :").'</h3>
					   
					   <h4 style="float:left;">'.$this->__( "Lanzillo Ferdinando" ) .'</h4>
					   <h4 style="float:right;"><a href="mailto:flanzillo05@gmail.com">'.$this->__( "E-mail : flanzillo05@gmail.com" ) .'</a></h4>
					   
					   
					   </td></tr>
					   ', $element->getHtmlId(), $element->getHtmlId(), $element->getLabel()
        );
    }
}
?>