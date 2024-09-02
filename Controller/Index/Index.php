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
namespace Mavenbird\Newproduct\Controller\Index;

use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\App\RequestInterface;
use Magento\Store\Model\ScopeInterface;
use \Magento\Framework\App\Action\Action;

class Index extends \Magento\Framework\App\Action\Action
{

   
    /**
     * @var XML_PATH_ENABLED
     */
    public const XML_PATH_ENABLED = 'newproduct/setting/enable';

    /**
     * @var $scopeConfig
     */
    protected $scopeConfig;

    /**
     * @var $resultPageFactory
     */
    protected $resultPageFactory;

    /**
     * Construct
     *
     * @param  \Magento\Framework\App\Action\Context $context
     * @param  \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param  \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
    
        parent::__construct($context);
        $this->scopeConfig = $scopeConfig;
        $this->_config = $scopeConfig->getValue('newproduct/setting', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Dispatch
     *
     * @param RequestInterface $request
     */
    public function dispatch(RequestInterface $request)
    {
        if (!$this->scopeConfig->isSetFlag(self::XML_PATH_ENABLED, ScopeInterface::SCOPE_STORE)) {
            throw new NotFoundException(__('Page not found.'));
        }
        return parent::dispatch($request);
    }
    
    /**
     * Execute
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('New Products'));

        return $resultPage;
    }
}
