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
 
class FME_Productattachments_Block_Cmspagesattachments extends Mage_Catalog_Block_Product_Abstract
{
	
	const DISPLAY_CONTROLS = 'productattachments/cmspagesattachments/enabled';
	protected function _tohtml()
    {
		if (!Mage::getStoreConfig(self::DISPLAY_CONTROLS)) {
			return parent::_toHtml();
		} 
		$this->setLinksforProduct();
		$this->setTemplate("productattachments/cms_attachments.phtml");
		return parent::_toHtml();
    }
	
	public function getCmsPageRelatedAttachments()    {
		
		$dataCurrentPage = $this->getHelper('cms/page')->getPage()->getData();
		$pageid = $dataCurrentPage['page_id'];
	
		$storeId = Mage::app()->getStore()->getId();
		
		$productattachmentsTable = Mage::getSingleton('core/resource')->getTableName('productattachments');
		$productattachmentsStoreTable = Mage::getSingleton('core/resource')->getTableName('productattachments_store');
		
		$sqry = "SELECT main_table.* FROM ".$productattachmentsTable." main_table 
				 INNER JOIN ".$productattachmentsStoreTable." AS store_table ON main_table.productattachments_id = store_table.productattachments_id
				 WHERE store_table.store_id in(0,".$storeId.") 
				 AND (main_table.cmspage_id LIKE '%,".$pageid."' or main_table.cmspage_id like '%,".$pageid.",%' or main_table.cmspage_id like '".$pageid.",%' or main_table.cmspage_id = ".$pageid.")";
		$connection = Mage::getSingleton('core/resource')->getConnection('core_read');
		$select = $connection->query($sqry);
		$relatedCmsAttachments = $select->fetchAll();	
		return $relatedCmsAttachments;
		
	}
	
}