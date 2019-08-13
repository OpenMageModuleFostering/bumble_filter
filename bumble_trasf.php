<?php
            
        


$a_json = array();
$a_json_row= array();


require_once 'app/Mage.php';
Mage::app();

$products = Mage::getModel('catalog/product')->getCollection();                  


$a_json_row[] = array( 'value' => "", 
                    'label' => $this->__('Select Extra Attribute'));

$res = $_GET['p'] ;

        
$j = 0;
$k = array();
        
         foreach($products as $product) 
        {
            $prod= Mage::getSingleton('catalog/product')->load($product->getId());
            $attribute = $prod->getResource()->getAttribute($res);
            $options= $attribute->getSource()->getAllOptions(true, true);

            foreach ($options as $option) 
            {
          
             if ($option['value'] == $prod->getData($res) && $prod->getData($res) != "" && !in_array($option['value'],$k))
             {
            
              $value = $option['value'];
              $label = $option['label'];


              $k[$j]= $option['value'];
              $j++; 

              $a_json_row[] = array( 'value' => $value, 
                               'label' => $label );
              
              }
            }
        }

        
        array_push($a_json, $a_json_row);
        echo json_encode($a_json);
        flush();