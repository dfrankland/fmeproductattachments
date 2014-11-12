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
 
class FME_Productattachments_Block_Productattachments extends Mage_Catalog_Block_Product_Abstract
{
	
	const DISPLAY_CONTROLS = 'productattachments/productattachments/enabled';
	protected function _tohtml()
    {
		 if (!Mage::getStoreConfig(self::DISPLAY_CONTROLS))
            return parent::_toHtml();

		$this->setLinksforProduct();
		$this->setTemplate("productattachments/productattachments.phtml");
		return parent::_toHtml();
    }
	
	public function getProductRelatedAttachments()    {
		
		$id  = $this->getProduct()->getId(); 		
		$productattachmentsTable = Mage::getSingleton('core/resource')->getTableName('productattachments');
		$productattachmentsProductsTable = Mage::getSingleton('core/resource')->getTableName('productattachments_products');
		$productattachmentsStoreTable = Mage::getSingleton('core/resource')->getTableName('productattachments_store');
		$storeId = Mage::app()->getStore()->getId();
		
		$sqry = "SELECT a.* FROM ".$productattachmentsTable." a 
				 INNER JOIN ".$productattachmentsStoreTable." AS store_table ON a.productattachments_id = store_table.productattachments_id
				 INNER JOIN ".$productattachmentsProductsTable." AS ap ON a.productattachments_id = ap.productattachments_id
				 WHERE ap.product_id = ".$id." AND store_table.store_id in(0,".$storeId.") AND a.status = 1";
		$connection = Mage::getSingleton('core/resource')->getConnection('core_read');
		$select = $connection->query($sqry);
		$relatedProductAttachments = $select->fetchAll();	
		return $relatedProductAttachments;
		
	}
}