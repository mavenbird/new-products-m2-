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
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Customer\Model\Session;

class Index extends Action
{
    /**
     * @var $resultPageFactory
     */
    protected $resultPageFactory;

    /**
     * @var $session
     */
    protected $session;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param Session $session
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Session $session
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->session = $session;
    }

    /**
     * Execute
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Mavenbird_Newproduct::grid');
        $resultPage->getConfig()->getTitle()->prepend(__('MavenBird New Products'));

        if ($this->getRequest()->getQuery('ajax')) {
            $this->_forward('grid');
            return;
        }

        $store = (int)$this->getRequest()->getParam('store', 0);
        $this->session->setTestKey($store);

        return $resultPage;
    }

    /**
     * Is allowed
     */
    protected function _isAllowed()
    {
        return true;
    }
}
