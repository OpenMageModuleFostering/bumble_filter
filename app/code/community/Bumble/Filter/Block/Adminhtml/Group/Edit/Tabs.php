<?php
 /*------------------------------------------------------------------------
  * Bumble Extension Module 
  * @package             SocialConnect
  * @author              Lanzillo Ferdinando, Sabatino Francesco
  * @copyright           Copyright (C) 2016 http://www.armah.it/armah-extension-en/ All Rights Reserved.
  * @license             http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
  * @Technical-Support   http://www.armah.it/armah-extension-en/
----------------------------------------------------------------------------*/
class Bumble_Filter_Block_Adminhtml_Group_Edit_Tabs extends Bumble_Filter_Block_Ourtab
{

    public function __construct()
    {
      $url = $_SERVER["REQUEST_URI"];
      $route =strtok($_SERVER["REQUEST_URI"],'?');
        parent::__construct();
        $this->setId('group_form');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bumble_filter')->__($this->__('Page').' <a onclick= "PrevSave()"><button><</button></a> '.$this->select().' <a onclick= "NextSave()"><button>></button></a>'. $this->__(' of ').$this->maxPage().'<br />'.$this->__('Total ').$this->numItem().$this->__(' Items')));

    }

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
            $n = str_replace("'", "", $option['label']);
            $num[]= $n;

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

