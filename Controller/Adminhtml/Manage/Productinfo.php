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
namespace Mavenbird\Newproduct\Controller\Adminhtml\Manage;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\LayoutFactory;
use Magento\Framework\Controller\ResultFactory;

class Productinfo extends Action
{
    /**
     * @var $resultLayoutFactory
     */
    protected $resultLayoutFactory;

    /**
     * Construct
     *
     * @param Action\Context $context
     * @param LayoutFactory $resultLayoutFactory
     */
    public function __construct(
        Action\Context $context,
        LayoutFactory $resultLayoutFactory
    ) {
        parent::__construct($context);
        $this->resultLayoutFactory = $resultLayoutFactory;
    }

    /**
     * Execute
     */
    public function execute()
    {
        /** @var \Magento\Framework\View\Result\Layout $resultLayout */
        $resultLayout = $this->resultLayoutFactory->create();
        
        // Assuming 'newproduct.product.edit.tab.productinfo' is a block name
        $block = $resultLayout->getLayout()->getBlock('newproduct.product.edit.tab.productinfo');
        
        if ($block) {
            $block->setProductsNewproduct($this->getRequest()->getPost('products_newproduct', null));
        }

        return $resultLayout;
    }

    /**
     * Is allowed
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Mavenbird_Newproduct::newproduct');
    }
}
