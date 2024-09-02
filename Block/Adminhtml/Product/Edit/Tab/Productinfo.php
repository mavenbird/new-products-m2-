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
namespace  Mavenbird\Newproduct\Block\Adminhtml\Product\Edit\Tab;

use Magento\Backend\Block\Widget\Grid\Column;
use Magento\Backend\Block\Widget\Grid\Extended;

class Productinfo extends Extended
{

   
    /**
     * @var $_coreRegistry
     */
    protected $_coreRegistry = null;

    /**
     * @var $_linkFactory
     */
    protected $_linkFactory;

    /**
     * @var $_setsFactory
     */
    protected $_setsFactory;

    /**
     * @var $_productFactory
     */
    protected $_productFactory;

    /**
     * @var $_type
     */
    protected $_type;

    /**
     * @var $_status
     */
    protected $_status;

    /**
     * @var $_visibility
     */
    protected $_visibility;

    /**
     * @var $_customcollection
     */
    protected $_customcollection;

    /**
     * @var $moduleManager
     */
    protected $moduleManager;
    
    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Module\Manager $moduleManager
     * @param \Mavenbird\Newproduct\Model\ResourceModel\Customcollection\Collection $customcollection
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Magento\Catalog\Model\Product\LinkFactory $linkFactory
     * @param \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory $setsFactory
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param \Magento\Catalog\Model\Product\Type $type
     * @param \Magento\Catalog\Model\Product\Attribute\Source\Status $status
     * @param \Magento\Catalog\Model\Product\Visibility $visibility
     * @param \Magento\Framework\Registry $coreRegistry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Module\Manager $moduleManager,
        \Mavenbird\Newproduct\Model\ResourceModel\Customcollection\Collection $customcollection,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Catalog\Model\Product\LinkFactory $linkFactory,
        \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory $setsFactory,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Catalog\Model\Product\Type $type,
        \Magento\Catalog\Model\Product\Attribute\Source\Status $status,
        \Magento\Catalog\Model\Product\Visibility $visibility,
        \Magento\Framework\Registry $coreRegistry,
        array $data = []
    ) {
        $this->_linkFactory = $linkFactory;
        $this->_customcollection = $customcollection;
        $this->_setsFactory = $setsFactory;
        $this->_productFactory = $productFactory;
        $this->_type = $type;
        $this->_status = $status;
        $this->_visibility = $visibility;
        $this->_coreRegistry = $coreRegistry;
        $this->moduleManager = $moduleManager;
        parent::__construct($context, $backendHelper, $data);
    }
    
    /**
     * Construct
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('main_section');
        $this->setDefaultSort('entity_id');
        $this->setUseAjax(true);
    }
    
    /**
     * PrepareCollection
     */
    protected function _prepareCollection()
    {
        $storeId = (int)$this->getRequest()->getParam('store', 0);
        $collection = $this->_productFactory->create()->getCollection()->addAttributeToSelect(
            'sku'
        )->addAttributeToSelect(
            'name'
        )->addAttributeToSelect(
            'attribute_set_id'
        )->addAttributeToSelect(
            'type_id'
        );
      
            $collection->joinAttribute(
                'name',
                'catalog_product/name',
                'entity_id'
            );
            $collection->joinAttribute(
                'custom_name',
                'catalog_product/name',
                'entity_id',
                null,
                'inner'
            );
            $collection->joinAttribute(
                'status',
                'catalog_product/status',
                'entity_id',
                null,
                'inner'
            );
            $collection->joinAttribute(
                'visibility',
                'catalog_product/visibility',
                'entity_id',
                null,
                'inner'
            );
            $collection->joinAttribute('price', 'catalog_product/price', 'entity_id', null, 'left');
            $customcollection=$this->_customcollection->getData();
        foreach ($customcollection as $custom) {
            if (($custom['store_id']==$storeId) && ($storeId!=0)) {
                $entityId_str=$custom['entity_id'];
                if (empty($entityId_str)) {
                    $entityId_str=0;
                }
                $entity= explode(",", $entityId_str);
            } else {
                $entity=0;
            }
            if ($storeId==0) {
                $entityId_str[]=$custom['entity_id'];
            }
             $store_ids[]=$custom['store_id'];
        }
        
        if ($customcollection) {
            if ($storeId==0) {
                $new_entityId= implode(",", $entityId_str);
                $new= explode(",", $new_entityId);
                $entity=array_unique($new);
            } elseif (!in_array($storeId, $store_ids)) {
                $entity=0;
            } else {
                $entity= explode(",", $entityId_str);
            }
        } else {
            $entity=0;
        }
        
        $collection->addFieldToFilter('entity_id', ['nin' => $entity]);
 
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
    
    /**
     * Prepare columns
     */
    protected function _prepareColumns()
    {
              $this->addColumn(
                  'in_products',
                  [
                    'type' => 'checkbox',
                    'name' => 'in_products',
                    'values' => $this->getSelectedProducts(),
                    'align' => 'center',
                    'index' => 'entity_id',
                    'header_css_class' => 'col-select',
                    'column_css_class' => 'col-select'
                  ]
              );
            
        $this->addColumn(
            'name',
            [
                        'header' => __('Name'),
                        'index' => 'name',
                        'header_css_class' => 'col-name',
                        'column_css_class' => 'col-name'
                ]
        );
        $this->addColumn(
            'type',
            [
                        'header' => __('Type'),
                        'index' => 'type_id',
                        'type' => 'options',
                        'options' => $this->_type->getOptionArray(),
                        'header_css_class' => 'col-type',
                        'column_css_class' => 'col-type'
                ]
        );
        $sets = $this->_setsFactory->create()->setEntityTypeFilter(
            $this->_productFactory->create()->getResource()->getTypeId()
        )->load()->toOptionHash();
 
        $this->addColumn(
            'set_name',
            [
                        'header' => __('Attribute Set'),
                        'index' => 'attribute_set_id',
                        'type' => 'options',
                        'options' => $sets,
                        'header_css_class' => 'col-attr-name',
                        'column_css_class' => 'col-attr-name'
                ]
        );
 
        $this->addColumn(
            'status',
            [
                        'header' => __('Status'),
                        'index' => 'status',
                        'type' => 'options',
                        'options' => $this->_status->getOptionArray(),
                        'header_css_class' => 'col-status',
                        'column_css_class' => 'col-status'
                ]
        );
 
        $this->addColumn(
            'visibility',
            [
                        'header' => __('Visibility'),
                        'index' => 'visibility',
                        'type' => 'options',
                        'options' => $this->_visibility->getOptionArray(),
                        'header_css_class' => 'col-visibility',
                        'column_css_class' => 'col-visibility'
                ]
        );
 
        $this->addColumn(
            'sku',
            [
                        'header' => __('SKU'),
                        'index' => 'sku',
                        'header_css_class' => 'col-sku',
                        'column_css_class' => 'col-sku'
                ]
        );
 
        $this->addColumn(
            'price',
            [
                        'header' => __('Price'),
                        'type' => 'currency',
                        'currency_code' => (string)$this->_scopeConfig->getValue(
                            \Magento\Directory\Model\Currency::XML_PATH_CURRENCY_BASE,
                            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                        ),
                        'index' => 'price',
                        'header_css_class' => 'col-price',
                        'column_css_class' => 'col-price'
                ]
        );
        return parent::_prepareColumns();
    }

    /**
     * Get selected products
     */
    protected function getSelectedProducts()
    {
        $products = $this->getProductsNewproduct();
        
        return $products;
    }
    
    /**
     * Get grid url
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/productinfogrid', ['_current' => true]);
    }
}
