<?php

class Bumble_Filter_Block_Carousel extends Mage_Core_Block_Template
{
	public function __construct()
	{
		parent::__construct();
		$this->setTemplate('bumble/filter/carousel.phtml');

	}
}