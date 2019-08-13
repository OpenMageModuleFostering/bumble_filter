<?php
/***********************Bumble Filter**************************************/
class Bumble_Filter_Model_Observer  extends Varien_Object
{
	
	/**
	 *
	 */
	public function getRoute()
	{
		$url= $_SERVER['HTTP_HOST'];
         $url2 = $_SERVER["REQUEST_URI"];
         $route= str_replace($url, '', $url2);
         $route= str_replace('/', '', $route);
    
         $collection = Mage::getModel('bumble_filter/filter')->getCollection();

      
      
      foreach( $collection as $model ){
        $m= $model->getFilterId();
         $groCollection = Mage::getModel('bumble_filter/filter')->load($m);
         $idenFilter= $groCollection->getIdentifier();
        if ($idenFilter == $route)
        {
        	$rou = $idenFilter;
        	return $rou;
        }
    	}

    	

	}

	public function initControllerRouters( $observer ){	
		
        $request = $observer->getEvent()->getFront()->getRequest();
	
		$identifier = trim($request->getPathInfo(), '/');
	
	 
        $condition = new Varien_Object(array(
            'identifier' => $identifier,
            'continue'   => true
        ));
        Mage::dispatchEvent('filter_controller_router_match_before', array(
            'router'    => $this,
            'condition' => $condition
        ));
        $identifier = $condition->getIdentifier();
		 
		 
        if ($condition->getRedirectUrl()) {
            Mage::app()->getFrontController()->getResponse()
                ->setRedirect($condition->getRedirectUrl())
                ->sendResponse();
            $request->setDispatched(true);
            return true;
        }


        if (!$condition->getContinue())
            return false;
         
		
		if($identifier) {
			
            if(  preg_match("#^".$this->getRoute()."(\.html)?$#",$identifier, $match) ) {
                $request->setModuleName('bumblefilter')
                        ->setControllerName('filter')
                        ->setActionName('index');
                $request->setAlias(
                    Mage_Core_Model_Url_Rewrite::REWRITE_REQUEST_PATH_ALIAS,
                    $identifier
                );
                return true;
			
            }  
			return true;
	    } 
		
        return false;					
	}
	
	/**
	 *
	 */
	public function beforeRender( Varien_Event_Observer $observer ){
	//	$controller_name = Mage::app()->getRequest()->getControllerModule();
	//	$menu_name = $controller_name."_".Mage::app()->getRequest()->getControllerName();
		$helper =  Mage::helper('bumble_filter/data');
		
		
		// if($helper->checkAvaiable( $controller_name )){
			 $config = $helper->get();
			return $this->_loadMedia( $config );
		 	/**LATEST BLOG */
		//	$this->filterScrollModule( $menu_name , $helper );
			/** CATEGORY BLOG */
	//		$this->filterNavModule( $menu_name , $helper );
		// }
   }
   
   public function getGeneralConfig( $val, $default = "" ){ 
		return Mage::getStoreConfig( "bumble_filter/general_setting/".$val );
 	}

   public function getModuleConfig( $val ){
		return Mage::getStoreConfig( "bumble_filter/module_setting/".$val );
   }
   
   public function filterScrollModule( $menu_name, $helper ){
   		if( !$this->getModuleConfig("enable_scrollmodule") ){
			return ;
		}
		
		if($helper->checkMenuItem( $menu_name, $this->getModuleConfig("scroll_menuassignment") )){
			
			$layout = Mage::getSingleton('core/layout');
			$title = $this->getModuleConfig("scroll_title");
			$position = $this->getModuleConfig("scroll_position");
			if( !$position ){ $position = "right"; }
			
			$cposition = $this->getModuleConfig("scroll_customposition");
			if( $cposition ){ $position = $cposition; }

			$display = $this->getModuleConfig("scroll_display");
			if( $display=="after" ){ $display = true; }else { $display=false; }
	
			$block =  $layout->createBlock( 'bumble_filter/scroll' );
	
			if($myblock = $layout->getBlock( $position )){
				$myblock->insert($block, $title , $display);
			}

		}
   }
   
   
   
    public function filterNavModule( $menu_name, $helper ){
		if( !$this->getModuleConfig("enable_filternavmodule")){
			return ;
		}

		if($helper->checkMenuItem( $menu_name, $this->getModuleConfig("filternav_menuassignment") )){
			
			$layout = Mage::getSingleton('core/layout');
			$title = $this->getModuleConfig("filternav_title");
			$position = $this->getModuleConfig("filternav_position");
			if( !$position ){ $position = "right"; }
			
			$cposition = $this->getModuleConfig("filternav_customposition");
			if( $cposition ){ $position = $cposition; }

			$display = $this->getModuleConfig("filternav_display");
			if( $display=="after" ){ $display = true; }else { $display=false; }

			$block =  $layout->createBlock( 'bumble_filter/filternav' );

			if($myblock = $layout->getBlock( $position )){
				$myblock->insert($block, $title , $display);
			}

		}
	}

   function _loadMedia( $config = array()){
		/*
		$mediaHelper =  Mage::helper('bumble_filter/media');
		$mediaHelper->addMediaFile("skin_css", "bumble_filter/style.css" );*/
	}
}
