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
namespace Mavenbird\Newproduct\Block\Widget;

class Newproductwidget extends \Mavenbird\Newproduct\Block\Newproduct implements \Magento\Widget\Block\BlockInterface
{
    /**
     *  Add data
     *
     * @param array $arr
     */
    public function addData(array $arr)
    {
        
        $this->_data = array_merge($this->_data, $arr);
    }

    /**
     * Set data
     *
     * @param string $key
     * @param mixed $value
     */
    public function setData($key, $value = null)
    {
        
        $this->_data[$key] = $value;
    }
 
    /**
     * To html
     */
    public function _toHtml()
    {
        if ($this->getData('template')) {
            $this->setTemplate($this->getData('template'));
        }
        return parent::_toHtml();
    }
}
