<?php
/*------------------------------------------------------------------------
  * Bumble Extension Module 
  * @package             SocialConnect
  * @author              Lanzillo Ferdinando, Sabatino Francesco
  * @copyright           Copyright (C) 2016 http://www.armah.it/armah-extension-en/ All Rights Reserved.
  * @license             http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
  * @Technical-Support   http://www.armah.it/armah-extension-en/
---------------------------------------------------------------------------*/

class Bumble_Filter_Adminhtml_FiltergroupController extends Mage_Adminhtml_Controller_Action {
    protected function _initAction() {
        $this->loadLayout()
                ->_setActiveMenu('bumble_filter/group');

        return $this;
    }

    public function indexAction() {
        $this->_title($this->__('Filters Manager'));
        $this->_initAction();
        $this->_addContent($this->getLayout()->createBlock('bumble_filter/adminhtml_group') );
        $this->renderLayout();
    
    }

    public function editAction(){
        $this->_title($this->__('Edit Record'));
        $id     = $this->getRequest()->getParam('id');
        $id   = $id?$id: 0;
        
          $tabs= $this->getRequest()->getParam('id');
        
        $resource = Mage::getSingleton('core/resource');
        $readConnection = $resource->getConnection('core_read');

        $query = "SELECT `group_id` FROM  bumble_filter_group WHERE `filter_id`= '$tabs'";

        $results = $readConnection->fetchAll($query);
      
          /* get the results */
         $group_id =  $results[0]['group_id'];

        
        $_model  = Mage::getModel('bumble_filter/group')->load($group_id);
        Mage::register('group_data', $_model);
        $this->loadLayout();
        $this->_setActiveMenu('bumble_filter/group');
        $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Group Manager'), Mage::helper('adminhtml')->__('Group Manager'), $this->getUrl('*/*/'));
        $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Add group'), Mage::helper('adminhtml')->__('Add group'));
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

        $this->_addContent($this->getLayout()->createBlock('bumble_filter/adminhtml_group_edit'))
            ->_addLeft($this->getLayout()->createBlock('bumble_filter/adminhtml_group_edit_tabs'));
        

         if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled())  {

            $this->getLayout()->getBlock('head')
                                ->setCanLoadTinyMce(true)
                                ->addItem('js','tiny_mce/tiny_mce.js')
                                ->addItem('js','mage/adminhtml/wysiwyg/tiny_mce/setup.js')
                                ->addJs('mage/adminhtml/browser.js')
                                ->addJs('prototype/window.js')
                                ->addJs('lib/FABridge.js')
                                ->addJs('lib/flex.js')
                                ->addJs('mage/adminhtml/flexuploader.js')
                                ->addItem('js_css','prototype/windows/themes/default.css')
                                ->addCss('lib/prototype/windows/themes/magento.css');
        } 
          $this->renderLayout();
  }

  public function addAction(){
    $this->_redirect('*/filter/add');
  }

   /**
    * function Add New or Save  group 
    * @param name POST
    * @param status POST
    */
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
      
      #var_dump($num_split);

