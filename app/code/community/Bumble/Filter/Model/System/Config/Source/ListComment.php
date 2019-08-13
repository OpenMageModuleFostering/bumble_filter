<?php 
 /*------------------------------------------------------------------------
  * Bumble Extension Module 
  * @package             SocialConnect
  * @author              Lanzillo Ferdinando, Sabatino Francesco
  * @copyright           Copyright (C) 2016 http://www.armah.it/armah-extension-en/ All Rights Reserved.
  * @license             http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
  * @Technical-Support   http://www.armah.it/armah-extension-en/
-------------------------------------------------------------------------------*/

class Bumble_Filter_Model_System_Config_Source_ListComment
{	
 
    public function toOptionArray()
    {
		

		$output = array();
		$output[] = array("value"=>"" , "label" => Mage::helper('adminhtml')->__("Default Engine"));
		$output[] = array("value"=>"disqus" , "label" => Mage::helper('adminhtml')->__("Disqus"));
		$output[] = array("value"=>"facebook" , "label" => Mage::helper('adminhtml')->__("Facebook"));
		
        return $output ;
    }    
}
