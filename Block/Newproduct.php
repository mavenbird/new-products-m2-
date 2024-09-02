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
namespace Mavenbird\Newproduct\Block;

class Newproduct extends \Magento\Catalog\Block\Product\AbstractProduct
{
    /**
     * @var $_npmanualCollection
     */
    protected $_npmanualCollection;

    /**
     * @var $_config
     */
    protected $_config;

    /**
     * @var $_sliderconfig
     */
    protected $_sliderconfig;

    /**
     * @var $urlHelper
     */
    protected $urlHelper;

    /**
     * @var $_imageHelper
     */
    protected $_imageHelper;

    /**
     * @var $_storeManager
     */
    protected $_storeManager;

    /**
     * @var $_productCollectionFactory
     */
    protected $_productCollectionFactory;

    /**
     * @var $pager
     */
    protected $pager;

    /**
     * @var PAGE_VAR_NAME
     */
    public const PAGE_VAR_NAME = 'np';

    /**
     * @var Output
     */
    protected $outputHelper;

    /**
     * @var Image
     */
    protected $image;

    /**
     * @var Data
     */
    protected $dataHelper;

    /**
     * @var Compare
     */
    protected $compareHelper;

    /**
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \Magento\Framework\App\ResourceConnection $resource
     * @param \Magento\Framework\Url\Helper\Data $urlHelper
     * @param \Mavenbird\Newproduct\Model\ResourceModel\Customcollection\Collection $npmanualCollection
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param \Magento\CatalogInventory\Helper\Stock $stockHelper
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Magento\Catalog\Helper\Output $outputHelper
     * @param \Magento\Catalog\Helper\Image $image
     * @param \Magento\Wishlist\Helper\Data $dataHelper
     * @param \Magento\Catalog\Helper\Compare $compareHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Framework\Url\Helper\Data $urlHelper,
        \Mavenbird\Newproduct\Model\ResourceModel\Customcollection\Collection $npmanualCollection,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\CatalogInventory\Helper\Stock $stockHelper,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Catalog\Helper\Output $outputHelper,
        \Magento\Catalog\Helper\Image $image,
        \Magento\Wishlist\Helper\Data $dataHelper,
        \Magento\Catalog\Helper\Product\Compare $compareHelper,
        array $data = []
    ) {
    
        $this->_coreResource = $resource;
        $this->urlHelper = $urlHelper;
        $this->_npmanualCollection = $npmanualCollection;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->stockHelper = $stockHelper;
        $this->_objectManager = $objectManager;
        $this->outputHelper = $outputHelper;
        $this->image = $image;
        $this->dataHelper = $dataHelper;
        $this->compareHelper = $compareHelper;
        parent::__construct($context, $data);
    }

    /**
     * Get config
     */
    public function getConfig()
    {
        return $this->_scopeConfig->getValue('newproduct/setting', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get slider config
     */
    public function getSliderconfig()
    {
        return $this->_scopeConfig->getValue('newproduct/slidersetting', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
    
    /**
     * To html
     */
    public function _toHtml()
    {
    
        $this->_config=$this->getConfig();
        if ($this->_config['enable']=="0") {
            return '';
        }
        
        if (!$this->getTemplate()) {
            $this->setTemplate('new_grid.phtml');
        }
        return parent::_toHtml();
    }
    
    /**
     * Get widget options
     */
    public function setWidgetOptions()
    {
        
        $this->setShowHeading((bool)$this->getWdShowHeading());
        $this->setHeading($this->getWdHeading());
        $this->setProductType($this->getWdProductType());
        $this->setDefaultNew($this->getWdDefaultNew());
        $this->setAddDays($this->getWdAddDays());
        $this->setNewproduct($this->getWdNewproduct());
        $this->setCategories($this->getWdCategories());
        $this->setSortBy($this->getWdSortBy());
        $this->setSortOrder($this->getWdSortOrder());
        $this->setProductsPrice((bool)$this->getWdPrice());
        $this->setDescription((bool)$this->getWdDescription());
        $this->setAddToCart((bool)$this->getWdCart());
        $this->setAddToWishlist((bool)$this->getWdWishlist());
        $this->setAddToCompare((bool)$this->getWdCompare());
        $this->setOutOfStock((bool)$this->getWdOutStock());
        $this->setAjaxscrollPage((bool)$this->getWdAjaxscrollPage());
        //Template Settings
        $this->setNoOfProduct((int)$this->getWdNoOfProduct());
        $this->setProductsPerRow((int)$this->getWdProductsPerRow());
        $this->setProductsPerPage($this->getWdProductsPerPage());
        $this->setShowSlider((bool)$this->getWdSlider());
        
        //slider Settings
        $this->setAutoscroll((bool)$this->getWdAutoscroll());
        //$this->setPagination((bool)$this->getWdPagination());
        $this->setNavarrow((bool)$this->getWdNavarrow());
    }
    
    /**
     * Set config value
     */
    public function setConfigValues()
    {
        $this->_config=$this->getConfig();
        $this->_sliderConfig=$this->getSliderconfig();
        
        $this->setEnabled((bool)$this->_config['enable']);
        $this->setShowHeading((bool)$this->_config['show_heading']);
        $this->setProductType($this->_config['product_type']);
        $this->setDefaultNew($this->_config['default_new']);
        $this->setAddDays($this->_config['add_days']);
        $this->setHeading($this->_config['heading']);
        $this->setNewproduct($this->_config['newproduct']);
        $this->setCategories($this->_config['categories']);
        $this->setSortBy($this->_config['sort_by']);
        $this->setSortOrder($this->_config['sort_order']);
        $this->setProductsPrice((bool)$this->_config['price']);
        $this->setDescription((bool)$this->_config['description']);
        $this->setAddToCart((bool)$this->_config['cart']);
        $this->setAddToWishlist((bool)$this->_config['wishlist']);
        $this->setAddToCompare((bool)$this->_config['compare']);
        $this->setOutOfStock((bool)$this->_config['out_stock']);
        $this->setAjaxscrollPage((bool)$this->_config['enable_ajaxscroll_page']);
        //Template Settings
        $this->setNoOfProduct((int)$this->_config['no_of_product']);
        $this->setProductsPerRow((int)$this->_config['products_per_row']);
        $this->setProductsPerPage($this->_config['per_page_value']);
        $this->setShowSlider((bool)$this->_config['slider']);

        //slider Settings
        $this->setAutoscroll((bool)$this->_sliderConfig['autoscroll']);
        //$this->setPagination((bool)$this->_sliderConfig['pagination']);
        $this->setNavarrow((bool)$this->_sliderConfig['navarrow']);
    }
    
    /**
     * Before to html
     */
    protected function _beforeToHtml()
    {
        
        if ($this->getType()== \Mavenbird\Newproduct\Block\Widget\Newproductwidget\Interceptor::class) {
            $this->setWidgetOptions();
        } elseif ($this->getType()==\Mavenbird\Newproduct\Block\Widget\Newproductwidget::class) {
            $this->setWidgetOptions();
        } else {
            $this->setConfigValues();
        }
        $this->setProductCollection($this->getNewproductsCollection());
        return parent::_beforeToHtml();
    }
    
    /**
     * Get pager html
     */
    public function getPagerHtml()
    {
        if ($this->getProductCollection()->getSize()) {
            if (!$this->pager) {
                 $this->pager = $this->getLayout()->createBlock(
                     \Magento\Catalog\Block\Product\Widget\Html\Pager::class,
                     'new.pager'
                 );
                 
                $this->pager->setUseContainer(true)
                    ->setShowAmounts(true)
                    ->setShowPerPage(false)
                    ->setPageVarName('np')
                    ->setLimit($this->getProductsPerPage())
                    ->setTotalLimit($this->getNoOfProduct())
                    ->setCollection($this->getProductCollection());
            }
            if ($this->pager instanceof \Magento\Framework\View\Element\AbstractBlock) {
                return $this->pager->toHtml();
            }
        }
        return '';
    }
    
    /**
     * Get Product collection for Auto Newproduc
     */
    public function getNewAutoCollection()
    {
        $todayStartOfDayDate = $this->_localeDate->date()->setTime(0, 0, 0)->format('Y-m-d H:i:s');
        $todayEndOfDayDate = $this->_localeDate->date()->setTime(23, 59, 59)->format('Y-m-d H:i:s');
        $collection = $this->_productCollectionFactory->create();
        $collection->setVisibility(\Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH);

        $collection = $this->_addProductAttributesAndPrices(
            $collection
        )->addStoreFilter()->addAttributeToFilter(
            'news_from_date',
            [
                'or' => [
                    0 => ['date' => true, 'to' => $todayEndOfDayDate],
                    1 => ['is' => new \Zend_Db_Expr('null')],
                ]
            ],
            'left'
        )->addAttributeToFilter(
            'news_to_date',
            [
                'or' => [
                    0 => ['date' => true, 'from' => $todayStartOfDayDate],
                    1 => ['is' => new \Zend_Db_Expr('null')],
                ]
            ],
            'left'
        )->addAttributeToFilter(
            [
                ['attribute' => 'news_from_date', 'is' => new \Zend_Db_Expr('not null')],
                ['attribute' => 'news_to_date', 'is' => new \Zend_Db_Expr('not null')],
            ]
        )->addAttributeToSort(
            'news_from_date',
            'desc'
        )->setPageSize(
            $this->getProductsCount()
        )->setCurPage(
            1
        );
        $this->_logger->debug("Collection data: " . json_encode($collection->getData()));
        return $collection;
    }

    /**
     * Get new manual collection
     */
    public function getNewManualCollection()
    {
        $storeId=$this->_storeManager->getStore()->getId();
        $product_ids=$this->getProductsIds();
        $collection = $this->_productCollectionFactory->create();
        $collection = $this->_addProductAttributesAndPrices($collection)
            ->setStore($storeId)
            ->addStoreFilter($storeId)
            ->addFieldToFilter('entity_id', ['in' =>$product_ids])
            ->addAttributeToFilter('visibility', 4);
           
        return $collection;
    }
    
    /**
     * Get new product by created date
     */
    protected function getNewProductsByCreatedDate()
    {
        $days = $this->getAddDays();
        $today = strtotime($this->_localeDate->date()->format('Y-m-d H:i:s'));
        $last = $today - (60*60*24*$days);
        $from = date("Y-m-d H:i:s", $last);
        $to = date("Y-m-d H:i:s", $today);
         $collection = $this->_productCollectionFactory->create();
        // $collection->setVisibility($this->visibilityModel->getVisibleInCatalogIds());
        $collection = $this->_addProductAttributesAndPrices($collection);
        $collection->addAttributeToSelect('*')
                ->addAttributeToSort('created_at', 'desc')
                ->addAttributeToFilter('created_at', ['from' => $from, 'to' => $to]);
                
        return $collection;
    }
    
    /**
     * Get new product by created date
     */
    public function getNewproductsCollection()
    {
        switch ($this->getProductType()) {
            case 2:
                if ($this->getDefaultNew()) {
                    $collection = $this->getNewAutoCollection();
                } else {
                    $collection = $this->getNewProductsByCreatedDate();
                }
                break;
            case 1: //Manually
                $collection = $this->getNewManualCollection();
                break;
            case 0: //Both
                if ($this->getDefaultNew()) {
                    $collection1 = $this->getNewAutoCollection();
                } else {
                    $collection1 = $this->getNewProductsByCreatedDate();
                }
                $collection2 = $this->getNewManualCollection();
                        
                $merged_ids = array_unique(array_merge($collection1->getAllIds(), $collection2->getAllIds()));
                 $collection = $this->_productCollectionFactory->create();
                $collection->addAttributeToSelect('*')
                ->addFieldToFilter('entity_id', ['in' =>$merged_ids]);
                break;
            default:
                $collection = $this->getNewAutoCollection();
                break;
        }
        
        $product_ids = $collection->getAllIds();
        $page=($this->getRequest()->getParam('np'))? $this->getRequest()->getParam('np') : 1;
        
        $total_limit = $this->getNoOfProduct();
        $product_ids = array_slice($product_ids, 0, $total_limit);
        // print_r($total_limit);
        // print_r($product_ids);
       //for limit the collection to solve last pagination number of items issue
        
        $storeId=$this->_storeManager->getStore()->getId();
        /** @var $collection \Magento\Catalog\Model\ResourceModel\Product\Collection */
        $collection = $this->_productCollectionFactory->create();
        $collection = $this->_addProductAttributesAndPrices($collection)
            ->setStore($storeId)
            ->addStoreFilter($storeId)
            ->addFieldToFilter('entity_id', ['in' => $product_ids])
            ->addAttributeToFilter('visibility', 4);
        
        if (!$this->getShowSlider()) {
            $collection->setPageSize($this->getProductsPerPage())
                       ->setCurPage($page);
        } else {
            $collection->setPageSize($this->getNoOfProduct());
        }
        // echo $this->getProductsPerPage();
        //    print_r($collection->getData());exit;
        //Display out of stock products
        if (!$this->getOutOfStock()) {
            $this->stockHelper->addInStockFilterToCollection($collection);
        }
    
        //Display By Category
        
        if ($this->getNewproduct()==2) {
            if ($this->getCategories()) {
                 $categories=ltrim($this->getCategories(), ",");
                $categorytable = $this->_coreResource->getTableName('catalog_category_product');
                $collection->getSelect()
                        ->joinLeft(['ccp' => $categorytable], 'e.entity_id = ccp.product_id', 'ccp.category_id')
                        ->group('e.entity_id')
                        ->where("ccp.category_id IN (".$categories.")");
            }
        }
        
        //Set Sort Order
        if ($this->getSortOrder()=='rand') {
            $collection->getSelect()->order('rand()');
        } else {
            if ($this->getSortBy()=='position') {

                $collection->getSelect()->order('e.entity_id ' . $this->getSortOrder());
            } else {
                 $collection->addAttributeToSort($this->getSortBy(), $this->getSortOrder());
            }
        }
               
        return $collection;
    }
    
    /**
     * Get product Ids
     */
    public function getProductsIds()
    {
        $storeId=$this->_storeManager->getStore()->getId();
        $customcollection=$this->_npmanualCollection->getData();
    
        foreach ($customcollection as $custom) {
            if ($custom['entity_id']!='') {
                if ($custom['store_id']==$storeId) {
                    $product_ids=$custom['entity_id'];
                }
            }
        }
        
        if (empty($product_ids)) {
            foreach ($customcollection as $custom) {
                $store_arr=[0,$storeId];
                foreach ($store_arr as $store) {
                    if ($custom['store_id']==$store) {
                        $product_ids[]=$custom['entity_id'];
                    }
                }
            }
            if (!empty($product_ids)) {
                $new_entityId= implode(",", $product_ids);
                $new= explode(",", $new_entityId);
                $entity=array_unique($new);
            } else {
                return $product_ids=[0];
            }
            return $entity;
        } else {
            $entity= explode(",", $product_ids);
            return $entity;
        }
    }
    
    /**
     * Get imagehelper
     */
    public function getImageHelper()
    {
        return  $this->imageHelper;
    }

    /**
     * Get add to cart ost params
     *
     * @param \Magento\Catalog\Model\Product $product
     */
    public function getAddToCartPostParams(\Magento\Catalog\Model\Product $product)
    {
        $url = $this->getAddToCartUrl($product);
        return [
            'action' => $url,
            'data' => [
            'product' => $product->getEntityId(),
            \Magento\Framework\App\Action\Action::PARAM_NAME_URL_ENCODED =>
                $this->urlHelper->getEncodedUrl($url),
            ]
        ];
    }

    /**
     * Get unique
     */
    public function getUniqueSliderKey()
    {
        $key = uniqid();
        return $key;
    }

    /**
     * Get outputhelper
     */
    public function getOutputHelper()
    {
        return $this->outputHelper;
    }

    /**
     * Get Datahelper
     */
    public function getDataHelper()
    {
        return $this->dataHelper;
    }

    /**
     * Get Image
     */
    public function getImageData()
    {
        return $this->image;
    }

    /**
     * Get Comparehelper
     */
    public function getCompareHelper()
    {
        return $this->compareHelper;
    }
}
