<?php
 
class FME_Productattachments_Block_All extends Mage_Catalog_Block_Product_Abstract
{
	protected function _prepareLayout()
    {
        parent::_prepareLayout();
    }

	protected function getAllCategories()
	{
        $cats = Mage::getModel('productattachments/productcats')
        		->getCollection()
                ->addOrder('sort_order', SORT_ORDER_ASC)
        		->addFilter('category_status', 1)
        		->addFilter('show_on_mediathek', 1);
        return $cats;
	}
	
	protected function getDownloadsByCategory($categoryId)
	{
		$productattachmentsTable = Mage::getSingleton('core/resource')->getTableName('productattachments');
		$productattachmentsStoreTable = Mage::getSingleton('core/resource')->getTableName('productattachments_store');
		$productattachmentsCategoryTable = Mage::getSingleton('core/resource')->getTableName('productattachments_cats');
		$storeId = Mage::app()->getStore()->getId();
		
		$sqry = "SELECT a.productattachments_id, a.file_icon, a.limit_downloads, a.title, a.content, cat.category_prefix
				 FROM ".$productattachmentsTable." a 
				 INNER JOIN ".$productattachmentsStoreTable." AS store_table ON a.productattachments_id = store_table.productattachments_id
				 INNER JOIN ".$productattachmentsCategoryTable." AS cat ON a.cat_id = cat.category_id
				 WHERE
				 	cat.category_id = ".$categoryId."
				 	AND store_table.store_id in(0,".$storeId.")
				 	AND a.status = 1
				 	AND cat.category_status = 1
				 	AND a.show_on_mediathek = 1
				 ORDER BY a.title ASC";
				 
		$connection = Mage::getSingleton('core/resource')->getConnection('core_read');
		$select = $connection->query($sqry);
		$relatedProductAttachments = $select->fetchAll();	
		return $relatedProductAttachments;
	}	
}
