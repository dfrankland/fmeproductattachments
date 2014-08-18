<?php

class FME_Productattachments_Model_Observer_Product extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the manufacturers_id refers to the key field in your database table.
        $this->_init('productattachments/productattachments', 'productattachments_id');
    }
	
	/**
	 * This method will run when the product is saved
	 * Use this function to update the product model and save
	 *
	 * @param Varien_Event_Observer $observer
	 */
	public function saveTabData(Varien_Event_Observer $observer)
	{
		if ($post = $this->_getRequest()->getPost()) {
			
			$productID = Mage::registry('current_product')->getId();
			$condition = $this->_getWriteAdapter()->quoteInto('product_id = ?', $productID);
			
			//Get Related Products	
			
			if(isset($post['links'])) {
				$links = $post['links'];
			}
			
			if (isset($links['related_attachments'])) {
				
				$attachmentIds = Mage::helper('adminhtml/js')->decodeGridSerializedInput($links['related_attachments']);
				$this->_getWriteAdapter()->delete($this->getTable('productattachments_products'), $condition);
				
				//Save Related Products
				foreach ($attachmentIds as $_attachment) {
					$attachmentArray = array();
					$attachmentArray['productattachments_id'] = $_attachment;
					$attachmentArray['product_id'] = $productID;
					$this->_getWriteAdapter()->insert($this->getTable('productattachments_products'), $attachmentArray);
				}
			} 
		}
	}
	
	/**
	 * Shortcut to getRequest
	 */
	protected function _getRequest()
	{
		return Mage::app()->getRequest();
	}
}