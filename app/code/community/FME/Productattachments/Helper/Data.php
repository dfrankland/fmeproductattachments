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

class FME_Productattachments_Helper_Data extends Mage_Core_Helper_Abstract
{
	const XML_PATH_LIST_PRODUCT_PAGE_ATTACHMENT_HEADING	= 'productattachments/productattachments/product_attachment_heading';
	const XML_PATH_LIST_CMS_PAGE_ATTACHMENT_HEADING	= 'productattachments/cmspagesattachments/cms_page_attachment_heading';
	
	//const XML_PATH_LIST_ALLOWED_FILE_EXTENSIONS	= 'productattachments/productattachments/allowed_file_extensions';

	/*
     * @var array Characters to be URL-encoded
     */
    protected static $_URL_ENCODED_CHARS = array(
            ' ', '+', '(', ')', ';', ':', '@', '&', '`', '\'',
            '=', '!', '$', ',', '/', '?', '#', '[', ']', '%',
        );


	public function getProductPageAttachmentHeading()
	{
		return Mage::getStoreConfig(self::XML_PATH_LIST_PRODUCT_PAGE_ATTACHMENT_HEADING);
	}
	
	public function getCMSPageAttachmentHeading()
	{
		return Mage::getStoreConfig(self::XML_PATH_LIST_CMS_PAGE_ATTACHMENT_HEADING);
	}
	
	/*public function getAllowedFileExtensions()
	{
		return Mage::getStoreConfig(self::XML_PATH_LIST_ALLOWED_FILE_EXTENSIONS);
	}*/
	
	public static function nameToUrlKey($name)
    {
        $name = trim($name);

        $name = str_replace(self::$_URL_ENCODED_CHARS, '_', $name);

        do {
            $name = $newStr = str_replace('__', '_', $name, $count);
        } while($count);

        return $name;
    }
	
	public function getCatData($pid=0){
		$out = array();
         $collection = Mage::getModel('productattachments/productcats')->getCollection()
			->addOrder('category_name', 'ASC');

		foreach ($collection as $item){
			$out[] = $item->getData();
		}
		
		return $out;
	}
	
	public function getSelectcat(){
		//Set 0 For Parent Cat!
		$this->drawSelect(0);				
        foreach ($this->outtree['value'] as $k => $v){
        	$out[] = array('value'=>$v, 'label'=>$this->outtree['label'][$k]);
        }
		return $out;
	}


	public function drawSelect($pid=0){
		$items = $this->getCatData($pid);
		if(count($items) > 0 ){
			$this->outtree['value'][] = $item[0];
			$this->outtree['label'][] = 'Select Category';
			foreach ($items as $item){
				$this->outtree['value'][] = $item['category_id'];
				$this->outtree['label'][] = $item['category_name'];
			}
		} 
		return;
	}

}