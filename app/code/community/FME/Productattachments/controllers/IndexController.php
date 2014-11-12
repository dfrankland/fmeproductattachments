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

class FME_Productattachments_IndexController extends Mage_Core_Controller_Front_Action
{

	public function preDispatch()
    {
		parent::preDispatch();

		$login_before_download = Mage::getStoreConfig('productattachments/general/login_before_download');
		//Productattachments id to check if there is
		$pid     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('productattachments/productattachments')->load($pid);
		$customer_group_id = $model['customer_group_id'];

		//Check if user is logged in or override configuration
		if($customer_group_id==0 || Mage::getSingleton('customer/session')->isLoggedIn()){
			$login_before_download = 0;
		}

		//Checking Configuration settings to see user authentication
		if($login_before_download){
			Mage::getSingleton('customer/session')->addError(Mage::helper('productattachments')->__('Please login to download the attachment.'));
			Mage::app()->getFrontController()->getResponse()->setRedirect($_SERVER['HTTP_REFERER']);
			$this->setFlag('', 'no-dispatch', true);
		}
    }

    public function indexAction()
    {
	$this->loadLayout();
	$this->renderLayout();
    }

    public function allAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

	public function downloadAction(){

		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('productattachments/productattachments')->load($id);

		//Checking Customer Group to download the attachment
		$customer_group_ids = explode(",",$model['customer_group_id']);
		$groupid = Mage::getSingleton('customer/session')->getCustomerGroupId();
		$isIncluded = false;

        foreach($customer_group_ids as $customer_group_id){
    		if($customer_group_id != "" || $customer_group_id != null || $customer_group_id != 0){
    			if($customer_group_id == $groupid){
					$isIncluded = true;
				}
    		}
        }

		if(!$isIncluded){
			Mage::getSingleton('customer/session')->addError(Mage::helper('productattachments')->__('This attachment is not for your User Group.'));
			Mage::app()->getFrontController()->getResponse()->setRedirect($_SERVER['HTTP_REFERER']);
			return;
		}

		//Update Download Counter
		Mage::getModel('productattachments/productattachments')->updateCounter($id);

		$fileconfig = Mage::getModel('productattachments/image_fileicon');
		$filePath = Mage::getBaseDir('media'). DS . $model['filename'];
		$fileconfig->Fileicon($filePath);
		$fileName = $model['filename'];

		$fileType = $fileconfig->getType();
		$fileSize = $fileconfig->getSize();

		if(isset($_SERVER['HTTP_USER_AGENT']) && preg_match("/MSIE/", $_SERVER['HTTP_USER_AGENT'])) {
		   ini_set( 'zlib.output_compression','Off' );
   		}
		header("Content-Type: $fileType");
		header("Pragma: public");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Disposition: attachment; filename=$fileName");
		header("Content-Transfer-Encoding: binary");
		header("Content-length: " . filesize($filePath));
		// read file
		readfile($filePath);
		exit();
	}
}
