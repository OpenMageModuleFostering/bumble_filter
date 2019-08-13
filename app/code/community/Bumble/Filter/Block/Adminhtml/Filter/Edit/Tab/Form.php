<?php
/***********************Bumble Filter**************************************/


class Bumble_Filter_Block_Adminhtml_Filter_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {

        $urlpa= $_SERVER['HTTP_HOST'];
        $_model = Mage::registry('filter_data');
        $form = new Varien_Data_Form();
        $this->setForm($form);

        $config = Mage::getSingleton('cms/wysiwyg_config')->getConfig(
                array(
                        'add_widgets' => false,
                        'add_variables' => false,
                        'add_images' => true,
                        'encode_directibumble'             => false,
                        'directibumble_url'                => Mage::getSingleton('adminhtml/url')->getUrl('*/cms_wysiwyg/directive'),
                        'files_browser_window_url' => Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg_images/index'),
                        'files_browser_window_width' => (int) Mage::getConfig()->getNode('adminhtml/cms/browser/window_width'),
                        'files_browser_window_height'=> (int) Mage::getConfig()->getNode('adminhtml/cms/browser/window_height')
                    )
                );

        $fieldset = $form->addFieldset('filter_form', array('legend'=>Mage::helper('bumble_filter')->__('General Information')));
        

                ?>
       
        <script>
            
               
                function getComune(attri){

                 if(document.getElementById("extraatt").value == "") document.getElementById("subatt").style.display = "none";
                 else document.getElementById("subatt").style.display = "block";
                 if(document.getElementById("extraatt").value == "" && document.getElementById("usemanufacturer").value == "0" ) document.getElementById("category").style.display = "none";
                 else document.getElementById("category").style.display = "block";


            
                
                var http = new XMLHttpRequest();
                var url = "http://<?php echo $urlpa; ?>/bumble_trasf.php";
                var params = "p="+ attri;
                
                http.open("GET", url+"?"+params, true);
                http.onreadystatechange = function() 
                {
                    
                    if(http.readyState === 4 && http.status === 200) 
                    {
                        var response = http.responseText;
                        var json = JSON.parse(response);
                        
                            
                       var x = document.getElementById("subatt");
                       while(x.hasChildNodes())
                       {
                        x.removeChild(x.firstChild);
                        }
                      for(var i=0; i<json.length;i+=1)
                      {
                         for(var j=0; j<json[i].length;j+=1)
                         {

                                 var node = document.createElement("option");
                                 node.setAttribute("value",json[i][j].value);
                                 var textnode = document.createTextNode(json[i][j].label);
                                 node.appendChild(textnode);
                                 document.getElementById("subatt").appendChild(node);
                                 
                          }
                             
                       }
                        
                    }
                };
                http.send(null);
        }

        function getManu(attri){

                 if(document.getElementById("usemanufacturer").value == "0") document.getElementById("manufacturer").style.display = "none";
                 else document.getElementById("manufacturer").style.display = "block";
                 if(document.getElementById("usemanufacturer").value == "0" && document.getElementById("extraatt").value == "") document.getElementById("category").style.display = "none";
                 else document.getElementById("category").style.display = "block";
        }

        </script>    
  

        <?php
        $url = $_SERVER["REQUEST_URI"];
        $par='add';
        
