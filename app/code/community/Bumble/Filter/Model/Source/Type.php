<?php
 /*------------------------------------------------------------------------
  * Bumble Extension Module 
  * @package             SocialConnect
  * @author              Lanzillo Ferdinando, Sabatino Francesco
  * @copyright           Copyright (C) 2016 http://www.armah.it/armah-extension-en/ All Rights Reserved.
  * @license             http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
  * @Technical-Support   http://www.armah.it/armah-extension-en/
---------------------------------------------------------------------------*/
class Bumble_Filter_Model_Config_Source_Type
{
    const IMAGE       = 'image';
    const PRODUCT    = 'product';

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => self::IMAGE, 'label'=>Mage::helper('adminhtml')->__('Image')),
            array('value' => self::PRODUCT, 'label'=>Mage::helper('adminhtml')->__('Product'))
        );
    }

    /**
     * Options getter
     *
     * @return array
     */
    public function toGridOptionArray()
    {
        return array(
            self::IMAGE => Mage::helper('adminhtml')->__('Image'),
            self::PRODUCT => Mage::helper('adminhtml')->__('Product')
        );
    }
}
