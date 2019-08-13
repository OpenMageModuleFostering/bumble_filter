<?php
 /*------------------------------------------------------------------------
  * Bumble Extension Module 
  * @package             SocialConnect
  * @author              Lanzillo Ferdinando, Sabatino Francesco
  * @copyright           Copyright (C) 2016 http://www.armah.it/armah-extension-en/ All Rights Reserved.
  * @license             http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
  * @Technical-Support   http://www.armah.it/armah-extension-en/
------------------------------------------------------------------------*/
class Bumble_Filter_Model_Mysql4_Filter extends Mage_Core_Model_Mysql4_Abstract {

    /**
     * Initialize resource model
     */
    protected function _construct() {
	
        $this->_init('bumble_filter/filter', 'filter_id');
    }

    /**
     * Load images
     */
   // public function loadImage(Mage_Core_Model_Abstract $object) {
   //     return $this->__loadImage($object);
   // }

    /**
     * Retrieve select object for load object data
     *
     * @param string $field
     * @param mixed $value
     * @return Zend_Db_Select
     */
    protected function _getLoadSelect($field, $value, $object) {
        $select = parent::_getLoadSelect($field, $value, $object);

        return $select;
    }



    /**
     * Call-back function
     */
    protected function _beforeDelete(Mage_Core_Model_Abstract $object) {
        // Cleanup stats on filter delete
        $adapter = $this->_getReadAdapter();
        // 1. Delete filter/store
        //$adapter->delete($this->getTable('bumble_filter/filter_store'), 'filter_id='.$object->getId());
        // 2. Delete filter/post_cat

        return parent::_beforeDelete($object);
    }
    /**
   * Assign page to store views
   *
   * @param Mage_Core_Model_Abstract $object
   */
  protected function _afterSave(Mage_Core_Model_Abstract $object)
  {
    $condition = $this->_getWriteAdapter()->quoteInto('filter_id = ?', $object->getId());
    // process faq item to store relation
    $this->_getWriteAdapter()->delete($this->getTable('bumble_filter/filter_store'), $condition);
    $stores = (array) $object->getData('stores');

    if($stores){
      foreach ((array) $object->getData('stores') as $store) {
        $storeArray = array ();
        $storeArray['filter_id'] = $object->getId();
        $storeArray['store_id'] = $store;
        $this->_getWriteAdapter()->insert(
          $this->getTable('bumble_filter/filter_store'), $storeArray
        );
      } 
    }else{
      $storeArray = array ();
      $storeArray['filter_id'] = $object->getId();
      $storeArray['store_id'] = $object->getStoreId();
      $this->_getWriteAdapter()->insert(
        $this->getTable('bumble_filter/filter_store'), $storeArray
      );
    }
    
    
    return parent::_afterSave($object);
  }

  /**
   * Do store and category processing after loading
   * 
   * @param Mage_Core_Model_Abstract $object Current faq item
   */
  protected function _afterLoad(Mage_Core_Model_Abstract $object)
  {
      // process faq item to store relation
    $select = $this->_getReadAdapter()->select()->from(
      $this->getTable('bumble_filter/filter_store')
    )->where('filter_id = ?', $object->getId());
    
    if ($data = $this->_getReadAdapter()->fetchAll($select)) {
      $storesArray = array ();
      foreach ($data as $row) {
        $storesArray[] = $row['store_id'];
      }
      $object->setData('store_id', $storesArray);
    }
        
    return parent::_afterLoad($object);
  }

  public function lookupStoreIds($theme_id = 0){
    $select = $this->_getReadAdapter()->select()->from(
      $this->getTable('bumble_filter/filter_store')
    )->where('filter_id = ?', (int)$theme_id);

    $storesArray = array ();

    if ($data = $this->_getReadAdapter()->fetchAll($select)) {
      
      foreach ($data as $row) {
        $storesArray[] = $row['store_id'];
      }
    }
    return $storesArray;
    }

}
