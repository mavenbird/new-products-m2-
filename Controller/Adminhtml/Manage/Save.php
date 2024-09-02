<?php
namespace Mavenbird\Newproduct\Controller\Adminhtml\Manage;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Catalog\Model\ResourceModel\Product\Collection as ProductCollection;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Backend\Helper\Js as JsHelper;
use Magento\Store\Api\StoreRepositoryInterface;
use Mavenbird\Newproduct\Model\CustomcollectionFactory;

class Save extends Action
{

    /**
     * @var $collection
     */
    protected $collection;

    /**
     * @var $CustomerSession
     */
    protected $customerSession;

    /**
     * @var $jsHelper
     */
    protected $jsHelper;

    /**
     * @var $storeRepository
     */
    protected $storeRepository;

    /**
     * @var $customCollectionFactory
     */
    protected $customCollectionFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $collection
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Backend\Helper\Js $jsHelper
     * @param \Magento\Store\Api\StoreRepositoryInterface $storeRepository
     * @param \Mavenbird\Newproduct\Model\CustomcollectionFactory $customCollectionFactory
     */
    public function __construct(
        Context $context,
        ProductCollection $collection,
        CustomerSession $customerSession,
        JsHelper $jsHelper,
        StoreRepositoryInterface $storeRepository,
        CustomcollectionFactory $customCollectionFactory
    ) {
        $this->collection = $collection;
        $this->customerSession = $customerSession;
        $this->jsHelper = $jsHelper;
        $this->storeRepository = $storeRepository;
        $this->customCollectionFactory = $customCollectionFactory;

        parent::__construct($context);
    }
    
    /**
     * Execute
     */
    public function execute()
    {
        $session = $this->customerSession;
        $storeId = $session->getTestKey();
        $data = $this->getRequest()->getPostValue();
        $entityIds = $this->jsHelper->decodeGridSerializedInput($data['links']['newproduct']);
        
        if (!$this->isValidEntityIds($entityIds)) {
            $this->messageManager->addError(__('Please select product(s).'));
            return $this->_redirect('*/*/new');
        }

        $entityIdStr = implode(",", $entityIds);
        $allStores = $this->storeRepository->getList();
        $customCollection = $this->customCollectionFactory->create()->getCollection()->getData();
        $storeIds = array_map(fn($store) => $store->getId(), $allStores);

        if ($customCollection) {
            $this->processCustomCollection($customCollection, $storeId, $entityIdStr, $allStores, $storeIds);
        } else {
            $this->saveToStores([0, $storeId], $entityIdStr);
        }

        return $this->_redirect('*/*/');
    }

    /**
     * Is valid entity ids
     *
     * @param array $entityIds
     */
    protected function isValidEntityIds($entityIds)
    {
        return is_array($entityIds) && !empty($entityIds);
    }

    /**
     * Process custom collection
     *
     * @param array $customCollection
     * @param int $storeId
     * @param string $entityIdStr
     * @param array $allStores
     * @param array $storeIds
     */
    protected function processCustomCollection($customCollection, $storeId, $entityIdStr, $allStores, $storeIds)
    {
        $customStoreIds = array_column($customCollection, 'store_id');
        
        if (in_array($storeId, $customStoreIds)) {
            if ($storeId == 0) {
                $this->processAllStores($customCollection, $entityIdStr, $allStores);
            } else {
                $this->processSpecificStore($customCollection, $storeId, $entityIdStr, $storeIds);
            }
        } else {
            $this->saveToStores([0, $storeId], $entityIdStr);
        }
    }

    /**
     * Process all stores
     *
     * @param array $customCollection
     * @param string $entityIdStr
     * @param array $allStores
     */
    protected function processAllStores($customCollection, $entityIdStr, $allStores)
    {
        foreach ($allStores as $store) {
            $storeId = $store->getId();
            foreach ($customCollection as $custom) {
                if ($custom['store_id'] == $storeId) {
                    $this->updateCustomCollection($custom['id'], $storeId, $this->mergeEntityIds($entityIdStr, $custom['entity_id']));
                }
            }
            if (!in_array($storeId, array_column($customCollection, 'store_id'))) {
                $this->saveToStores([$storeId], $entityIdStr);
            }
        }
    }

    /**
     * Process specific store
     *
     * @param array $customCollection
     * @param int $storeId
     * @param string $entityIdStr
     * @param array $storeIds
     */
    protected function processSpecificStore($customCollection, $storeId, $entityIdStr, $storeIds)
    {
        foreach ([0, $storeId] as $store) {
            foreach ($customCollection as $custom) {
                if ($custom['store_id'] == $store) {
                    $newEntityId = $store == 0
                        ? $this->mergeEntityIds($entityIdStr, $custom['entity_id'])
                        : $entityIdStr . "," . $custom['entity_id'];

                    $this->updateCustomCollection($custom['id'], $store, $newEntityId);
                }
            }
        }
    }

    /**
     * Update custom collection
     *
     * @param int $id
     * @param int $storeId
     * @param string $entityIdStr
     */
    protected function updateCustomCollection($id, $storeId, $entityIdStr)
    {
        $data = $this->customCollectionFactory->create();
        $data->setData('entity_id', $entityIdStr);
        $data->setData('store_id', $storeId);
        $data->setData('id', $id);
        $data->save();
    }

    /**
     * Merge entity ids
     *
     * @param string $entityIdStr
     * @param string $customEntityId
     */
    protected function mergeEntityIds($entityIdStr, $customEntityId)
    {
        return implode(",", array_unique(array_merge(explode(",", $entityIdStr), explode(",", $customEntityId))));
    }

    /**
     * Save to stores
     *
     * @param array $storeIds
     * @param string $entityIdStr
     */
    protected function saveToStores(array $storeIds, $entityIdStr)
    {
        foreach ($storeIds as $storeId) {
            $data = $this->customCollectionFactory->create();
            $data->setData('entity_id', $entityIdStr);
            $data->setData('store_id', $storeId);
            $data->save();
        }
    }

    /**
     * Is Allowed
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Mavenbird_Newproduct::newproduct');
    }
}
