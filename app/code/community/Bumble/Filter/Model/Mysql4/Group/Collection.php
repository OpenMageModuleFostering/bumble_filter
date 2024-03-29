<?php
/******************************************************
 * @package Bumble Megamenu module for Magento 1.4.x.x and Magento 1.7.x.x
 * @version 1.0.0.1
 * @author http://landofcoder.com
 * @copyright	Copyright (C) December 2010 LandOfCoder.com <@emai:landofcoder@gmail.com>.All rights reserved.
 * @license		GNU General Public License version 2
*******************************************************/

class Bumble_Filter_Model_Mysql4_Group_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('bumble_filter/group');
    }

}