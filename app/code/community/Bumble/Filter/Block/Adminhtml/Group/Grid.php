<?php

class Bumble_Filter_Block_Adminhtml_Group_Grid extends Mage_Adminhtml_Block_Widget_Grid 
{
    public function __construct() {
		
        parent::__construct();
 
        $this->setId('postGrid');
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

       $this->addColumn('title', array(
                'header'    => Mage::helper('bumble_filter')->__('Group Name'),
                'align'     =>'center',
                'index'     => 'title',
        ));
       
        $this->addColumn('status', array(
                'header'    => Mage::helper('bumble_filter')->__('Status'),
                'align'     => 'center',
                'width'     => '80px',
                'index'     => 'status',
                'type'      => 'options',
                'options'   => array(
                        1 => Mage::helper('bumble_filter')->__('Enabled'),
                        0 => Mage::helper('bumble_filter')->__('Disabled'),
                //3 => Mage::helper('bumble_filter')->__('Hidden'),
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
    

    public function getRowUrl($row) {
     
     
        return $this->getUrl('*/*/edit/', array('id' => $row->getId(), 'page'=>1 ));
    }

}