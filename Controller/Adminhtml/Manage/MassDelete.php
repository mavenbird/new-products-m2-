<?php
namespace Mavenbird\Newproduct\Controller\Adminhtml\Manage;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Customer\Model\Session;
use Magento\Store\Api\StoreRepositoryInterface;
use Mavenbird\Newproduct\Model\CustomcollectionFactory;
use Magento\Framework\Controller\ResultFactory;

class MassDelete extends Action
{

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var StoreRepositoryInterface
     */
    protected $storeRepository;

    /**
     * @var CustomcollectionFactory
     */
    protected $customCollectionFactory;

    /**
     * @param Context $context
     * @param Session $session
     * @param StoreRepositoryInterface $storeRepository
     * @param CustomcollectionFactory $customCollectionFactory
     */
    public function __construct(
        Context $context,
        Session $session,
        StoreRepositoryInterface $storeRepository,
        CustomcollectionFactory $customCollectionFactory
    ) {
        parent::__construct($context);
        $this->session = $session;
        $this->storeRepository = $storeRepository;
        $this->customCollectionFactory = $customCollectionFactory;
    }

    /**
     * Execute
     */
    public function execute()
    {
        $storeId = $this->session->getTestKey();
        $entityIds = $this->getRequest()->getParam('product');

        if (!is_array($entityIds) || empty($entityIds)) {
            $this->messageManager->addError(__('Please select product(s).'));
        } else {
            try {
                $count = count($entityIds);
                foreach ($entityIds as $entityId) {
                    $customCollection = $this->customCollectionFactory->create()->getCollection();
                    foreach ($customCollection as $custom) {
                        if ($storeId != 0) {
                            $this->processStoreSpecificDelete($custom, $storeId, $entityId);
                        } else {
                            $this->processAllStoresDelete($custom, $entityId);
                        }
                    }
                }
                $this->messageManager->addSuccess(
                    __('A total of %1 record(s) have been deleted.', $count)
                );
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * Process store specific delete
     *
     * @param Customcollection $custom
     * @param int $storeId
     * @param int $entityId
     */
    protected function processStoreSpecificDelete($custom, $storeId, $entityId)
    {
        if ($custom->getStoreId() == $storeId) {
            $this->removeEntityFromCustom($custom, $entityId);
        }
    }

    /**
     * Process all stores delete
     *
     * @param Customcollection $custom
     * @param int $entityId
     */
    protected function processAllStoresDelete($custom, $entityId)
    {
        $allStores = $this->storeRepository->getList();
        foreach ($allStores as $store) {
            if ($custom->getStoreId() == $store->getId()) {
                $this->removeEntityFromCustom($custom, $entityId);
            }
        }
    }

    /**
     * Remove entity from custom
     *
     * @param Customcollection $custom
     * @param int $entityId
     */
    protected function removeEntityFromCustom($custom, $entityId)
    {
        $entityArr = explode(",", $custom->getEntityId());
        if (in_array($entityId, $entityArr)) {
            $entityArr = array_diff($entityArr, [$entityId]);
            $newEntity = implode(",", $entityArr);
            $custom->setEntityId($newEntity);
            $custom->save();
        }
    }

    /**
     * Is allowed
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Mavenbird_Newproduct::newproduct');
    }
}
