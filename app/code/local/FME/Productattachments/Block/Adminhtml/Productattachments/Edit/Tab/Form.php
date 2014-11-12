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

class FME_Productattachments_Block_Adminhtml_Productattachments_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
		$form = new Varien_Data_Form();
		$this->setForm($form);
		$fieldset = $form->addFieldset('productattachments_form', array('legend'=>Mage::helper('productattachments')->__('File information')));

		$fieldset->addField('title', 'text', array(
		  'label'     => Mage::helper('productattachments')->__('Title'),
		  'class'     => 'required-entry',
		  'required'  => true,
		  'name'      => 'title',
		));

		$fieldset->addField('cat_id', 'select', array(
          'label'     => Mage::helper('productattachments')->__('Category'),
          'name'      => 'cat_id',
		  'type'      => 'text',
          'values'    => Mage::helper('productattachments')->getSelectcat(),
        ));

		$object = Mage::getModel('productattachments/productattachments')->load( $this->getRequest()->getParam('id') );
		$note = false;
		if($object->getFilename()) {
			$File =  Mage::getBaseUrl('media').$object->getFilename();

			//Get File Size, Icon, Type
			$fileconfig = Mage::getModel('productattachments/image_fileicon');
			$filePath = Mage::getBaseDir('media'). DS . $object->getFilename();
			$fileconfig->Fileicon($filePath);
			$DownloadURL = $object->getFileIcon().'&nbsp;&nbsp;<a href='.$File.' target="_blank">Download Current File!</a>';
		} else {
			$DownloadURL = '';
		}

		$fieldset->addField('my_file_uploader', 'file', array(
			'label'        => Mage::helper('productattachments')->__('File'),
			'note'      => $note,
			'name'        => 'my_file_uploader',
			'class'     => (($object->getFilename()) ? '' : 'required-entry'),
			'required'  => (($object->getFilename()) ? false : true),
			'after_element_html' => $DownloadURL,
		 ));

		$fieldset->addField('my_file', 'hidden', array(
			'name'        => 'my_file',
		));

		$fieldset->addField('store_id','multiselect',array(
			'name'      => 'stores[]',
			'label'     => Mage::helper('productattachments')->__('Store View'),
			'title'     => Mage::helper('productattachments')->__('Store View'),
			'required'  => true,
			'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true)
		));

		$fieldset->addField('status', 'select', array(
		  'label'     => Mage::helper('productattachments')->__('Status'),
		  'name'      => 'status',
		  'values'    => array(
			  array(
				  'value'     => 1,
				  'label'     => Mage::helper('productattachments')->__('Enabled'),
			  ),

			  array(
				  'value'     => 2,
				  'label'     => Mage::helper('productattachments')->__('Disabled'),
			  ),
		  ),
		));

		//fetch all user groups
		$customer_group = new Mage_Customer_Model_Group();
		$groups_array = array();
		$allGroups  = $customer_group->getCollection()->toOptionHash();
		foreach($allGroups as $key=>$allGroup){
			  $groups_array[] = array('value'=>$key, 'label'=>$allGroup,);
		}

		$fieldset->addField('customer_group_id', 'multiselect', array(
		  'label'     => Mage::helper('productattachments')->__('Customer Group'),
		  'name'      => 'customer_group_id',
		  'values'    => $groups_array,
		  'after_element_html' => '<p class="nm"><small>' . Mage::helper('productattachments')->__('(This option will override the configuration settings)') . '</small></p>'
		));

		$fieldset->addField('limit_downloads', 'text', array(
		  'label'     => Mage::helper('productattachments')->__('Limit Downloads'),
		  'name'      => 'limit_downloads',
		  'after_element_html' => '<p class="nm"><small>' . Mage::helper('productattachments')->__('(Enter number of downloads for this attachment. If empty then unlimited.)') . '</small></p>'
		));

		$fieldset->addField('content', 'editor', array(
		  'name'      => 'content',
		  'label'     => Mage::helper('productattachments')->__('Content'),
		  'title'     => Mage::helper('productattachments')->__('Content'),
		  'style'     => 'width:400px; height:200px;',
		  'wysiwyg'   => false,
		  'required'  => false,
		));

		if ( Mage::getSingleton('adminhtml/session')->getProductattachmentsData() )
		{
		  $form->setValues(Mage::getSingleton('adminhtml/session')->getProductattachmentsData());
		  Mage::getSingleton('adminhtml/session')->setProductattachmentsData(null);
		} elseif ( Mage::registry('productattachments_data') ) {
		  $form->setValues(Mage::registry('productattachments_data')->getData());
		}
		return parent::_prepareForm();
  }
}
