<?php
 /*------------------------------------------------------------------------
  * Bumble Extension Module 
  * @package             SocialConnect
  * @author              Lanzillo Ferdinando, Sabatino Francesco
  * @copyright           Copyright (C) 2016 http://www.armah.it/armah-extension-en/ All Rights Reserved.
  * @license             http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
  * @Technical-Support   http://www.armah.it/armah-extension-en/
--------------------------------------------------------------------------*/
class Bumble_Filter_Model_Mysql4_Filter_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract {
    protected $_previewFlag = false;
    /**
     * Constructor method
     */
    protected function _construct() {
		parent::_construct();
		$this->_init('bumble_filter/filter');
    }

    /**
     * Add Filter by store
     *
     * @param int|Mage_Core_Model_Store $store
     * @return Bumble_Slider_Model_Mysql4_Filter_Collection
     */
    public function addStoreFilter($store) {
        if (!Mage::app()->isSingleStoreMode()) {
            if ($store instanceof Mage_Core_Model_Store) {
                $store = array($store->getId());
            }

            $this->getSelect()->join(
                    array('store_table' => $this->getTable('bumble_filter/filter_store')),
                    'main_table.filter_id = store_table.filter_id',
                    array()
                    )
                    ->where('store_table.store_id in (?)', array(0, $store))
                    ->group('main_table.filter_id');
            return $this;
        }
        return $this;
    }

    /**
     * Add Filter by status
     *
     * @param int $status
     * @return Bumble_Slider_Model_Mysql4_Filter_Collection
     */
    public function addEnableFilter($status = 1) {
        $this->getSelect()->where('main_table.is_active = ?', $status);
        return $this;
    }
	
	 public function addChildrentFilter( $parentId = 1) {
        $this->getSelect()->where( 'main_table.parent_id = ?', $parentId );
        return $this;
    }
    /**
     * After load processing - adds store information to the datasets
     *
     */
    protected function _beforeLoad()
    {
       $store_id = Mage::app()->getStore()->getId();
       if($store_id){
         $this->addStoreFilter($store_id);
       }
       
       parent::_beforeLoad();
       return $this;
    }
    /**
     * After load processing - adds store information to the datasets
     *
     */
    protected function _afterLoad()
    {
        parent::_afterLoad();
        return $this;
    }
}