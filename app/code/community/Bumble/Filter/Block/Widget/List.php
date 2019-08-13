<?php
class Bumble_Filter_Block_Widget_List extends Bumble_Filter_Block_Filternav implements Mage_Widget_Block_Interface
{
	/**
	 * Contructor
	 */
	public function __construct($attributes = array())
	{
	 	parent::__construct($attributes);
		
		/*Cache Block*/
        $enable_cache = $this->getConfig("enable_cache", 1 );
        if(!$enable_cache) {
          $cache_lifetime = null;
        } else {
          $cache_lifetime = $this->getConfig("cache_lifetime", 86400 );
          $cache_lifetime = (int)$cache_lifetime>0?$cache_lifetime: 86400;
        }

        $this->addData(array('cache_lifetime' => $cache_lifetime));

        $this->addCacheTag(array(
          Mage_Core_Model_Store::CACHE_TAG,
          Mage_Cms_Model_Block::CACHE_TAG,
          Bumble_Filter_Model_Filter::CACHE_WIDGET_LIST_TAG
        ));

	}

	/**
     * Get Key pieces for caching block content
     *
     * @return array
     */
    public function getCacheKeyInfo()
    {
        return array(
           'ARMAH_BRAND_WIDGET_LIST',
           $this->getNameInLayout(),
           Mage::app()->getStore()->getId(),
           Mage::getDesign()->getPackageName(),
           Mage::getDesign()->getTheme('template'),
           Mage::getSingleton('customer/session')->getCustomerGroupId(),
           'template' => $this->getTemplate(),
        );
    }

	public function _toHtml() {
      $pretext = $this->getConfig("pretext");
      $pretext = base64_decode($pretext);
      $filter_group = $this->getConfig('filter_group');
      $show = $this->getConfig("show");
      $limit = (int)$this->getConfig('itemvisiable');
      
      if(!$show ) return;
      $collection = Mage::getModel( 'bumble_filter/filter' )->getCollection();
      $collection->addFieldToFilter("group_filter_id", array("eq" => $filter_group))
      ->addFieldToFilter('is_active', 1)
      ->setOrder( 'position', 'ASC' );

      if($limit){
        $collection ->setPageSize($limit);
      }
      $resroute = Mage::getStoreConfig('bumble_filter/general_setting/route');
      $extension = ".html";
      foreach( $collection as $model ){
        if(!$model->getLink()){
          Mage::getModel('core/url_rewrite')->loadByIdPath('bumblefilter/filter/'.$model->getId())
                ->setIdPath('bumblefilter/filter/'.$model->getId())
                ->setRequestPath($resroute .'/'.$model->getIdentifier().$extension  )
                ->setTargetPath('bumblefilter/filter/view/id/'.$model->getId())
                ->save();
        } 
      }
      $this->setData("pretext", $pretext);
      $this->assign('resroute',$resroute);
      return parent::_toHtml();
	}
}