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
namespace  Mavenbird\Newproduct\Controller\Adminhtml\Manage;

class Grid extends \Magento\Backend\App\Action
{
    /**
     * Execute
     */
    public function execute()
    {
        
            $this->getResponse()->setBody(
                $this->_view->getLayout()->
                createBlock(Mavenbird\Newproduct\Block\Adminhtml\Product\Grid::class)->toHtml()
            );
    }

    /**
     * Is Allowed
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Mavenbird_Newproduct::newproduct');
    }
}
