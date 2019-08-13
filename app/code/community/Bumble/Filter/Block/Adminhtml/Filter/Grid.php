<?php
/*------------------------------------------------------------------------
  * Bumble Extension Module 
  * @package             SocialConnect
  * @author              Lanzillo Ferdinando, Sabatino Francesco
  * @copyright           Copyright (C) 2016 http://www.armah.it/armah-extension-en/ All Rights Reserved.
  * @license             http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
  * @Technical-Support   http://www.armah.it/armah-extension-en/
---------------------------------------------------------------------------*/
class Bumble_Filter_Block_Adminhtml_Filter_Grid extends Mage_Adminhtml_Block_Widget_Grid 
{ 
    public function __construct() {
        
        parent::__construct();
        
    
        $this->setId('filter');
        $this->setDefaultSort('date_from');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        
    }

  //  protected function _getStore() {
   //     $storeId = (int) $this->getRequest()->getParam('store', 0);
   //     return Mage::app()->getStore($storeId);
   // }

    protected function _prepareCollection() {
        $collection = Mage::getModel('bumble_filter/filter')->getCollection();
        //$store = $this->_getStore();
        //if ($store->getId()) {
        //    $collection->addStoreFilter($store);
       // }
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
    
    
    protected function _prepareColumns() {  
     
        $this->addColumn('filter_id', array(
                'header'    => Mage::helper('bumble_filter')->__('ID'),
                'align'     =>'center',
                'width'     => '50px',
                'index'     => 'filter_id',
        ));
         
        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('store_id', 
                    array (
                            'header' => Mage::helper('cms')->__('Store view'), 
                            'index' => 'store_id', 
                            'type' => 'store', 
                            'store_all' => true, 
                            'store_view' => true, 
                            'sortable' => false, 
                            'filter_condition_callback' => array (
                                    $this, 
                                    '_filterStoreCondition' ) ));
        }

        $this->addColumn('title', array(
                'header'    => Mage::helper('bumble_filter')->__('Title'),
                'align'     =>'left',
                'index'     => 'title',
        ));
        $this->addColumn('identifier', array(
                'header'    => Mage::helper('bumble_filter')->__('Identifier'),
                'align'     =>'left',
                'index'     => 'identifier',
        )); 
        
       
        
        $this->addColumn('is_active', array(
                'header'    => Mage::helper('bumble_filter')->__('Status'),
                'align'     => 'left',
                'width'     => '80px',
                'index'     => 'is_active',
                'type'      => 'options',
                'options'   => array(
                        1 => Mage::helper('bumble_filter')->__('Enabled'),
                        0 => Mage::helper('bumble_filter')->__('Disabled'),
                ),
        ));

        return parent::_prepareColumns();
    }
    /**
     * Helper function to do after load modifications
     *
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
    /**
     * Helper function to add store filter condition
     *
     * @param Mage_Core_Model_Mysql4_Collection_Abstract $collection Data collection
     * @param Mage_Adminhtml_Block_Widget_Grid_Column $column Column information to be filtered
     */
    protected function _filterStoreCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        
        $this->getCollection()->addStoreFilter($value);
    }

    protected function _prepareMassaction() { 
        $this->setMassactionIdField('filter_id');
        $this->getMassactionBlock()->setFormFieldName('filter');

        $this->getMassactionBlock()->addItem('delete', array(
                'label'    => Mage::helper('bumble_filter')->__('Delete'),
                'url'      => $this->getUrl('*/*/massDelete'),
                'confirm'  => Mage::helper('bumble_filter')->__('Are you sure?')
        ));

        $statuses = array(
                1 => Mage::helper('bumble_filter')->__('Enabled'),
                2 => Mage::helper('bumble_filter')->__('Disabled')
                );
        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
                'label'=> Mage::helper('bumble_filter')->__('Change status'),
                'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                'additional' => array(
                        'visibility' => array(
                                'name' => 'status',
                                'type' => 'select',
                                'class' => 'required-entry',
                                'label' => Mage::helper('bumble_filter')->__('Status'),
                                'values' => $statuses
                        )
                )
        ));
        return $this;
    }

    public function getRowUrl($row) {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

}
