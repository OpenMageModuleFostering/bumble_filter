<?php
 /*------------------------------------------------------------------------
  * Bumble Extension Module 
  * @package             SocialConnect
  * @author              Lanzillo Ferdinando, Sabatino Francesco
  * @copyright           Copyright (C) 2016 http://www.armah.it/armah-extension-en/ All Rights Reserved.
  * @license             http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
  * @Technical-Support   http://www.armah.it/armah-extension-en/
---------------------------------------------------------------------------*/

class Bumble_Filter_Block_Adminhtml_Group_Edit_Tab_Form1 extends Mage_Adminhtml_Block_Widget_Form 
{

     public function getNum()
    {
      $ob = $this->getRequest()->getParam('id');
      
      $br = Mage::getModel('bumble_filter/filter')->load($ob);
      $filtertype= $br->getManufacturer();
      $use= $br->getUsemanufacturer();
      
      $resource = Mage::getSingleton('core/resource');
        $readConnection = $resource->getConnection('core_read');

        $query = "SELECT `group_id` FROM bumble_filter_group WHERE `filter_id`= '$ob'";


        $braresults = $readConnection->fetchAll($query);
        $bra_id =  $braresults[0]['group_id'];

      $gro = Mage::getModel('bumble_filter/group')->load($bra_id);


      if($use == 1)
    {
      if($filtertype == 'all')
        {
          $num = array();
          $attribute = Mage::getModel('eav/config')->getAttribute('catalog_product', 'manufacturer');

          foreach ( $attribute->getSource()->getAllOptions(true, true) as $option)
          {
          if($option['label'] != "")
          {  
            $num[]= $option['label'];
          }
          }
         
      }
      else
      {

        
        $attr = Mage::getModel('catalog/resource_eav_attribute')->load(81);

        $label = $attr->getSource()->getOptionText($filtertype);
        $label = str_replace("'", "", $label);
        $num=array();

        $num[] = $label;// 2 array
      }
    }else{
      $ob = $this->getRequest()->getParam('id');
      
      $br = Mage::getModel('bumble_filter/filter')->load($ob);

      $num = $br->getTitle();
    }

      return $num;
    }

    public function ciao()
    {
      
        $page = $this->getRequest()->getParam('page') ;
      
     
      return $page;
    }

    protected function _prepareForm()
    {
      $page= $this->ciao();
      
      if($page == null){
        $page=1;
      }else{
        $page=$page;
      }
      $num = $this->getNum();
      #var_dump($page);
      $tabs= $this->getRequest()->getParam('id');
        
        $resource = Mage::getSingleton('core/resource');
        $readConnection = $resource->getConnection('core_read');

        $query = "SELECT `group_id` FROM   bumble_filter_group WHERE `filter_id`= '$tabs' AND `page`= '$page'";

        $results = $readConnection->fetchAll($query);
      
          /* get the results */
        
        if($page > 1)
        {
          $resPage= (20 * $page)-20;
        }
        else{
          $resPage = 0;
        }         

         $var = 0+$resPage;
         
          /* get the results */
         $group_id =  $results[0]['group_id'];

         


        
        
      $url = $_SERVER["REQUEST_URI"];
        $_model = Mage::registry('group_data');
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

        $fieldset = $form->addFieldset('group_form', array('legend'=>Mage::helper('bumble_filter')->__('General Information ')));


        $fieldset->addField('title','text',array(
          'label'=> Mage::helper('bumble_filter')->__('Title'),
          'name'=> 'title1',
          'placeholder' => $num[$var],
          'after_element_html' => '<br /><small>'.$this->__('By Default the title will be the same of Label') .'</small>',

          ));
        
        $fieldset->addField('file','image', array(
            'label'=>Mage::helper('bumble_filter')->__('Image'),
            'name'=> 'file1',
            ));
		
        $config->setData(Mage::helper('bumble_filter')->recursiveReplace(
                    '/bumble_filter/',
                    '/'.(string)Mage::app()->getConfig()->getNode('admin/routers/adminhtml/args/frontName').'/',
                    $config->getData()
                )
            );
        $fieldset->addField('identifier', 'text', array(
            'label'     => Mage::helper('bumble_filter')->__('Identifier'),
            'name'      => 'identifier1',
            'id'       =>  'identifier',
            //'value'     => $_model->getLabel()
        ));
        
        $fieldset->addField('description', 'editor', array(
            'label'     => Mage::helper('bumble_filter')->__('Description'),
            'name'      => 'description1',
            'style'     => 'width:600px;height:300px;',
            'wysiwyg'   => true,
            'config'   => $config
        ));

		$fieldset->addField('status', 'select', array(
            'label'     => Mage::helper('bumble_filter')->__('Is Active'),
            'name'      => 'status1',
            'values'    => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray(),
            //'value'     => $_model->getStatus(),
        ));
    $fieldset->addField('page', 'select', array(
            'name'      => 'page',
            'values'    => array(array('value' => $page,'label' => $page)),
            'style'     => 'display:none;',
            
        ));
    
    



   

        if ( Mage::getSingleton('adminhtml/session')->getGroupData() )
          {
              $form->setValues(Mage::getSingleton('adminhtml/session')->getGroupData());
              Mage::getSingleton('adminhtml/session')->getFilterData(null);
          } elseif ( Mage::registry('group_data') ) {

        $newReg  = Mage::getModel('bumble_filter/group')->load($group_id);


              $form->setValues($newReg->getData());
          }
        
        return parent::_prepareForm();
    }
	
}
