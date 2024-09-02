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
namespace Mavenbird\Newproduct\Block\Adminhtml\Product;

class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * Construct
     */
    protected function _construct()
    {
        $this->_objectId = 'id';
        $this->_controller = 'adminhtml_product';
        $this->_blockGroup = 'Mavenbird_Newproduct';
        parent::_construct();

        $this->buttonList->update('save', 'label', __('Save Products'));
        $this->buttonList->update('delete', 'label', __('Delete Block'));
    }
}
