<?php
 
class Bumble_Filter_Model_Catalog_Layer extends Mage_Catalog_Model_Layer
{
 
    public function prepareProductCollection($collection)
    {
        parent::prepareProductCollection($collection);
 
        $collection
            ->getSelect()
            ->where('price_index.final_price < price_index.price');
 
        return $this;
    }
 
}