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
* @copyright  Copyright 2010 � free-magentoextensions.com All right reserved
*/

class FME_Productattachments_Block_Widgetattachments extends Mage_Core_Block_Template implements Mage_Widget_Block_Interface{
    /**
    * A model to serialize attributes
    * @var Varien_Object
    */
    protected $_serializer = null;

    /**
    * Initialization
    */
    protected function _construct(){
        $this->_serializer = new Varien_Object();
        parent::_construct();
    }

    /**
    * Produce links list rendered as html
    *
    * @return string
    */
    protected function _tohtml(){
        $html = '';
        $attachmentId = $this->getData('enabled_download');
        if(empty($attachmentId)){
            return $html;
        }
        $download = Mage::getModel('productattachments/productattachments')->getCollection()->addFieldToFilter('productattachments_id',$attachmentId)->getData();
        $this->assign('download',$download);
        return parent::_toHtml();
    }
}
