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
namespace Mavenbird\Newproduct\Model;

use \Magento\Backend\Block\Template\Context;
use \Magento\Framework\Registry;
use \Magento\Framework\Data\FormFactory;
use \Magento\Store\Model\System\Store;
use \Magento\Catalog\Model\ResourceModel\Category\Tree;
use \Magento\Catalog\Model\Category;

class Categories implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var $_systemStore
     */
    protected $_systemStore;

    /**
     * @var $_categoryTree

     */
    protected $_categorytree;

    /**
     * @var $_categoryFlatState
     */
    protected $categoryFlatConfig;

    /**
     * Construct
     *
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param Store $systemStore
     * @param Tree $categorytree
     * @param Category $categoryFlatState
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Store $systemStore,
        Tree $categorytree,
        Category $categoryFlatState,
        array $data = []
    ) {
        $this->_systemStore = $systemStore;
        $this->_categorytree = $categorytree;
        $this->categoryFlatConfig = $categoryFlatState;
    }
    /**
     * Build multiselect values
     *
     * @param \Magento\Catalog\Model\Category $node
     * @param array $values
     * @param int $level
     */
    public function buildCategoriesMultiselectValues($node, $values, $level = 0)
    {
        $nonEscapableNbspChar = "\xC2\xA0";

        $level++;
        if ($level > 2) {
            $values[$node->getId()]['value'] = $node->getId();
            $values[$node->getId()]['label'] = str_repeat($nonEscapableNbspChar, ($level - 3) * 5).$node->getName();
        }

        foreach ($node->getChildren() as $child) {
            $values = $this->buildCategoriesMultiselectValues($child, $values, $level);
        }

        return $values;
    }

    /**
     * To option array
     */
    public function toOptionArray()
    {
        $tree = $this->_categorytree->load();

        $parentId = 1;

        $root = $tree->getNodeById($parentId);

        if ($root && $root->getId() == 1) {
            $root->setName('Root');
        }

        $collection = $this->categoryFlatConfig->getCollection()
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('is_active');

        $tree->addCollectionData($collection, true);

        $values['---'] = [
            'value' => '',
            'label' => '',
        ];
        return $this->buildCategoriesMultiselectValues($root, $values);
    }
}
