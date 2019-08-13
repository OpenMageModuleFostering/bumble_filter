<?php

class Bumble_Filter_Model_System_Config_Source_ListGroup
{

    public function toOptionArray() {
        $Collection = Mage::getModel('bumble_filter/filter')->getCollection();
        $arr = array(array("value" => "0", "label" => Mage::helper("bumble_filter")->__("-- All Groups --")));
        foreach($Collection as $cat) {
            $arr[] = array("value" => $cat->getFilterId(), "label" =>$cat->getTitle());
        }
        return $arr;
    }
}