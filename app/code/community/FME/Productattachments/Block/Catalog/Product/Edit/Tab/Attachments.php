<?php
/**
 * Productattachments extension
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   FME
 * @package    Productattachments
 * @author     Kamran Rafiq Malik <kamran.malik@unitedsol.net>
 * @copyright  Copyright 2010 © free-magentoextensions.com All right reserved
 */
 
 
class FME_Productattachments_Block_Catalog_Product_Edit_Tab_Attachments extends Mage_Adminhtml_Block_Widget_Grid {
 
	public function __construct() {
		parent::__construct();
		$this->setId('product_attachments_grid');
		$this->setDefaultSort('productattachments_id');
		$this->setUseAjax(true);	
		$this->setDefaultFilter(array('in_attachments'=>1));
	}
	
	/**
	 * Retirve currently edited product model
	 *
	 * @return Mage_Catalog_Model_Product
	 */
	protected function _getProduct()
	{
		return Mage::registry('current_product_attachments');
	}


	/**
     * Add filter
     *
     * @param object $column
     * @return Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Related
     */
    protected function _addColumnFilterToCollection($column)
    {
        // Set custom filter for in product flag
        if ($column->getId() == 'in_attachments') {
            $attachmentIds = $this->_getSelectedAttachments();
            if (empty($attachmentIds)) {
                $attachmentIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('productattachments_id', array('in'=>$attachmentIds));
            } else {
                if($attachmentIds) {
                    $this->getCollection()->addFieldToFilter('productattachments_id', array('nin'=>$attachmentIds));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }

	protected function _prepareCollection () {
	  
	    $collection = Mage::getModel('productattachments/productattachments')->getCollection();
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}
  
  	/**
     * Checks when this block is readonly
     *
     * @return boolean
     */
    public function isReadonly()
    {
        return 0;
    }

   protected function _prepareColumns()
  {
	  
	  $this->addColumn('in_attachments', array(
			'header_css_class'  => 'a-center',
			'type'              => 'checkbox',
			'name'              => 'in_attachments',
			'values'            => $this->_getSelectedAttachments(),
			'align'             => 'center',
			'index'             => 'productattachments_id'
		));
	  
      $this->addColumn('productattachments_id', array(
          'header'    => Mage::helper('productattachments')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'productattachments_id',
      ));

      $this->addColumn('title', array(
          'header'    => Mage::helper('productattachments')->__('Title'),
          'align'     =>'left',
          'index'     => 'title',
      ));

      $this->addColumn('status', array(
          'header'    => Mage::helper('productattachments')->__('Status'),
          'align'     => 'left',
          'width'     => '80px',
          'index'     => 'status',
          'type'      => 'options',
          'options'   => array(
              1 => 'Enabled',
              2 => 'Disabled',
          ),
      ));
	  
      return parent::_prepareColumns();
  }

  public function getGridUrl() {
    	return $this->getData('grid_url') ? $this->getData('grid_url') : $this->getUrl('*/*/attachmentsGrid', array('_current' => true));
  }
  
  	public function _getSelectedAttachments()
    {
        $products = $this->getProductRelatedAttachments();	
        if (!is_array($products)) {
            $products = array_keys($this->getProductAttachments());
        }
        return $products;
    }
  
  	/**
     * Retrieve related products
     *
     * @return array
     */
    public function getProductAttachments()
    {
		$productid = $this->getRequest()->getParam('id');
       	$productsArr = array();
        foreach (Mage::registry('current_product_attachments')->getRelatedAttachments($productid) as $products) {
           $productsArr[$products["productattachments_id"]] = array('position' => '0');
        }
        return $productsArr;

    }
	
}

?>
