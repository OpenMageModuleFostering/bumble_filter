<?php
class Bumble_Filter_Model_Options
{
  
  public function toOptionArray()
  {
    $products = Mage::getModel('catalog/product')
                    ->getCollection();                  
        $option = array();
        
        $option[] = array( 'value' => "", 
                           'label' => 'Select Extra Attribute');
        $j=0;
        $value=array();
        foreach($products as $product) 
        {
            $prod= Mage::getSingleton('catalog/product')->load($product->getId());
            $attributes = $prod->getAttributes();
            
            foreach ($attributes as $attribute) 
            {
                if ($attribute->getIsVisible() && $attribute->getAttributeCode() != "manufacturer" && $attribute->getFrontend()->getValue($prod)!="No" )
                {
                    $value[$j]= $attribute->getAttributeCode();
                    $j++;
                }
            }
        }
        
        $option[] = array( 'value' => 'price', 
                            'label' => 'Price' );
        $result=array_unique($value);
        $filtro=array_filter($result);
        
        foreach ($filtro as $key) 
        {
                $keys = ucwords($key);

                $option[] = array( 'value' => $key, 
                               'label' => $keys );
        }
         return $option;
  }
}