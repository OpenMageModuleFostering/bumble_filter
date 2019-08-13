<?php
/**
 * Bumbletheme
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Bumbletheme EULA that is bundled with
 * this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.bumbletheme.com/LICENSE-1.0.html
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the extension
 * to newer versions in the future. If you wish to customize the extension
 * for your needs please refer to http://www.bumbletheme.com/ for more information
 *
 * @category   Bumble
 * @package    Bumble_Blog
 * @copyright  Copyright (c) 2014 Bumbletheme (http://www.bumbletheme.com/)
 * @license    http://www.bumbletheme.com/LICENSE-1.0.html
 */

/**
 * Bumble Blog Extension
 *
 * @category   Bumble
 * @package    Bumble_Blog
 * @author     Bumbletheme Dev Team <bumbletheme@gmail.com>
 */
class Bumble_Filter_Model_System_Config_Source_Listcolumns
{

  public function toOptionArray()
  {

    $output = array();
    $output[] = array("value"=>"" , "label" => Mage::helper('adminhtml')->__("Auto"));
    $output[] = array("value"=>"1" , "label" => 1);
    $output[] = array("value"=>"2" , "label" => 2);
    $output[] = array("value"=>"3" , "label" => 3);
    $output[] = array("value"=>"4" , "label" => 4);
    $output[] = array("value"=>"5" , "label" => 5);
    $output[] = array("value"=>"6" , "label" => 6);

    return $output ;
  }
}