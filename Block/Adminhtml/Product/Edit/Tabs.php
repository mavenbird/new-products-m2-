<?php
/**
 * Mavenbird Technologies Private Limited
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://mavenbird.com/Mavenbird-Module-License.txt
 *
 * =================================================================
 *
 * @category   Mavenbird
 * @package    Mavenbird_Newproduct
 * @author     Mavenbird Team
 * @copyright  Copyright (c) 2018-2024 Mavenbird Technologies Private Limited ( http://mavenbird.com )
 * @license    http://mavenbird.com/Mavenbird-Module-License.txt
 */
namespace Mavenbird\Newproduct\Block\Adminhtml\Product\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{

   
    /**
     * Contruct
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('product_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Add Newproduct'));
    }

    /**
     * Prepare Layout
     */
    protected function _prepareLayout()
    {
          $this->addTab(
              'newproduct',
              [
                  'label' => __('Add New Products'),
                  'url' => $this->getUrl('newproduct/manage/productinfo', ['_current' => true]),
                  'class' => 'ajax'
              ]
          );
          return parent::_prepareLayout();
    }
}
