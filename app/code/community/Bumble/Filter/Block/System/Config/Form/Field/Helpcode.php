<?php

class Bumble_Filter_Block_System_Config_Form_Field_Helpcode  extends Mage_Adminhtml_Block_Abstract implements Varien_Data_Form_Element_Renderer_Interface
{
	public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $useContainerId = $element->getData('use_container_id');
        return  sprintf('<tr class="system-fieldset-sub-head" id="row_%s"><td colspan="5" class="bumble-description">
					   <h3>	 Bumble Filter </h3>
					   <p>'.$this->__( "If you need to insert Carousel like a block, you can paste the follow code in CMS page :" ) .'</p>
							
							<code><strong>{{block type="core/template" name="bumble.carousel" template="bumble/filter/block/carousel.phtml"}}</strong></code>
					   </td></tr>', $element->getHtmlId(), $element->getHtmlId(), $element->getLabel()
        );
    }
}
?>