      $num =str_replace("'", "", $br->getTitle());
    }

      return $num;
    }


    public function ciao()
    {
      if($_GET['page'] != null)
      {
        $page = $_GET['page'] ;
      }
      if($this->getRequest()->getParam('page') != null && $_GET['page'] == null)
      {
        $page = $this->getRequest()->getParam('page') ;
      }
     
      return $page;
    }

    public function next()
    {
      $page= $this->ciao();
      
      if( $page == 1 || $page == null)
      {
        $page = 1;
      }else
      {
        $page = $page;
      }
      if(count($this->getNum()) > 1 && $page < $this->maxPage() )
      {
         $next = $page+1;
      }else{
        $next = $page;
      }
      return $next;
    }

    public function prev()
    {
      $page= $this->ciao();
      
      if( $page == 1 || $page == null)
      {
        $page = 1;
      }else
      {
        $page = $page;
      }
      if(count($this->getNum()) > 1 && $page > 1 )
      {
         $prev = $page-1;
      }else{
        $prev = $page;
      }

      
      return $prev;
    }

    protected function _beforeToHtml()
    {
       $num = $this->getNum();

      $url = $_SERVER["REQUEST_URI"];
      $route =strtok($_SERVER["REQUEST_URI"],'?');
      $pageshow= 20;
      
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
      
      
      if($use == 1 && $bra_id == null)
      {
      
      //Condizione pagine    
      if($this->ciao())
      {
        #var_dump($this->getNum());
        if($this->ciao() > 2)//page +2 
        {
        
          $numPage = $this->ciao();
          $pageprev = $numPage - 1;
          $pagenext = $numPage + 1;
          
          $pageshowextra= ($pageshow * $numPage) - $pageshow ;

          $num3 = array_slice($num, $pageshowextra);
      
          $j= 1;  
          $incr= 1;
            foreach ($num3 as $key) {
              if($j <= $pageshow){  
              if($key != ""){    
                $this->addTab($key.'_section', array(
                  'label'     => Mage::helper('bumble_filter')->__($key),
                  'title'     => Mage::helper('bumble_filter')->__($key),
                  'content'   => $this->getLayout()->createBlock('bumble_filter/adminhtml_group_edit_tab_form'.$incr)->toHtml(),
                  
                ));
                $j++;
              }}
               $incr++;
            }
          
        }
        elseif($this->ciao() == 2) //page 2
        {

          $num2 = array_slice($num, $pageshow);
      
          $j= 1;  
           $incr= 1;
          foreach ($num2 as $key) 
          {
            if($j <= $pageshow)
            { 
            if($key != ""){    
              $this->addTab($key.'_section', array(
                'label'     => Mage::helper('bumble_filter')->__($key),
                'title'     => Mage::helper('bumble_filter')->__($key),
                'content'   => $this->getLayout()->createBlock('bumble_filter/adminhtml_group_edit_tab_form'.$incr)->toHtml(),
                
              ));
                $j++;
            }}
            $incr++;
          }
          
          
        }
      }

        //Prima pagina
      if($this->ciao() == 1 || $this->ciao() == null)

      { 
        
        $lunghezzaArrayn= count($num);
          if ($lunghezzaArrayn <= 19 )
        { $incr= 1;  foreach ($num as $key) {
          if($key != ""){
            $this->addTab($key.'_section', array(
              'label'     => Mage::helper('bumble_filter')->__($key),
              'title'     => Mage::helper('bumble_filter')->__($key),
              'content'   => $this->getLayout()->createBlock('bumble_filter/adminhtml_group_edit_tab_form'.$incr)->toHtml(),
             
            ));

            $incr++;
      
        }  }  }
        else
        {
      
          $j= 1;
             $incr= 1;
            foreach ($num as $key) {
             
              if($j <= $pageshow){
                if($key != ""){
                  $this->addTab($key.'_section', array(
                    'label'     => Mage::helper('bumble_filter')->__($key),
                    'title'     => Mage::helper('bumble_filter')->__($key),
                    'content'   => $this->getLayout()->createBlock('bumble_filter/adminhtml_group_edit_tab_form'.$incr)->toHtml(),
                    
                  ));
                    $j++;
                    
              }}
              $incr++;
            }
            
        }

      }

    }//primo if is null


    //if is not null
    if($use == 1 && $bra_id != null && $filtertype == 'all')
      {
        $num=$this->getNum();

      $ob = $this->getRequest()->getParam('id');


      $gro = Mage::getModel('bumble_filter/group')->getCollection();
      $tab= array();
      foreach ($gro as $key) {
        $g = Mage::getModel('bumble_filter/group')->load($key->getGroupId());
        if($g->getFilterId() == $ob)
        {
          $replace= $g->getTabs();
          $titleTabs = str_replace("'", "", $replace);
          $tab[] = $titleTabs;
        }
      }
      
      //array finale
      $count1= count($num);
      $count2= count($tab);
      if($count1 != $count2)
      {
        

      foreach ($num as $key ) {
        
        if(!in_array($key, $tab))
        {
          $tab[] = $key;
        }

      }

      }

      //Condizione pagine    
      if($this->ciao())
      {

        if($this->ciao() > 2)//page +2 
        {
        $numPage = $this->ciao();
          #var_dump($num[140]);
          $pageprev = $numPage - 1;
          $pagenext = $numPage + 1;
          $resPage= (20 * $numPage)-20;
          $maxresPage = $resPage + 20;
          $var = $resPage;
          $tab3 = array();
          while($var < $maxresPage){
            $tab3[] = $num[$var];

            $var++;
          }
          #$numPage = $this->ciao();
          #$pageprev = $numPage - 1;
          #$pagenext = $numPage + 1;
            
          $pageshowextra= ($pageshow * $numPage) - $pageshow ;

          #$tab3 = array_slice($tab, $pageshowextra);
      
          $j= 1;  
          $incr= 1;
            foreach ($tab3 as $key) {
              if($j <= $pageshow){ 
              if($key != ""){     
                $this->addTab($key.'_section', array(
                  'label'     => Mage::helper('bumble_filter')->__($key),
                  'title'     => Mage::helper('bumble_filter')->__($key),
                  'content'   => $this->getLayout()->createBlock('bumble_filter/adminhtml_group_edit_tab_form'.$incr)->toHtml(),
                  
                ));
                $j++;
              }}
               $incr++;
            }
          
        }
        elseif($this->ciao() == 2) //page 2
        {
          $numPage = $this->ciao();
          #var_dump($num[140]);
          $pageprev = $numPage - 1;
          $pagenext = $numPage + 1;
          $resPage= (20 * $numPage)-20;
          $maxresPage = $resPage + 20;
          $var = $resPage;
          $tab2 = array();
          while($var < $maxresPage){
            $tab2[] = $num[$var];

            $var++;
          }
          #$tab2 = array_slice($tab, $pageshow);
      
          $j= 1;  
           $incr= 1;
          foreach ($tab2 as $key) 
          {
            if($j <= $pageshow)
            {
            if($key != ""){     
              $this->addTab($key.'_section', array(
                'label'     => Mage::helper('bumble_filter')->__($key),
                'title'     => Mage::helper('bumble_filter')->__($key),
                'content'   => $this->getLayout()->createBlock('bumble_filter/adminhtml_group_edit_tab_form'.$incr)->toHtml(),
                
              ));
                $j++;
            }}
            $incr++;
          }
          
        }
      }

        //Prima pagina
      if($this->ciao() == 1 || $this->ciao() == null)

      { $numPage = $this->ciao();
          #var_dump($num[140]);
          $pageprev = $numPage - 1;
          $pagenext = $numPage + 1;
          $resPage= (20 * $numPage)-20;
          $maxresPage = $resPage + 20;
          $var = $resPage;
          $tab = array();
          while($var < $maxresPage){
            $tab[] = $num[$var];

            $var++;
          }
        
        $lunghezzaArrayn= count($tab);
          if ($lunghezzaArrayn <= 19 )
        { $incr= 1;  foreach ($tab as $key) {
          if($key != ""){
            $this->addTab($key.'_section', array(
              'label'     => Mage::helper('bumble_filter')->__($key),
              'title'     => Mage::helper('bumble_filter')->__($key),
              'content'   => $this->getLayout()->createBlock('bumble_filter/adminhtml_group_edit_tab_form'.$incr)->toHtml(),
             
            ));

            $incr++;
      
        }  }  }
        else
        {
      
          $j= 1;
             $incr= 1;
            foreach ($tab as $key) {
             
              if($j <= $pageshow){
                if($key != ""){
                  $this->addTab($key.'_section', array(
                    'label'     => Mage::helper('bumble_filter')->__($key),
                    'title'     => Mage::helper('bumble_filter')->__($key),
                    'content'   => $this->getLayout()->createBlock('bumble_filter/adminhtml_group_edit_tab_form'.$incr)->toHtml(),
                    
                  ));
                    $j++;
                    
              }}
              $incr++;
            }
            
        }

      }

    }

    if($use == 1 && $bra_id != null && $filtertype != 'all')
    {
      $ob = $this->getRequest()->getParam('id');
      
      $br = Mage::getModel('bumble_filter/filter')->load($ob);

      $title = $br->getManufacturer();

      $attr = Mage::getModel('catalog/resource_eav_attribute')->load(81);

        $label = $attr->getSource()->getOptionText($title);
        $label = str_replace("'", "", $label);
        // 2 array

      $this->addTab($label.'_section', array(
                    'label'     => Mage::helper('bumble_filter')->__($label),
                    'title'     => Mage::helper('bumble_filter')->__($label),
                    'content'   => $this->getLayout()->createBlock('bumble_filter/adminhtml_group_edit_tab_form1')->toHtml(),
                    
                  ));
    }
    if($use == 0)
    {

      $ob = $this->getRequest()->getParam('id');
      
      $br = Mage::getModel('bumble_filter/filter')->load($ob);

      $title = $br->getTitle();

      $this->addTab($title.'_section', array(
                    'label'     => Mage::helper('bumble_filter')->__('Group: %s', $title),
                    'title'     => Mage::helper('bumble_filter')->__('Group: %s', $title),
                    'content'   => $this->getLayout()->createBlock('bumble_filter/adminhtml_group_edit_tab_form1')->toHtml(),
                    
                  ));
    }
        
        return parent::_beforeToHtml();
    
    }
    public function select()
    {
      $num = $num = $this->getNum();
      $sele= count($num);
      $num_split= $sele/20;
      $num_split = round($num_split)+1;
      #location.href=\''.$route.'?page=\' + this.options[this.selectedIndex].value
      $select = '<select name="SelectURL" onchange="editForm.submit($(\'edit_form\').action+\'SelectURL/*/page/\'+ this.options[this.selectedIndex].value);">';
      $check = 1;
      while($check <= $num_split)
      {
        if($this->ciao() == $check){
          $selected = "selected=\"selected\"";
        }else{
          $selected = "";
        }
        $select .= '<option value="'.$check.'" '.$selected.'>'.$check.'</option>';

        $check++;
      }
      $select .= '</select>';

      return $select;
    }
    public function numItem()
    {
      $num = $num = $this->getNum();
      $sele= count($num);
      

      return $sele;
    }
    public function maxPage()
    {
      $num = $this->getNum();
      $sele= count($num);
      $num_split= $sele/20;
      if(is_float($num_split)){ 
        $split = explode('.', $num_split); 
        #var_dump($split[1]);
        if($split[1] <= 5 || $split[1] <= 50){
          $num_split = round($num_split)+1;
        } else 
        {
          $num_split = round($num_split);
        }
      }
      return $num_split;
    }
}
