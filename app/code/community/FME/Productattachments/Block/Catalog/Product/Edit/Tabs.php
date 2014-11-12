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
 

class FME_Productattachments_Block_Catalog_Product_Edit_Tabs extends Mage_Adminhtml_Block_Catalog_Product_Edit_Tabs {
 
 protected function _prepareLayout()
    {
        $ret = parent::_prepareLayout();
        
        $product = $this->getProduct();

        if (!($setId = $product->getAttributeSetId())) {
            $setId = $this->getRequest()->getParam('set', null);
        }

        if ($setId) {

            $this->addTab('attachments', array(
                'label'     => Mage::helper('catalog')->__('Attachments'),
                'url'       => $this->getUrl('productattachments/catalog_product/attachments', array('_current' => true)),
                'class'     => 'ajax',
            ));
        }
        else {;}
        return $ret;
		//
    } 
}

?>
