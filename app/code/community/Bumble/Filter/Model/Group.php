<?php
 
class Bumble_Filter_Model_Group extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {	
	    $this->_init('bumble_filter/group');
	
    }
    public function getCategoryLink(){
      return  Mage::getBaseUrl().Mage::getModel('core/url_rewrite')->loadByIdPath('bumblefilter/filter/index/category/'.$this->getId())->getRequestPath();
    }

    public function deletes($group_id)
    {
      $res = true;
      $images = $this->image;
      foreach ($images as $image)
      {
        if (preg_match('/sample/', $image) === 0)
          if ($image && file_exists(dirname(__FILE__).'/images/'.$image))
            $res &= @unlink(dirname(__FILE__).'/images/'.$image);
      }
      $model = $this->setId($group_id);
          try{
            $model->delete();
            //Mage::getSingleton('core/session')->addSuccess( 'Deleted profile successfully!' );  
            //echo 'Inserted abc',$group_id;
        }catch(exception $e){
            //Mage::getSingleton( 'core/session') ->addError( $e->getMessage() );
        }
      return $res;
    }
}