<?php

class Bumble_Filter_Block_System_Config_Form_Field_Devnames  extends Mage_Adminhtml_Block_Abstract implements Varien_Data_Form_Element_Renderer_Interface
{
	public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $useContainerId = $element->getData('use_container_id');
        return  sprintf('<tr class="system-fieldset-sub-head" id="row_%s"><td colspan="5" class="bumble-description">
					   <h3>'.$this->__("Developers :").'</h3>
					   <div>
					   <h4>'.$this->__( "Lanzillo Ferdinando" ) .'</h4>
					   </td>
					   <td colspan="5" class="bumble-description">
					   <h4><a href="mailto:flanzillo05@gmail.com">'.$this->__( "E-mail : flanzillo05@gmail.com" ) .'</a></h4>
					   </div>
					   
					   </td></tr>
					   ', $element->getHtmlId(), $element->getHtmlId(), $element->getLabel()
        );
    }
}
?>