<?php

class Bumble_Filter_Block_Left extends Mage_Catalog_Block_Product_List 
{
	public function __construct()
	{
		parent::__construct();
		$this->setTemplate('bumble/filter/left.phtml');
	}
}