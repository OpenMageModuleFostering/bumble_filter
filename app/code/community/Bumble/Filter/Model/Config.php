<?php
/***********************Bumble Filter**************************************/
class Bumble_Filter_Model_Config extends Mage_Catalog_Model_Product_Media_Config {

    public function getBaseMediaPath() {
        return Mage::getBaseDir('media') .DS. 'filter';
    }

    public function getBaseMediaUrl() {
        return Mage::getBaseUrl('media') . 'filter';
    }

    public function getBaseTmpMediaPath() {
        return Mage::getBaseDir('media') .DS. 'tmp' .DS. 'filter';
    }

    public function getBaseTmpMediaUrl() {
        return Mage::getBaseUrl('media') . 'tmp/filter';
    }

}