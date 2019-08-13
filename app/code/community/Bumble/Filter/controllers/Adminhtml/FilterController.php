<?php 
 /*------------------------------------------------------------------------
  * Bumble Extension Module 
  * @package             SocialConnect
  * @author              Lanzillo Ferdinando, Sabatino Francesco
  * @copyright           Copyright (C) 2016 http://www.armah.it/armah-extension-en/ All Rights Reserved.
  * @license             http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
  * @Technical-Support   http://www.armah.it/armah-extension-en/
----------------------------------------------------------------------------*/
class Bumble_Filter_Adminhtml_FilterController extends Mage_Adminhtml_Controller_Action 
{
    protected function _initAction() {
        $this->loadLayout()
                ->_setActiveMenu('bumble_filter/filter');

        return $this;
    }
	
	
	/**
	 * index action
	 */ 
    public function indexAction() {
		
		$this->_title($this->__('Filters Manager'));
        $this->_initAction();
        $this->_addContent($this->getLayout()->createBlock('bumble_filter/adminhtml_filter') );
        $this->renderLayout();
        
		
    }
	
	public function editAction(){
		$this->_title($this->__('Edit Record'));
		$id     = $this->getRequest()->getParam('id');
        $_model  = Mage::getModel('bumble_filter/filter')->load( $id );

		Mage::register('filter_data', $_model);
        Mage::register('current_filter', $_model);
		
		$this->loadLayout();
	    $this->_setActiveMenu('bumble_filter/filter');
        $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Filter Manager'), Mage::helper('adminhtml')->__('Filter Manager'), $this->getUrl('*/*/'));
        $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Add Filter'), Mage::helper('adminhtml')->__('Add Filter'));

        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

        $this->_addContent($this->getLayout()->createBlock('bumble_filter/adminhtml_filter_edit'))
                ->_addLeft($this->getLayout()->createBlock('bumble_filter/adminhtml_filter_edit_tabs'));
		if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {

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
		$this->_forward('edit');
	}
	
	public function saveAction() {
		if ($data = $this->getRequest()->getPost()) {	    
			$model = Mage::getModel('bumble_filter/filter');
			if($data['identifier'] == '' || !isset($data['identifier']))
			$data['identifier'] = str_replace(' ', '-', strtolower($data['title'.$h]));
			
			if(isset($_FILES['file']['name']) && $_FILES['file']['name'] != '') {
				try {	
					/* Starting upload */	
					$uploader = new Varien_File_Uploader('file');
					$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
					$uploader->setAllowRenameFiles(false);
					$uploader->setFilesDispersion(false);
					$path = Mage::getBaseDir('media') . '/bumblefilter/';
					$uploader->save($path, $_FILES['file']['name'] );
					
				} catch (Exception $e) {
			  
				}
				//this way the name is saved in DB
				$data['file'] = 'bumblefilter/' .preg_replace("#\s+#","_", $_FILES['file']['name']);
				$sizes = array( "filter_imagesize" => "l" );
				foreach( $sizes as $key => $size ){
					$c = Mage::getStoreConfig( 'bumble_filter/general_setting/'.$key );
					$tmp = explode( "x", $c );
					if( count($tmp) > 0 && (int)$tmp[0] ){
						Mage::helper('bumble_filter')->resize( $data['file'], (int)$tmp[0], (int)$tmp[1] );
					}
				}		
			} elseif((isset($data['file']['delete']) && $data['file']['delete'] == 1)){
                //can also delete file from fs
                unlink(Mage::getBaseDir('media') . DS . $data['file']['value']);
                //set path to null and save to database
                $data['file'] = "";
            } else {
                $data['file'] = isset($data['file']['value'])?$data['file']['value']:"";
            }
			
			
			if(isset($_FILES['icon']['name']) && $_FILES['icon']['name'] != '') {					
				try {	
					/* Starting upload */	
					$uploader = new Varien_File_Uploader('icon');
					$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
					$uploader->setAllowRenameFiles(false);
					$uploader->setFilesDispersion(false);
					$path = Mage::getBaseDir('media') . '/bumblefilter/icon/';
					$uploader->save($path, $_FILES['icon']['name'] );
					
				} catch (Exception $e) {
			  
				}
				//this way the name is saved in DB
				$data['icon'] = 'bumblefilter/icon/' .preg_replace("#\s+#","_", $_FILES['icon']['name']);
				 	
			} elseif((isset($data['icon']['delete']) && $data['icon']['delete'] == 1)){
                //can also delete file from fs
                unlink(Mage::getBaseDir('media') . DS . $data['icon']['value']);
                //set path to null and save to database
                $data['icon'] = "";
            } else {
                $data['icon'] = isset($data['icon']['value'])?$data['icon']['value']:"";
            }

			$data['stores'] = $this->getRequest()->getParam('stores');

			
			$model->setData($data)
				->setId($this->getRequest()->getParam('id'));
			try {
				$model->save();
				$resroute = Mage::getStoreConfig('bumble_filter/general_setting/route');
				$extension = ".html";
				//Save to Url Rewite
				Mage::getModel('core/url_rewrite')->loadByIdPath('bumblefilter/filter/'.$model->getId())
							->setIdPath('bumblefilter/filter/'.$model->getId())
							->setRequestPath($resroute .'/'.$model->getIdentifier().$extension  )
							->setTargetPath('bumblefilter/filter/view/id/'.$model->getId())
							->save();
				
				
				
				// save rewrite url
				if ($this->getRequest()->getParam('back')) {
					Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('bumble_filter')->__('Successfully Save, you can continue to edit'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);
					
					$this->_redirect('*/*/edit', array('id' => $model->getId()));
					return;
				}
				if ($this->getRequest()->getParam('backto')) {
					Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('bumble_filter')->__('Your Group has been modify'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);
					$this->_redirect('*/*/', array('id' => $model->getId()));
					return;
				}
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('bumble_filter')->__('Next step field up all details, and Save your Record'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);

				$urlconf= Mage::getModel('bumble_filter/group')->load(9);
				$this->_redirect('*/filtergroup/edit', array('id' => $model->getId()));
				return;
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				Mage::getSingleton('adminhtml/session')->setFormData($data);
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
				return;
			}
		}
		
		Mage::getSingleton('adminhtml/session')->addError(Mage::helper('bumble_filter')->__('Unable to find cat to save'));
		$this->_redirect('*/*/');
    }
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
    }
	
	 public function massStatusAction() {
        $IDList = $this->getRequest()->getParam('filter');
        if(!is_array($IDList)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select record(s)'));
        } else {
            try {
                foreach ($IDList as $itemId) {
                    $_model = Mage::getSingleton('bumble_filter/filter')
                            ->setIsMassStatus(true)
                            ->load($itemId)
                            ->setIsActive($this->getRequest()->getParam('status'))
                            ->save();
                }
                $this->_getSession()->addSuccess(
                        $this->__('Total of %d record(s) were successfully updated', count($IDList))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
	public function massDeleteAction() {
		

        $IDList = $this->getRequest()->getParam('filter');
        if(!is_array($IDList)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select record(s)'));
        } else {
        	$resource = Mage::getSingleton('core/resource');
     
      			$write = $resource->getConnection('core_write');
				
            try {
                foreach ($IDList as $itemId) {
                    $_model = Mage::getModel('bumble_filter/filter')
                            ->setIsMassDelete(true)->load($itemId);
                    $_model->delete();
                    $resource = Mage::getSingleton('core/resource');
        			$readConnection = $resource->getConnection('core_read');
        			$query = "SELECT `group_id` FROM  bumble_filter_group WHERE `filter_id`= '$itemId'";

        			$results = $readConnection->fetchAll($query);

                    Mage::getModel('core/url_rewrite')->loadByIdPath('bumblefilter/filter/'.$itemId)->delete();
                    $write->query("DELETE FROM bumble_filter_group WHERE `filter_id` = '$itemId'");

                    foreach ($results as $key) {
                    	Mage::getModel('core/url_rewrite')->loadByIdPath('bumblefilter/filtergroup/'.$key['group_id'])->delete();
                    }

                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($IDList)
                        )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
	
}