      return $num_split;
    }

    public function savegroupAction() {

      $ob = $this->getRequest()->getParam('id');
      //$tabs= $_GET['tabs'];
      $br = Mage::getModel('bumble_filter/filter')->load($ob);
      $filtertype= $br->getManufacturer();
      $usemanu = $br->getUsemanufacturer();
      
      $Num= $this->getNum();
      /*if($usemanu == 1 && $filtertype == 'all')
        {
          $products = Mage::getModel('catalog/product')->getCollection();                  
          $num = array();
          $Num= array();
        
          $i = 0;
          $att = array();

          foreach($products as $product) 
          {
            $prod= Mage::getModel('catalog/product')->load($product->getId());
            $at= $prod->getAttributeText('manufacturer');
            if($at != ''){
            $att[$i]= $at;
            $i++;
          }
          }
        
          $result = array_unique($att);
          
          //$result = array_filter($result);
          
          
          foreach ($result as $key) 
          {
            
            $k = str_replace("'", "", $key);
           $num[] =  $k;// 1 array
           
           $Num[] = $key;
        
          }
          
        
      }
      else
      {

        
        $attr = Mage::getModel('catalog/resource_eav_attribute')->load(81);

        $label = $attr->getSource()->getOptionText($filtertype);
        $lab = str_replace("'", "", $label);
        $num=array();
        $Num= array();

        $num[] = $lab;// 2 array
         $j = str_replace("'", "\'.'", $label);
        $Num[] = $label;
      }*/
          $lunghezzaArray= count($Num);
      $title= array();
      $file= array();
      $identifier= array();
      $description= array();
      $tabs= array();
      $status= array();


    if ($data = $this->getRequest()->getPost()) {

      /*********  Save Image  *********/
       $h= 1;
      foreach ($Num as $ind) {
        
      if($h<= $lunghezzaArray && $h <= 20){
      
       
      if(isset($_FILES['file'.$h]['name']) && $_FILES['file'.$h]['name'] != '') 
            {
              try 
              { 
              /* Starting upload */ 
              $uploader = new Varien_File_Uploader('file'.$h);
              $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
              $uploader->setAllowRenameFiles(false);
              $uploader->setFilesDispersion(false);
              $path = Mage::getBaseDir('media') . '/bumblefilter/';
              $uploader->save($path, $_FILES['file'.$h]['name'] );
          
              } 
              catch (Exception $e) 
              {
        
              }

              //This way save image in DB

              $data['file'.$h] = 'bumblefilter/' .preg_replace("#\s+#","_", $_FILES['file'.$h]['name']);
              $file[] = $data['file'.$h];
              $sizes = array( "filter_imagesize" => "l" );
              foreach( $sizes as $key => $size )
              {
                  $c = Mage::getStoreConfig( 'bumble_filter/general_setting/'.$key );
                  $tmp = explode( "x", $c );
              
                if( count($tmp) > 0 && (int)$tmp[0] )
                {
                  Mage::helper('bumble_filter')->resize( $data['file'.$h], (int)$tmp[0], (int)$tmp[1] );
                }
              }   
            }
             
            elseif((isset($data['file'.$h]['delete']) && $data['file'.$h]['delete'] == 1))
            {
                //can also delete file from fs
                unlink(Mage::getBaseDir('media') . DS . $data['file'.$h]['value']);
                //set path to null and save to database
                $data['file'.$h] = "";
                $file[] = $data['file'.$h];
            } 
            else 
            {
                $data['file'.$h] = isset($data['file'.$h]['value'])?$data['file'.$h]['value']:"";
                $file[] = $data['file'.$h];
            }/********* End Save Image  *********/
            }
      $h++; 
      }


      /********* Get all data  *********/
      $filterId = $this->getRequest()->getParam('id');
      $INpage = $data['page'];
      $selectform = $data['SelectURL'];
      var_dump($selectform);
      $page=$data['page'];

        if($page > 1)
        {
          $resPage= (20 * $page)-20;
        }

        if($page == 1 || $page == null){
          $resPage = 0;
        }         

         
      $h= 1;
      $zero =0;
      foreach ($Num as $ind) {
        
      if($h<= $lunghezzaArray && $h <= 20){
        if($zero < 20)
        {
          $var = $zero+$resPage;
        }
        if($data['title'.$h] == "")
          {$rep_tit = $Num[$var];}
        else
          {$rep_tit = $data['title'.$h];}
      $title[] = $rep_tit;
      if(empty($data['identifier'.$h])){
        $t= strtolower($rep_tit);
        $t=str_replace(' ', '-', $t);
        $identifier[]= $t;
      }else{
         $identifier[] = $data['identifier'.$h];
      }
     
      $description[] = $data['description'.$h];
      $status[] = $data['status'.$h];
      }
      $h++;$zero++; 
      } 
      /********* End Get all data  *********/

      /********* Write in DB *********/
      
      try {  



        $resource = Mage::getSingleton('core/resource');
        $readConnection = $resource->getConnection('core_read');
        //first query
        $query = "SELECT `group_id` FROM  bumble_filter_group WHERE `filter_id`= '$filterId' AND `page`= '$page'";

        $results = $readConnection->fetchAll($query);
        $group= array();
        foreach ($results as $key) {
          $group[]=$key['group_id'];
        }
        //second query
        $braquery = "SELECT `filter_id` FROM  bumble_filter_group WHERE `filter_id`= '$filterId' AND `page`= '$page'";

        $braresults = $readConnection->fetchAll($braquery);
        $bra_id =  $braresults[0]['filter_id'];
        //third query
        $titquery = "SELECT `title` FROM  bumble_filter_group WHERE `filter_id`= '$filterId'";

        $titresults = $readConnection->fetchAll($titquery);
        /*
        $newTit= array();
        foreach ($titresults as $key) {
          $newTit[]=$key['title'];
        }
        */
        $tabquery = "SELECT `tabs` FROM  bumble_filter_group WHERE `filter_id`= '$filterId'";

        $tabresults = $readConnection->fetchAll($tabquery);

        
        


      $resource = Mage::getSingleton('core/resource');
     
      $writeConnection = $resource->getConnection('core_write');
      $_titleCount = count($title);
      $_filterIdCount = count($titresults);
      if ($bra_id == '')
        {
      $varQuery= $resPage;
      $varQ= 0;
      foreach ($title as $a ) {
        if($a != "")
              {
          $query = "INSERT INTO bumble_filter_group (`title`,`filter_id`,`file`,`identifier`,`description`,`tabs`,`page`,`status`) VALUES ( \"$a\",\"$filterId\",\"$file[$varQ]\",\"$identifier[$varQ]\",\"$description[$varQ]\",\"$Num[$varQuery]\",\"$INpage\",\"$status[$varQ]\")";
          $writeConnection->query($query);
                  $varQuery++; $varQ++;
        }}
        
      }

      if ($bra_id == $filterId)
        {
          
           $varQuery= $resPage;
      $varQ= 0;
            foreach ($title as $a ) {
              if($a != "")
              {
              $query = "UPDATE bumble_filter_group SET title=\"$a\",file=\"$file[$varQ]\",identifier=\"$identifier[$varQ]\",description=\"$description[$varQ]\",tabs=\"$Num[$varQuery]\",page=\"$INpage\",status=\"$status[$varQ]\" WHERE group_id=\"$group[$varQ]\"";
              $writeConnection->query($query);
              $varQuery++; $varQ++;
            }
            }
        
          }

          
        if ($this->getRequest()->getParam('back')) {
          Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('bumble_filter')->__('Successfully Save, you can continue to edit'));
        Mage::getSingleton('adminhtml/session')->setFormData(false);
          
          $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id'), 'page' => $this->getRequest()->getParam('page')));
          return;
        }
        if ($this->getRequest()->getParam('Prev')) {
          
        Mage::getSingleton('adminhtml/session')->setFormData(false);
        if($this->getRequest()->getParam('page') == 1)
        {
          $pageres = 1;
        }if($this->getRequest()->getParam('page') != 1)
        {
           $pageres = $this->getRequest()->getParam('page')-1;
        }
          $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id'), 'page' => $pageres ));
          return;
        }

        if ($this->getRequest()->getParam('Next')) {
          
        Mage::getSingleton('adminhtml/session')->setFormData(false);
        if($this->getRequest()->getParam('page') == $this->maxPage())
        {
          $pageres = $this->maxPage() ;
        }else
        {
           $pageres = $this->getRequest()->getParam('page')+1;
        }
          $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id'), 'page' => $pageres ));
          return;
        }

        if ($this->getRequest()->getParam('SelectURL')) {
         
        Mage::getSingleton('adminhtml/session')->setFormData(false);
          
          $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id'), 'page' => $this->getRequest()->getParam('page')));
          return;
        }

        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('bumble_filter')->__('Group was successfully saved'));
        
           /********* End Write in DB *********/  
          } catch (Exception $e) {
        Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        Mage::getSingleton('adminhtml/session')->setFormData($data);
        $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
        return;
      }
    }//End get request data
    
    $this->_redirect('*/*/');

  }//End save Action
  public function imageAction() {
        $result = array();
        try {
            $uploader = new Bumble_Filter_Media_Uploader('image');
            $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
            $uploader->setAllowRenameFiles(true);
            $uploader->setFilesDispersion(true);
            $result = $uploader->save(
                    Mage::getSingleton('bumble_filter/config')->getBaseMediaPath()
            );

            $result['url'] = Mage::getSingleton('bumble_filter/config')->getMediaUrl($result['file']);
            $result['cookie'] = array(
                    'name'     => session_name(),
                    'value'    => $this->_getSession()->getSessionId(),
                    'lifetime' => $this->_getSession()->getCookieLifetime(),
                    'path'     => $this->_getSession()->getCookiePath(),
                    'domain'   => $this->_getSession()->getCookieDomain()
            );
        } catch (Exception $e) {
            $result = array('error'=>$e->getMessage(), 'errorcode'=>$e->getCode());
        }

        $this->getResponse()->setBody(Zend_Json::encode($result));
    }// End Add or Update

  /**
   * Delete
   */
   
  
  
  public function massRewriteAction(){
    try {

        /***************** SOLUTION 1  ******************/
        $resource = Mage::getSingleton('core/resource');
        $writeConnection = $resource->getConnection('core_write');

        $collection = Mage::getModel('bumble_filter/group')->getCollection();
        $coll = Mage::getModel('bumble_filter/filter')->getCollection();
        $extension = ".html";
        
        foreach( $collection as $model ){
          $r_Url= Mage::getModel('core/url_rewrite')->loadByIdPath('bumblefilter/filtergroup/'.$model->getId());
         if($r_Url['id_path'])
          {
            $url = explode('/', $r_Url['id_path']);
            $core= $url[2];
          }else{
            $core = null;
          }


          $m= $model->getFilterId();
          $groCollection = Mage::getModel('bumble_filter/filter')->load($m);
         
          $idenFilter= $groCollection->getIdentifier();
          $store_id = $groCollection['store_id'][0];
          $id_path = 'bumblefilter/filtergroup/'.$model->getId();
          $request_path = $idenFilter .'/'.$model->getIdentifier().$extension;
          $target_path = 'bumblefilter/filtergroup/view/id/'.$model->getId();
          $is_system = 0;
          $options = NULL;
        
           if($model->getId() != $core ) 
           {
            $query = "INSERT INTO core_url_rewrite (`store_id`,`id_path`,`request_path`,`target_path`,`is_system`) VALUES ( \"$store_id\",\"$id_path\",\"$request_path\",\"$target_path\",\"$is_system\")";
          
            $writeConnection->query($query);
           }
           else{
            $query = "UPDATE core_url_rewrite SET store_id=\"$store_id\",request_path=\"$request_path\",target_path=\"$target_path\",is_system=\"$is_system\" WHERE id_path=\"$id_path\"";
             
           $writeConnection->query($query);
           } 
            
        }

        foreach( $coll as $model ){
          $r_Url= Mage::getModel('core/url_rewrite')->loadByIdPath('bumblefilter/filter/'.$model->getId());
         if($r_Url['id_path'])
          {
            $url = explode('/', $r_Url['id_path']);
            $core= $url[2];
          }else{
            $core = null;
          }


          $m= $model->getId();
          $groCollection = Mage::getModel('bumble_filter/filter')->load($m);
         
          $idenFilter= $groCollection->getIdentifier();
          $store_id = $groCollection['store_id'][0];
          $id_path = 'bumblefilter/filter/'.$model->getId();
          $request_path = $idenFilter .'/';
          $target_path = 'bumblefilter/filter/index/id/'.$model->getId();
          $is_system = 0;
          $options = NULL;
        
           if($model->getId() != $core ) 
           {
            $query = "INSERT INTO core_url_rewrite (`store_id`,`id_path`,`request_path`,`target_path`,`is_system`) VALUES ( \"$store_id\",\"$id_path\",\"$request_path\",\"$target_path\",\"$is_system\")";
          
            $writeConnection->query($query);
           }
           else{
            $query = "UPDATE core_url_rewrite SET store_id=\"$store_id\",request_path=\"$request_path\",target_path=\"$target_path\",is_system=\"$is_system\" WHERE id_path=\"$id_path\"";
             
           $writeConnection->query($query);
           } 
            
        }
      
        /******************* SOLUTION 1 END***************/

      /*$collection = Mage::getModel('bumble_filter/group')->getCollection();
      $coll = Mage::getModel('bumble_filter/filter')->getCollection();
      $extension = ".html";
      foreach( $collection as $model ){
        $m= $model->getFilterId();
         $groCollection = Mage::getModel('bumble_filter/filter')->load($m);
         

         
         $idenFilter= $groCollection->getIdentifier();
        Mage::getModel('core/url_rewrite')->loadByIdPath('bumblefilter/filtergroup/'.$model->getId())
              ->setIsSystem(false)
              ->setStoreId($groCollection['store_id'][0])
              ->setIdPath('bumblefilter/filtergroup/'.$model->getId())
              ->setRequestPath($idenFilter .'/'.$model->getIdentifier().$extension  )
              ->setTargetPath('bumblefilter/filtergroup/view/id/'.$model->getId())
              ->save();
            
      }

      foreach( $coll as $model ){
        
         
        $groCollection = Mage::getModel('bumble_filter/filter')->load($model->getId());
         
         $idenFilter= $groCollection->getIdentifier();
        Mage::getModel('core/url_rewrite')->loadByIdPath('bumblefilter/filter/'.$model->getId())
              ->setIsSystem(false)
              ->setStoreId($groCollection['store_id'][0])
              ->setIdPath('bumblefilter/filter/'.$model->getId())
              ->setRequestPath($idenFilter)
              ->setTargetPath('bumblefilter/filter/index/id/'.$model->getId())
              ->save();
            
      }*/


      
      Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Rewrite URLs Of All Filter are resized successful'));
    } catch ( Exception $e ) {
        Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
    }
    
    $this->_redirect('*/*/'); 
  }
  
  
}// End Class
  