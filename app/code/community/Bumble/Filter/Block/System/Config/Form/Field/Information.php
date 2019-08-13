<?php

class Bumble_Filter_Block_System_Config_Form_Field_Information  extends Mage_Adminhtml_Block_Abstract implements Varien_Data_Form_Element_Renderer_Interface
{
	public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $useContainerId = $element->getData('use_container_id');
        return  sprintf('<tr class="system-fieldset-sub-head" id="row_%s"><td colspan="5" class="bumble-description">
        	<br />
					   
							<br />
                            <h3>'.$this->__("Developed:").'</h3>
							<div style="font-size:11px">'.$this->__( "Copyright &copy; 2016:" ) .' <i style="font-size:13px"><b>'.$this->__( "BumbleBit S.r.l." ) .'</b></i></div>
                            <div style="font-size:11px">'.$this->__( "For more Info on ours module o for a support have a look on" ) .' <i><a href="http://'.$this->__("www.armah.it/armah-extension-en/").'" target="_blank">www.armah.it/armah-extension/</a></i></div>
					   </td></tr>', $element->getHtmlId(), $element->getHtmlId(), $element->getLabel()
        );
    }
}
?>