        if(strpos($url,$par)==false){//mi trovo in edit
        $id=$this->getRequest()->getParam('id');
        $value=Mage::getModel('bumble_filter/filter')->load($id);
        $val=$value->getUsemanufacturer();
        $val2=$value->getExtraatt();
        $val3=$value->getSubatt();

           $fieldset->addField('title', 'text', array(
            'label'     => Mage::helper('bumble_filter')->__('Title'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'title',
        ));
        
         $fieldset->addField('identifier', 'text', array(
            'label'     => Mage::helper('bumble_filter')->__('Identifier'),
            'name'      => 'identifier',
            'id'       =>  'identifier',
            //'value'     => $_model->getLabel()
        ));

         $fieldset->addField('usemanufacturer' ,'select',array(
            'name'      => 'usemanufacturer',   
            'label' => $this->__('Manufacturer'),
            'values' => array(
                    array('value' => '0','label' => 'NO'),
                    array('value' => '1','label' => $this->__('YES'))
                ),
            'onchange' => "javascript:getManu(this.value)",
        ));
         if($val=='1'){

        $fieldset->addField('manufacturer' ,'select',array(
            'name'      => 'manufacturer',
            'values' => $this->getAttributeoption(),
            'style' => 'display:block;'
        ));
        }
        else{
            $fieldset->addField('manufacturer' ,'select',array(
            'name'      => 'manufacturer',
            'values' => $this->getAttributeoption(),
            'style' => 'display:none;'
        ));
        }

        
        $fieldset->addField('extraatt' ,'select',array(
            'name'      => 'extraatt',  
            'id' =>  'extraatt',
            'label' => Mage::helper('bumble_filter')->__('Exta Attribute'),            
            'values' => $this->getAttributextra(),
            'onchange' => "javascript:getComune(this.value)",
         ));


        if($val2!=''){
            if($val3=='')
            {
            $vettore=array();
            $vettore[]=array('value'=>'','label'=> $this->__('Select Extra Attribute'));
            $subatt=$this->getAttri();
            foreach($subatt as $s){
               $vettore[]=array('value'=>$s['value'],'label'=>$s['label']);
            }
        $fieldset->addField('subatt' ,'select',array(
            'name'      => 'subatt',   
            'class'  => 'subextra',
            'values' => $vettore,
            'style' => 'display:block;',
        ));
           }
           else{
            $fieldset->addField('subatt' ,'select',array(
            'name'      => 'subatt',   
            'class'  => 'subextra',
            'values' => $this->getAttri(),
            'style' => 'display:block;',
        ));
           }
    }
    else{
        $fieldset->addField('subatt' ,'select',array(
            'name'      => 'subatt',   
            'class'  => 'subextra',
            'values' => '',
            'style' => 'display:none;',
        ));
    }



    if($val=='1'|| $val2!=null){
        $fieldset->addField('category' ,'select',array(
            'name'      => 'category',   
            'values' => $this->getCat(),
            'style' => 'display:block;',
        ));  
    }
    
}

else{ //mi trovo in add

        $fieldset->addField('title', 'text', array(
            'label'     => Mage::helper('bumble_filter')->__('Title'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'title',
        ));

        $fieldset->addField('identifier', 'text', array(
            'label'     => Mage::helper('bumble_filter')->__('Identifier'),
            'name'      => 'identifier',
            'id'       =>  'identifier',
            //'value'     => $_model->getLabel()
        ));

        $fieldset->addField('usemanufacturer' ,'select',array(
            'name'      => 'usemanufacturer',   
            'label' => $this->__('Manufacturer'),
            'values' => array(
                    array('value' => '0','label' => 'NO'),
                    array('value' => '1','label' => $this->__('YES'))
                ),
            'onchange' => "javascript:getManu(this.value)",
        ));
       
        
            $fieldset->addField('manufacturer' ,'select',array(
            'name'      => 'manufacturer',
            'values' => $this->getAttributeoption(),
            'style' => 'display:none;'
        ));
        

        $fieldset->addField('extraatt' ,'select',array(
            'name'      => 'extraatt',  
            'id' =>  'extraatt',
            'label' => Mage::helper('bumble_filter')->__('Exta Attribute'),            
            'values' => $this->getAttributextra(),
            'onchange' => "javascript:getComune(this.value)",
        ));

        
        $fieldset->addField('subatt' ,'select',array(
            'name'      => 'subatt',   
            'class'  => 'subextra', 
            'values' => '',
            'style' => 'display:none;',
            
        ));

       $fieldset->addField('category' ,'select',array(
            'name'      => 'category',   
            'values' => $this->getCat(),
            'style' => 'display:none;',
        )); 

   }

    

        
        $config->setData(Mage::helper('bumble_filter')->recursiveReplace(
                    '/bumble_filter/',
                    '/'.(string)Mage::app()->getConfig()->getNode('admin/routers/adminhtml/args/frontName').'/',
                    $config->getData()
                )
            );
        
        $fieldset->addField('is_active', 'select', array(
            'label'     => Mage::helper('bumble_filter')->__('Is Active'),
            'name'      => 'is_active',
            'values'    => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray(),
            //'value'     => $_model->getIsActive()
        ));
        
        if (!Mage::app()->isSingleStoreMode()) {
            $fieldset->addField('store_id', 'multiselect', array(
                'name' => 'stores[]',
                'label' => Mage::helper('bumble_filter')->__('Store View'),
                'title' => Mage::helper('bumble_filter')->__('Store View'),
                'required' => true,
                'values' => Mage::getSingleton('adminhtml/system_store')
                             ->getStoreValuesForForm(false, true),
            ));
        }
        else {
            $fieldset->addField('store_id', 'hidden', array(
                'name' => 'stores[]',
                'value' => Mage::app()->getStore(true)->getId()
            ));
        }
         
        
        if ( Mage::getSingleton('adminhtml/session')->getFilterData() )
          {
              $form->setValues(Mage::getSingleton('adminhtml/session')->getFilterData());
              Mage::getSingleton('adminhtml/session')->getFilterData(null);
          } elseif ( Mage::registry('filter_data') ) {
              $form->setValues(Mage::registry('filter_data')->getData());
          }

        
        return parent::_prepareForm();
    }
    
    public function getProductList()
    {
       $products = Mage::getModel('catalog/product')->getCollection();
       
       return $products; 
    }

    public function getManFilter(){

        $br= array();
        $filterselected = Mage::getModel('bumble_filter/filter')->getCollection();
        
        foreach ($filterselected as $filter) {
           
           $br[] = $filter->getManufacturer();
        }
        return $br;
    }

    public function getAttri(){

        $url = $_SERVER["REQUEST_URI"];
        $par='add';
        $id=$this->getRequest()->getParam('id');
        $value=Mage::getModel('bumble_filter/filter')->load($id);
        $val=$value->getUsemanufacturer();
        $val2=$value->getExtraatt();
        $val3=$value->getSubatt();
        $subatt=array();
        $product = Mage::getModel('catalog/product');
        $attributes = Mage::getResourceModel('eav/entity_attribute_collection')
        ->setEntityTypeFilter($product->getResource()->getTypeId())
                ->addFieldToFilter('attribute_code', $val2); //can be changed to any attribute
        $attribute = $attributes->getFirstItem()->setEntity($product->getResource());
        $options = $attribute->getSource()->getAllOptions(false);
        foreach($options as $option){
            $collection=$product->getCollection();
            $collection->addFieldToFilter(array(
                                    array('attribute'=>$val2,'eq'=>$option['value']),
                                    
                    ));
            if($option['value']==$val3&&$option['value']!=''){
            $subatt[]=array('value'=>$option['value'],'label'=>$option['label']);
         }
        }
        foreach($options as $option){
            $collection=$product->getCollection();
            $collection->addFieldToFilter(array(
                                    array('attribute'=>$val2,'eq'=>$option['value']),
                                    
                    ));
            if((count($collection->getData()) >0) && $option['value']!=$val3) {
            $subatt[]=array('value'=>$option['value'],'label'=>$option['label']);
         }
        }
        return $subatt;

    }

  
    public function getAttributeoption() {
        $option = array();
        $option[] = array( 'value' => "all", 
                           'label' => $this->__('All'));
        $br=$this->getManFilter();
        $product = Mage::getModel('catalog/product');
        $attributes = Mage::getResourceModel('eav/entity_attribute_collection')
        ->setEntityTypeFilter($product->getResource()->getTypeId())
                ->addFieldToFilter('attribute_code', 'manufacturer'); //can be changed to any attribute
        $attribute = $attributes->getFirstItem()->setEntity($product->getResource());
        $manufacturers = $attribute->getSource()->getAllOptions(false);
        if(count($br)==0)
        {
            foreach($manufacturers as $man){
            $collection=$product = Mage::getModel('catalog/product')->getCollection();
            $collection->addFieldToFilter(array(
                                    array('attribute'=>'manufacturer','eq'=>$man['value']),
                                    
                    ));
            if((count($collection->getData()) >0)){
            $option[]= array('value' => $man['value'], 'label'=> $man['label']);
         }
                                            }
       }
       else{
        $url = $_SERVER["REQUEST_URI"];
        $par='add';
        if(strpos($url,$par)==false){//mi trovo in edit
            foreach($manufacturers as $man){
            $collection=$product = Mage::getModel('catalog/product')->getCollection();
            $collection->addFieldToFilter(array(
                                    array('attribute'=>'manufacturer','eq'=>$man['value']),
                                    
                    ));
            if((count($collection->getData()) >0)){
            $option[]= array('value' => $man['value'], 'label'=> $man['label']);
         }
                                            }
        }
        else{//mi trovo in add

         foreach($manufacturers as $man){
            $collection=$product = Mage::getModel('catalog/product')->getCollection();
            $collection->addFieldToFilter(array(
                                    array('attribute'=>'manufacturer','eq'=>$man['value']),
                                    
                    ));
            if((count($collection->getData()) >0)&& !in_array($man['value'],$br)) {
            $option[]= array('value' => $man['value'], 'label'=> $man['label']);
         }
                                            }


        }
       
       }

     return $option;
         

   }

    

    public function getAttributextra() {

     $option = array();
     $option[] = array( 'value' => "", 
                           'label' => $this->__('Select Extra Attribute'));
     $attributes = Mage::getResourceModel('catalog/product_attribute_collection')
     ->getItems();
   
    foreach ($attributes as $attribute){
    if ($attribute->getIsUserDefined()&&$attribute->getAttributeCode()!="manufacturer"&&$attribute->getAttributeCode()!="evidenzia"&&$attribute->usesSource())//&&$attribute->getValue()!='No'))
    {
         $collection = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToSelect($attribute->getAttributeCode())
            ->addAttributeToFilter(
                array
                (
                    array
                    (
                        'attribute' => $attribute->getAttributeCode(), 
                        'notnull' => true
                    ),
                    array
                    (
                        'attribute' => $attribute->getAttributeCode(),
                        'nin' => array('N/A', '', ' ')
                    )
                )
            );
            if((count($collection->getData()) >0))
            {
    $keys = ucwords($attribute->getAttributecode());
    $key = $attribute->getAttributecode();

                $option[] = array( 'value' => $key, 
                               'label' => $keys );
            }
    }
   
      }

      return $option;

    }


   
   

    public function getCat(){

    $option = array();
    $option[] = array( 'value' => "", 
                          'label' => $this->__('Select Category'));
    $categories = Mage::getModel('catalog/category')
        ->getCollection()
        ->addIsActiveFilter();
    foreach($categories as $cat){
    $c=Mage::getModel('catalog/category')->load($cat->getId());
    if($c->getLevel()!=1 && $c->getProductCount()>0){
      $option[] = array( 'value' => $c->getId(), 
                               'label' => $c->getName() );
    }
 }
   return $option;

    }
   
}
